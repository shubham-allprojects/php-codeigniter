<?php
namespace App\Controllers\api;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\Log;
use App\Models\CommonModel;

class Auth extends ResourceController
{
    protected $defaultModel;
    protected $networkModel;
    protected $lang;
    public function __construct(){
        $this->defaultModel = new CommonModel('default');
        $this->networkModel = new CommonModel('network');
    }
    public function login(){
        $validation = $this->validate([
            'login_id'  => ['label' => 'User Id', 'rules' => 'required'],
            'login_pw'  => ['label' => 'Password', 'rules' => 'required']
        ]);

        if(!$validation){
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $login_id = $this->request->getVar('login_id');
        $login_pw = $this->request->getVar('login_pw');
        $login_site = $this->request->getVar('login_site');
        $requestFrom = $this->request->getVar('requestFrom');
        
        $sessArr = [];

        if (empty($login_site) || $login_site == 0)
            $login_site = '1';

        $licensekey = $this->networkModel->selectSingleRow('network', 'LicenseKey, Model, Type', []);

        $connect_user = 0;
        if(!empty($licensekey)){
            if ($licensekey['Model'] == 1)
                $connect_user = 8;
            if ($licensekey['Model'] == 2)
                $connect_user = 16;
            if ($licensekey['Model'] == 3)
                $connect_user = 32;

            $sessArr['spider_key']   = $licensekey['LicenseKey'];
            $sessArr['spider_model'] = $licensekey['Model'];
            $sessArr['spider_kind']  = $licensekey['Type']; 
        }

        $ctrl = $this->defaultModel->selectSingleRow('Controller', '*', ['ID' => $login_id, 'Password' => $login_pw, 'No' => 1]);

        if(!empty($ctrl)){
            $sessArr['spider_type']     = 'spider';
            $sessArr['spider_userno']   = '';
            $sessArr['spider_userrole'] = 'free_auth';
            $sessArr['spider_id']       = $ctrl['ID'];
            $sessArr['spider_name']     = "Super Admin";
            $sessArr['spider_userimg']  = '';
            $sessArr['spider_site']     = $login_site;
            $sessArr['spider_language']   = $ctrl['Language'];
            $sessArr['spider_page']       = $ctrl['DefaultPage'];
            $sessArr['spider_floor']      = $ctrl['DefaultFloorNo'];
            $sessArr['spider_floorstate'] = $ctrl['DefaultFloorState'];
            $sessArr['spider_auto_disconnect_time'] = 1;

            $optionvalue = $this->networkModel->selectSingleRow('Option', 'OptionValue', []);
            
            if (!empty($optionvalue))
            {
                $sessArr['spider_option']   = $optionvalue['OptionValue'];
            }
            if($requestFrom === 'web'){
                $session = session();
                $session->set($sessArr);
            }

            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [ 'success' => 'Logged In' ],
                'data'      => $sessArr
            ];
            
            Log::set_log('user', 'login');
            return $this->respond($response);
        }


        $webuser = $this->defaultModel->selectSingleRow('WebUser', '*', ['ID' => $login_id, 'Password' => $login_pw, 'Site' => $login_site]);
        
        if(!empty($webuser))
        {
            $max_concurrent	= EnumTable::$attrModelSpec[session()->get('spider_model')][session()->get('spider_kind')][15];
            
            $count  = $this->networkModel->selectRowsCount("sessions", ['user_id !=' => $login_id, 'session_expiration' => time(), 'status' => 1]);

            if( $count >= $max_concurrent )
            {
                return $this->fail(lang('Message.Common.error_concurrent_over'));
            }

            $sessArr['spider_type']     = $webuser['Type'];
            $sessArr['spider_userno']   = $webuser['No'];
            $sessArr['spider_userrole'] = $webuser['UserRole'];
            $sessArr['spider_id']       = $webuser['ID'];
            $sessArr['spider_name']     = $webuser['name'];
            $sessArr['spider_userimg']  = $webuser['ImageFile'];
            $sessArr['spider_site']     = $login_site;

            $sessArr['spider_language']   = $webuser['Language'];
            $sessArr['spider_page']       = $webuser['DefaultPage'];
            $sessArr['spider_defaultpage'] = $webuser['DefaultPage'];
            $sessArr['spider_floor']      = $webuser['DefaultFloorNo'];
            $sessArr['spider_floorstate'] = $webuser['DefaultFloorState'];
            $sessArr['spider_auto_disconnect_time'] = $webuser['AutoDisconnectTime'];

            $optionvalue = $this->networkModel->selectSingleRow('Option', 'OptionValue', []);
            if (!empty($optionvalue))
            {
                $sessArr['spider_option']   = $optionvalue['OptionValue'];
            }

            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [ 'success' => 'Logged In' ],
                'data'      => $sessArr
            ];
            
            Log::set_log('user', 'login');
            return $this->respond($response);
        } else {
            Log::set_log_id('user', '_execfail', $login_id);
           
            return $this->fail(lang('Message.User.error_login_fail'));
        }
    }


    public function forgotPassword(){}
}
