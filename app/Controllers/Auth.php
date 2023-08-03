<?php
namespace App\Controllers;
use App\Models\CommonModel;

class Auth extends BaseController
{
    protected $networkModel;
    protected $defaultModel;
    public function __construct() {
        $this->networkModel = new CommonModel('network');
        $this->defaultModel = new CommonModel('default');
    }
    public function login()
    {
        $data['arr_site'] = $this->to_array_site();
        $data['spider_model'] = '0';
        $licensekey   = $this->networkModel->selectSingleRow("Network", "LicenseKey, Model, Type",[]);
        
        if ( !empty($licensekey) )
        {
            $data['spider_model'] = $licensekey['Model'];
        }

        if(!empty(session()->get('spider_id'))){
            $this->display([], 'wizard/index', ['header' => 'css', 'footer' => '']);
        }else{
            return view('auth/login', $data);
        }
        
    }
    public function to_array_site()
    {
        $data = $this->defaultModel->selectAllRows('Site', 'No, Name', []);
        $arr = array();

        foreach( $data as $val)
        {
            $arr[$val['No']] = $val['Name'];
        }
        return $arr;
    }

    public function forgot_password(){
        exec(SPIDER_COMM." smtp pwrqsend " . $this->input::post('login_id', ''));
        $this->util::alert("Sent a request message to the administrator.");
    }
    public function logout(){
        session()->destroy();
        return redirect()->to(base_url()); 
    }
}
