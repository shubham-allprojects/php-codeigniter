<?php
namespace App\Controllers;

class Api extends BaseController
{
    // ----------------------------------------------------------------------------------

    public function index()
    {
        $vars = ['baseController' => $this];
        $this->display($vars, 'api/api', ['header' => 'css', 'footer' => '']);
    }

    // ----------------------------------------------------------------------------------

    public function select()
    {
        $field = $this->input::get('f');
        $word = $this->input::get('w');
        $page = $this->input::get('p', 1);
        $view = $this->input::get('v');

        $page_config = PAGE_CONFIG;
        $pagination = $this->pagination;

        if (empty($field) || empty($word)) {
            $count = $this->api_conn->prepare("SELECT COUNT(*) FROM apiCredential");
            $count->execute();
            $count = $count->fetchColumn();

            $page_config['current_page'] = $page;
            $page_config['total_row'] = $count;
            $pagination->init($page_config);

            $list = $this->api_conn->prepare("SELECT * FROM apiCredential, apiProperty ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $count = $this->api_conn->prepare("SELECT COUNT(*) FROM apiCredential WHERE $field LIKE ?");
            $count->execute(array("%$word%"));
            $count = $count->fetchColumn();

            $page_config['current_page'] = $page;
            $page_config['total_row'] = $count;
            $pagination->init($page_config);

            $list = $this->api_conn->prepare("SELECT * FROM apiCredential, apiProperty WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);

        }

        foreach ($list as $key => $val) {
            $val['api_no'] = $val['No'];
            $val['api_username'] = $val['UserName'];
            $val['api_password'] = $this->decrypt_data($val['Password']);
            $val['api_url'] = $val['URL'];
            $val['property_id'] = $val['PropertyID'];
            $val['api_service'] = $val['ApiService'];
            $val['is_license'] = $val['isLicense'];
            $val['is_valid'] = $val['isValid'];
            $val['last_connection'] = $val['LastConnection'];
            $val['property_name'] = $val['MarketingName'];
            $val['ExistData'] = $val['SkipOverwrite'];
            $val['city'] = $val['City'];
            $val['state'] = $val['State'];
            $val['postal_code'] = $val['PostalCode'];
            $val['property_website'] = $val['Website'];

            $val['property_address'] = $val['Address'] . ',' . $val['City'] . ',' . $val['State'] . ',' . $val['PostalCode'];
            $val['Address'] = $val['property_address'];
            $list[$key] = $val;
        }

        $result['field'] = $field;
        $result['word'] = $word;
        $result['page'] = $page;
        $result['view'] = $view;
        $result['pages'] = $pagination->get_pages();
        $result['count'] = $count;
        $result['list'] = $list;

        if (empty($list)) {
            $result['list'] = array(array("emptydata" => "1"));
            $result['count'] = "1";

            $result['pages'] = array("1" => 1);
        }

        echo json_encode($result);
    }

    public function is_apiData_present()
    {
        $count = $this->api_conn->prepare("SELECT COUNT(*) FROM apiCredential");
        $count->execute();
        $count = $count->fetchColumn();
        echo $count;
        exit;
    }

    public function encrypt_data($data)
    {
        $passphrase = exec(SPIDER_COMM . " clntdb update");

        $secret_key = hex2bin($passphrase);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $iv = 'encryptionIntVec';
        $encrypted_64 = openssl_encrypt($data, 'aes-256-cbc', $secret_key, 0, $iv);
        $iv_64 = base64_encode($iv);
        $json = new stdClass();
        $json->iv = $iv_64;
        $json->data = $encrypted_64;
        return base64_encode(json_encode($json));
    }

    public function decrypt_data($data)
    {
        $passphrase = exec(SPIDER_COMM . " clntdb update");

        $secret_key = hex2bin($passphrase);
        $json = json_decode(base64_decode($data));
        $iv = base64_decode($json->{'iv'});
        $encrypted_64 = $json->{'data'};
        $data_encrypted = base64_decode($encrypted_64);
        $decrypted = openssl_decrypt($data_encrypted, 'aes-256-cbc', $secret_key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    public function check_admin_pwd()
    {
        $confirm_pw = $this->input::post('confirm_pw');
        $response = array();
        $errorFlag = true;

        if ($_SESSION['spider_type'] == 'spider') {
            $ctrl = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
            $ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
            if (!($ctrl = $ctrl->fetch(\PDO::FETCH_ASSOC))) {
                $response['error'] = 1;
                $response['message'] = $this->lang->user->error_login_pw_mismatch;
                $errorFlag = true;
            } else {
                $response['error'] = 0;
                $response['message'] = 'Password match';
                $errorFlag = false;
            }
        }
        echo json_encode($response);
        exit;
    }

    public function fetch_schedule_data()
    {
        $list = $this->api_conn->prepare("SELECT * FROM apiSchedule");
        $list->execute();
        $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        echo json_encode($list);
    }

    public function test_api($api_jsonrequest)
    {

        $apiOutput = exec("python /usr/lib/python2.7/site-packages/entrata/api.py test_api '$api_jsonrequest'", $output, $result);
        if ($result == 0) {
            $response['error'] = 0;
            $output[0] = str_replace("'", '"', $output[0]);
            $api_response = json_decode($output[0], true);
            if (isset($api_response['error']) && $api_response['error'] == 1) {
                $response['error'] = 1;
                $response['message'] = $api_response['message'];
            } else {
                $response['message'] = $api_response;
            }
        } else {
            $response['error'] = 1;
            $response['message'] = "Connection Error";
        }

        return $response;
    }

    public function test_api_connection()
    {

        $api_url = trim($this->input::post('api_url'));
        $api_username = trim($this->input::post('api_username'));
        $api_password = trim($this->input::post('api_password'));
        $property_id = trim($this->input::post('property_id'));

        $errorFlag = true;

        $response = array();

        if (!empty($api_url) && !filter_var($api_url, FILTER_VALIDATE_URL) === true) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_valid_url_required;
            $errorFlag = true;
        } else if (empty($api_url)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_url_required;
            $errorFlag = true;
        } else if (empty($api_username)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->provide_username;
            $errorFlag = true;
        } else if (empty($api_password)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->provide_password;
            $errorFlag = true;
        } else if (empty($property_id)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_property_id;
            $errorFlag = true;
        } else if (!empty(property_id) && !filter_var($property_id, FILTER_VALIDATE_INT) === true) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_valid_propertyid;
            $errorFlag = true;
        } else {
            $errorFlag = false;
        }

        if (!$errorFlag) {

            $api_jsonrequest = array("property_id" => $property_id, "username" => $api_username, "password" => $api_password, "api_url" => $api_url, "api_service" => "entrata");
            $api_jsonrequest = json_encode($api_jsonrequest);

            $response = $this->test_api($api_jsonrequest);
        }

        echo json_encode($response);
        exit;
    }

    public function save_api_data()
    {

        $api_url = trim($this->input::post('api_url'));
        $api_username = trim($this->input::post('api_username'));
        $api_password = trim($this->input::post('api_password'));
        $api_service = 'entrata';
        $property_id = trim($this->input::post('property_id'));
        $ExistData = trim($this->input::post('ExistData'));
        //$property_id  = trim($this->input::post('property_id'));
        $data_overwrite = trim($this->input::post('ExistData'));
        $this->systemLogger("$property_id data exists $data_overwrite");

        $schedule_time_1_h = trim($this->input::post('schedule_time_1_h'));
        $schedule_time_1_m = trim($this->input::post('schedule_time_1_m'));
        $schedule_time_2_h = trim($this->input::post('schedule_time_2_h'));
        $schedule_time_2_m = trim($this->input::post('schedule_time_2_m'));
        $schedule_time_3_h = trim($this->input::post('schedule_time_3_h'));
        $schedule_time_3_m = trim($this->input::post('schedule_time_3_m'));
        $schedule_time_4_h = trim($this->input::post('schedule_time_4_h'));
        $schedule_time_4_m = trim($this->input::post('schedule_time_4_m'));

        $schedule_time_1 = $this->input::post('schedule_time_1', '0');
        $schedule_time_2 = $this->input::post('schedule_time_2', '0');
        $schedule_time_3 = $this->input::post('schedule_time_3', '0');
        $schedule_time_4 = $this->input::post('schedule_time_4', '0');

        $scheduleArray = array($schedule_time_1, $schedule_time_2, $schedule_time_3, $schedule_time_4);

        $errorFlag = false;

        if (!empty($api_url) && !filter_var($api_url, FILTER_VALIDATE_URL) === true) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_valid_url_required;
            $errorFlag = true;
        } else if (empty($api_url)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_url_required;
            $errorFlag = true;
        } else if (empty($api_username)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->provide_username;
            $errorFlag = true;
        } else if (empty($api_password)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->provide_password;
            $errorFlag = true;
        } else if (empty($property_id)) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_property_id;
            $errorFlag = true;
        } else if (!empty(property_id) && !filter_var($property_id, FILTER_VALIDATE_INT) === true) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->error_valid_propertyid;
            $errorFlag = true;
        } else if ($schedule_time_1_h == 'NA' || $schedule_time_1_m == 'NA') {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->scheduletime1require;
            $errorFlag = true;
        } else if ($schedule_time_2 == 1 && ($schedule_time_2_h == 'NA' || $schedule_time_2_m == 'NA')) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->scheduletime2require;
            $errorFlag = true;
        } else if ($schedule_time_3 == 1 && ($schedule_time_3_h == 'NA' || $schedule_time_3_m == 'NA')) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->scheduletime3require;
            $errorFlag = true;
        } else if ($schedule_time_4 == 1 && ($schedule_time_4_h == 'NA' || $schedule_time_4_m == 'NA')) {
            $response['error'] = 1;
            $response['message'] = $this->lang->network->scheduletime4require;
            $errorFlag = true;
        } else {
            $errorFlag = false;
        }

        if (!$errorFlag) {

            $api_jsonrequest = array("property_id" => $property_id, "username" => $api_username, "password" => $api_password, "api_url" => $api_url, "api_service" => "entrata");
            $api_jsonrequest = json_encode($api_jsonrequest);

            // check if api credentials is valid or not
            $response = $this->test_api($api_jsonrequest);

            if ($response['error'] == 0) {

                $response['api_data'] = $response['message']['message'];
                $apiData = json_decode($response['message']['message'], true);

                $property_name = trim($apiData['MarketingName']);
                $address = trim($apiData['Address']);
                $property_website = trim($apiData['webSite']);
                $city = trim($apiData['City']);
                $state = trim($apiData['State']);
                $pcode = trim($apiData['PostalCode']);

                $count = $this->api_conn->prepare("SELECT COUNT(*) FROM apiCredential");
                $count->execute();
                $count = $count->fetchColumn();

                $api_password = $this->encrypt_data($api_password);

                $is_insert = 0;
                $is_update = 0;

                if ($count > 0) {
                    // These two lines can possibly be removed. They are redundant. These lines showed up
                    // after a commit with possible merge conflicts.
                    // $sth    = $this->api_conn->prepare("UPDATE apiCredential SET UserName=?,Password=?,URL=?,PropertyID=?,ApiService=?,SkipOverwrite=? WHERE No=?");
                    // $values = array($api_username,$api_password,$api_url,$property_id,$api_service,$ExistData,1);

                    $this->systemLogger("$property_id information updated with $data_overwrite");
                    $sth = $this->api_conn->prepare("UPDATE apiCredential SET UserName=?,Password=?,URL=?,PropertyID=?,ApiService=?, SkipOverwrite=? WHERE No=?");
                    $values = array($api_username, $api_password, $api_url, $property_id, $api_service, $data_overwrite, 1);
                    $prop = $this->api_conn->prepare("UPDATE apiProperty SET MarketingName=?,Address=?,City=?,State=?,PostalCode=?,Website=? WHERE No=?");
                    $prop_values = array($property_name, $address, $city, $state, $pcode, $property_website, 1);
                    $prop->execute($prop_values);
                    $is_update = 1;
                } else {
                    // These two lines can possibly be removed. The insert requires 6 values. These lines showed up
                    // after a commit with possible merge conflicts.
                    // $sth    = $this->api_conn->prepare("INSERT INTO apiCredential (No,UserName,Password,URL,PropertyID,ApiService,SkipOverwrite) VALUES (1,?,?,?,?,?)");
                    // $values = array($api_username,$api_password,$api_url,$property_id,$api_service,$ExistData);

                    $this->systemLogger("$property_id information added with $data_overwrite");
                    $sth = $this->api_conn->prepare("INSERT INTO apiCredential (No,UserName,Password,URL,PropertyID,ApiService,SkipOverwrite) VALUES (1,?,?,?,?,?,?)");
                    $values = array($api_username, $api_password, $api_url, $property_id, $api_service, $data_overwrite);
                    $prop = $this->api_conn->prepare("INSERT INTO apiProperty (MarketingName,Address,City,State,PostalCode,Website) VALUES (?,?,?,?,?,?)");
                    $prop_values = array($property_name, $address, $city, $state, $pcode, $property_website);
                    $prop->execute($prop_values);
                    $is_insert = 1;
                }

                if ($sth->execute($values)) {

                    $cvalue = 1;
                    for ($i = 0; $i < count($scheduleArray); $i++) {

                        if ($scheduleArray[$i] != 0) {
                            $scheduleTime = $this->input::post('schedule_time_' . $cvalue . '_h') . ':' . $this->input::post('schedule_time_' . $cvalue . '_m');
                        } else {
                            $scheduleTime = "24:00";
                        }

                        $scsql = $this->api_conn->prepare("UPDATE apiSchedule SET ScheduleTime=? WHERE No=?");
                        $scsqlvalues = array($scheduleTime, $cvalue);
                        $scsql->execute($scsqlvalues);

                        $cvalue++;
                    }

                    $response['error'] = 0;
                    $response['message'] = $this->lang->common->api_save_completed;

                    if ($is_insert == 1) {
                        $this->log::set_log('Entrata', 'insert');
                    } else if ($is_insert == 0) {
                        $this->log::set_log('Entrata', 'update');
                    }
                } else {
                    $response['error'] = 1;
                    $response['message'] = $this->lang->common->error_update;

                    if ($is_insert == 1) {
                        $this->log::set_log('Entrata', 'insert', $response['message']);
                    } else if ($is_insert == 0) {
                        $this->log::set_log('Entrata', 'update', $response['message']);
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }

    public function force_sync()
    {

        $response = array();
        $errorFlag = false;

        if (!$errorFlag) {

            $apiOutput = exec("python /usr/lib/python2.7/site-packages/entrata/api.py download_and_save", $output, $result);
            if ($result == 0) {
                $response['error'] = 0;
                //print_r($output);exit;
                $output[1] = str_replace("'", '"', $output[1]);
                $api_response = json_decode($output[1], true);
                if (isset($api_response['error']) && $api_response['error'] == 1) {
                    $response['error'] = 1;
                    $response['message'] = $api_response['message'];
                } else {
                    $response['message'] = $api_response['message'];
                }
            } else {
                $response['error'] = 1;
                $response['message'] = "Connection Error";
            }
        }

        echo json_encode($response);
        exit;

    }

    public function delete_api_data()
    {
        //$confirm_pw    = $this->input::post('confirm_pw');
        $response = array();
        //$errorFlag = True;
        $errorFlag = false;

        /*if( $_SESSION['spider_type'] == 'spider' )
        {
        $ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
        $ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
        if( !($ctrl = $ctrl->fetch(\PDO::FETCH_ASSOC)) )
        {
        $response['error'] = 1;
        $response['message'] = $this->lang->user->error_login_pw_mismatch;
        $errorFlag = True;
        }else{
        $errorFlag = False;
        }
        }*/

        if (!$errorFlag) {

            $apiOutput = exec("python /usr/lib/python2.7/site-packages/entrata/api.py delete_all_api_data", $output, $result);
            if ($result == 0) {
                $response['error'] = 0;
                $output[0] = str_replace("'", '"', $output[0]);
                $api_response = json_decode($output[0], true);
                if (isset($api_response['error']) && $api_response['error'] == 1) {
                    $response['error'] = 1;
                    $response['message'] = $api_response['message'];
                    $this->log::set_log('Entrata', 'delete', $api_response['message']);
                } else {
                    $response['message'] = $api_response;
                    $this->log::set_log('Entrata', 'delete');
                }
            } else {
                $response['error'] = 1;
                $response['message'] = "Connection Error";
                $this->log::set_log('Entrata', 'delete', $api_response['message']);
            }

        }

        echo json_encode($response);
        exit;

    }

}
