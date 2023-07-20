<?php
namespace App\Controllers;

use App\Models\CommonModel;

class license extends BaseController
{
    protected $networkModel;
    protected $defaultModel;
    public function __construct()
    {
        $this->defaultModel = new CommonModel('default');
        $this->networkModel = new CommonModel('network');
    }
    function index()
    {
        $dealer   = $this->defaultModel->selectAllRows("ToDoTable1", "*", ['No' => 1]);

        $site   = $this->defaultModel->selectAllRows("ToDoTable1", "*", ['No' => 2]);
		

        $comment   = $this->defaultModel->selectAllRows("ToDoTable1", "*", ['No' => 3]);
		

		$vars = array();
		$vars['dealer'] = $dealer;
		$vars['site'] = $site;
		$vars['comment'] = $comment;

		return view('wizard/license', $vars);
		// if( $this->input::get('wizard') == '1' )	$this->display($vars, '', 'none');
		// else								$this->display($vars, '', 'license');
    }

    // ----------------------------------------------------------------------------------

    // function select()
    // {
    //     $field  = Input::get('f');
    //     $word   = Input::get('w');
    //     $page   = Input::get('p', 1);
    //     $view   = Input::get('v');

    //     $page_config    = $GLOBALS['page_config'];
    //     $pagination     = new Pagination();

    //     if( empty($field) || empty($word) )
    //     {
    //         $count  = $this->net_work->prepare("SELECT COUNT(*) FROM Network");
    //         $count->execute();
    //         $count  = $count->fetchColumn();

    //         $page_config['current_page']    = $page;
    //         $page_config['total_row']       = $count;
    //         $pagination->init($page_config);

    //         $list   = $this->net_work->prepare("SELECT * FROM Network ORDER BY No DESC LIMIT ?, ?");
    //         $list->execute(array($pagination->offset, $pagination->row_size));
    //         $list   = $list->fetchAll(PDO::FETCH_ASSOC);
    //     }
    //     else
    //     {
    //         $count  = $this->net_work->prepare("SELECT COUNT(*) FROM Network WHERE $field LIKE ?");
    //         $count->execute(array("%$word%"));
    //         $count  = $count->fetchColumn();

    //         $page_config['current_page']    = $page;
    //         $page_config['total_row']       = $count;
    //         $pagination->init($page_config);

    //         $list   = $this->net_work->prepare("SELECT * FROM Network WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
    //         $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
    //         $list   = $list->fetchAll(PDO::FETCH_ASSOC);
    //     }
        
    //     foreach( $list as $key=>$val )
    //     {
    //     	$val['VersionStr']  = $this->get_version();
    //         $val['ModelStr']    = EnumTable::$attrModel[$val['Model']];
	// 		//$val['TypeStr']     = EnumTable::$attrDeviceType[$val['Type']];
	// 		$val['TypeStr']     = EnumTable::$attrModelType[$val['Model']][$val['Type']];
	// 		$val['MACAddress']  = $this->get_macaddress();
			
	// 		$list[$key] = $val;
    //     }

    //     $result['field']        = $field;
    //     $result['word']         = $word;
    //     $result['page']         = $page;
    //     $result['view']         = $view;
    //     $result['pages']        = $pagination->get_pages();
    //     $result['count']        = $count;
    //     $result['list']         = $list;

    //     echo json_encode($result);
    // }

    // // ----------------------------------------------------------------------------------
    // function update()
    // {
    //     $No         = Input::post('No');
    //     $LicenseKey = Input::post('LicenseKey');
        
    //     if( empty($LicenseKey) )     Util::alert( $this->lang->license->error_license_key_required, TRUE );
        
    //     $sth    = $this->net_work->prepare("UPDATE License SET Result=0 WHERE No=1");
    //     if( $sth->execute() )
    //     {
    //         Log::set_log(NULL, 'update');
	//         exec(SPIDER_COMM." license ".$LicenseKey);
	        
	//         sleep(3);
	        
	//         $license  = $this->net_work->query("SELECT Result FROM License WHERE No=1");
    //         $license->execute();
    //         if ( $license = $license->fetch(PDO::FETCH_ASSOC) )
    //         {
    //         	if ($license['Result'] == 1)		// Success
    //         	{
    //         		Util::alert($this->lang->license->license_key_success);
    //         		sleep(5);
    //         		Util::redirect('/?c=user&m=logout'); 
    //         	}
    //         	else if ($license['Result'] == 2)	// MAC Address Error
    //         	{
    //         		Util::alert($this->lang->license->license_key_mac_address, TRUE);
    //         	}
    //         	else if ($license['Result'] == 3)	// Unknown Device Type
    //         	{
    //         		Util::alert($this->lang->license->license_unknown_device, TRUE);
    //         	}
    //         	else if ($license['Result'] == 4)	// Cannot Support Downgrade
    //         	{
    //         		Util::alert($this->lang->license->license_cannot_downgrade, TRUE);
    //         	}
    //         	else 								// Change Failure
    //         	{
    //         		Util::alert($this->lang->common->error_update, TRUE);
    //         	}
    //         }
    //     }
    //     else
    //     {
    //         Util::alert($this->lang->common->error_update);
    //     }
    // }
    
    // // ----------------------------------------------------------------------------------
    
    // function get_version()
    // { 
	// 	exec(SPIDER_COMM." ver", $output);
	// 	$version	= @$output[count($output)-1];
	// 	$build = $this->get_build();
	// 	return $version . $build;
	// }

    // function get_build()
    // {
    //     $sw_rel = "";
    //     $sw_rev = "";

    //     $file = fopen("/etc/version", "r");
    //     while (!feof($file)) {
    //         $fline = trim(fgets($file));
    //         $pos = strpos($fline , ":");
    //         if ($pos !== false) {
    //             $field = substr($fline, 0, $pos);
    //             $field_value = substr($fline, $pos+2);
    //             if ($field === "Software Release") {
    //                 $sw_rel = $field_value;
    //             }
    //             if ($field === "Built commit, meta-nxgcpub") {
    //                 $sw_rev = $field_value;
    //             }
    //         }
    //     }
    //     fclose($file);

    //     $build = substr($sw_rev, 0, 10) . "/" . $sw_rel;
    //     if ($build !== "/") {
    //         $build = trim($build, "/");
    //         $build = strrev($build);
    //         $build = trim($build, "/");
    //         $build = strrev($build);
    //         $build = " (" . $build . ")";
    //     } else {
    //         $build = "";
    //     }
    //     return $build;
    // }

	// function get_macaddress()
	// {
	// 	$mac = "";
	// 	exec(SPIDER_COMM." getmac", $output);

	// 	foreach( $output as $line )
	// 	{
	// 		$line = trim($line);
	// 		$name_split = explode('-', $line);

	// 		if( $name_split[0] == 'MAC' ) 
	// 		{
	// 			$mac = $name_split[1];
	// 		}
	// 	}
		
	// 	/*
	// 	$mac  = $this->conn->prepare("SELECT MAC FROM Host WHERE IP = '".$_SERVER['HTTP_HOST']."'");
    //     $mac->execute();
    //     $mac  = $mac->fetchColumn();
    //     */
	// 	return $mac;
	// }

    // // ----------------------------------------------------------------------------------

    // function save()
    // {
	// 	$dealer_company_name	= Input::post('dealer_company_name');
	// 	$dealer_address1		= Input::post('dealer_address1');
	// 	$dealer_address2		= Input::post('dealer_address2');
	// 	$dealer_city			= Input::post('dealer_city');
	// 	$dealer_state			= Input::post('dealer_state');
	// 	$dealer_zip_code		= Input::post('dealer_zip_code');
	// 	$dealer_contact_name	= Input::post('dealer_contact_name');
	// 	$dealer_phone_number	= Input::post('dealer_phone_number');
	// 	$dealer_cell_phone		= Input::post('dealer_cell_phone');
	// 	$dealer_email			= Input::post('dealer_email');

	// 	if( empty($dealer_company_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_address1) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_city) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_state) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_zip_code) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_contact_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_phone_number) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_email) )			Util::alert( $this->lang->license->error_missing_required, TRUE );

	// 	if( Util::GetRecordCount('ToDoTable1', " WHERE No = 1") > 0 ) {
	//         $sth    = $this->conn->prepare("UPDATE ToDoTable1 SET Field1=?,Field2=?,Field3=?,Field4=?,Field5=?,Field6=?,Field7=?,Field8=?,Field9=?,Field10=? WHERE No = 1");
	// 	} else {
	//         $sth    = $this->conn->prepare("INSERT INTO ToDoTable1 (No,Field1,Field2,Field3,Field4,Field5,Field6,Field7,Field8,Field9,Field10) VALUES (1,?,?,?,?,?,?,?,?,?,?)");
	// 	}
    //     $values = array($dealer_company_name,$dealer_address1,$dealer_address2,$dealer_city,$dealer_state,$dealer_zip_code,$dealer_contact_name,$dealer_phone_number,$dealer_cell_phone,$dealer_email);
    //     $sth->execute($values);

	// 	$site_company_name	= Input::post('site_company_name');
	// 	$site_address1		= Input::post('site_address1');
	// 	$site_address2		= Input::post('site_address2');
	// 	$site_city			= Input::post('site_city');
	// 	$site_state			= Input::post('site_state');
	// 	$site_zip_code		= Input::post('site_zip_code');
	// 	$site_contact_name	= Input::post('site_contact_name');
	// 	$site_phone_number	= Input::post('site_phone_number');
	// 	$site_cell_phone	= Input::post('site_cell_phone');
	// 	$site_email			= Input::post('site_email');

	// 	if( Util::GetRecordCount('ToDoTable1', " WHERE No = 2") > 0 ) {
	//         $sth    = $this->conn->prepare("UPDATE ToDoTable1 SET Field1=?,Field2=?,Field3=?,Field4=?,Field5=?,Field6=?,Field7=?,Field8=?,Field9=?,Field10=? WHERE No = 2");
	// 	} else {
	//         $sth    = $this->conn->prepare("INSERT INTO ToDoTable1 (No,Field1,Field2,Field3,Field4,Field5,Field6,Field7,Field8,Field9,Field10) VALUES (2,?,?,?,?,?,?,?,?,?,?)");
	// 	}
    //     $values = array($site_company_name,$site_address1,$site_address2,$site_city,$site_state,$site_zip_code,$site_contact_name,$site_phone_number,$site_cell_phone,$site_email);
	// 	$sth->execute($values);

	// 	if( Util::GetRecordCount('ToDoTable1', " WHERE No = 3") > 0 ) {
	//         $sth    = $this->conn->prepare("UPDATE ToDoTable1 SET Field1=? WHERE No = 3");
	// 	} else {
	//         $sth    = $this->conn->prepare("INSERT INTO ToDoTable1 (No,Field1) VALUES (3,?)");
	// 	}
    //     $values = array(Input::post('register_comment'));
	// 	$sth->execute($values);
	// }

    // // ----------------------------------------------------------------------------------

    // function valid_sendorder()
    // {
	// 	$this->save();

	// 	$dealer_company_name	= Input::post('dealer_company_name');
	// 	$dealer_address1		= Input::post('dealer_address1');
	// 	$dealer_address2		= Input::post('dealer_address2');
	// 	$dealer_city			= Input::post('dealer_city');
	// 	$dealer_state			= Input::post('dealer_state');
	// 	$dealer_zip_code		= Input::post('dealer_zip_code');
	// 	$dealer_contact_name	= Input::post('dealer_contact_name');
	// 	$dealer_phone_number	= Input::post('dealer_phone_number');
	// 	$dealer_cell_phone		= Input::post('dealer_cell_phone');
	// 	$dealer_email			= Input::post('dealer_email');

	// 	if( empty($dealer_company_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_address1) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_city) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_state) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_zip_code) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_contact_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_phone_number) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_email) )			Util::alert( $this->lang->license->error_missing_required, TRUE );

	// 	Util::js('confirm_sendorder();');
	// }

    // // ----------------------------------------------------------------------------------

    // function sendorder()
    // {
	// 	$request_model		= Input::post('request_model');
	// 	$request_type		= Input::post('request_type');
	// 	$request_options	= Input::post('request_options');
	// 	$comment			= str_replace("\n", ' ', Input::post('comment'));
	// 	$comment			= str_replace("'", "''", $comment);

	// 	$current_option		= $this->current_system_option_to_str();
	// 	$request_option		= array();
	// 	if( is_array($request_options) ) {
	// 		foreach($request_options as $key=>$val) {
	// 			$request_option[] = EnumTable::$attrModelOptionIndex[$request_model][$key];
	// 		}
	// 	}
	// 	$request_option		= implode(', ', $request_option);

	// 	$dealer_company_name	= Input::post('dealer_company_name');
	// 	$dealer_address1		= Input::post('dealer_address1');
	// 	$dealer_address2		= Input::post('dealer_address2');
	// 	$dealer_city			= Input::post('dealer_city');
	// 	$dealer_state			= Input::post('dealer_state');
	// 	$dealer_zip_code		= Input::post('dealer_zip_code');
	// 	$dealer_contact_name	= Input::post('dealer_contact_name');
	// 	$dealer_phone_number	= Input::post('dealer_phone_number');
	// 	$dealer_cell_phone		= Input::post('dealer_cell_phone');
	// 	$dealer_email			= Input::post('dealer_email');

	// 	$register_comment		= str_replace("\n", ' ', Input::post('register_comment'));
	// 	$register_comment		= str_replace("'", "''", $register_comment);

	// 	if( empty($dealer_company_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_address1) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_city) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_state) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_zip_code) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_contact_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_phone_number) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_email) )			Util::alert( $this->lang->license->error_missing_required, TRUE );

	// 	$site_company_name	= Input::post('site_company_name');
	// 	$site_address1		= Input::post('site_address1');
	// 	$site_address2		= Input::post('site_address2');
	// 	$site_city			= Input::post('site_city');
	// 	$site_state			= Input::post('site_state');
	// 	$site_zip_code		= Input::post('site_zip_code');
	// 	$site_contact_name	= Input::post('site_contact_name');
	// 	$site_phone_number	= Input::post('site_phone_number');
	// 	$site_cell_phone	= Input::post('site_cell_phone');
	// 	$site_email			= Input::post('site_email');

	// 	$params = "'{$dealer_company_name}' '{$dealer_address1} {$dealer_address2} {$dealer_city} {$dealer_state} {$dealer_zip_code}' '{$dealer_contact_name}' '{$dealer_phone_number}' '{$dealer_cell_phone}' '{$dealer_email}' '{$register_comment}' '{$site_company_name}' '{$site_address1} {$site_address2} {$site_city} {$site_state} {$site_zip_code}' '{$site_contact_name}' '{$site_phone_number}' '{$site_cell_phone}' '{$site_email}'";

	// 	exec(SPIDER_COMM." sendorder '{$current_option}' '{$request_option}' '{$comment}' {$request_model} {$request_type} {$params}");
	// 	//echo(SPIDER_COMM." sendorder '{$current_option}' '{$request_option}' '{$comment}' {$request_model} {$request_type} {$params}");
	// 	//Util::alert($this->lang->license->sendorder_ok);
    // }

    // // ----------------------------------------------------------------------------------

    // function valid_sendregister()
    // {
	// 	$this->save();

	// 	$dealer_company_name	= Input::post('dealer_company_name');
	// 	$dealer_address1		= Input::post('dealer_address1');
	// 	$dealer_address2		= Input::post('dealer_address2');
	// 	$dealer_city			= Input::post('dealer_city');
	// 	$dealer_state			= Input::post('dealer_state');
	// 	$dealer_zip_code		= Input::post('dealer_zip_code');
	// 	$dealer_contact_name	= Input::post('dealer_contact_name');
	// 	$dealer_phone_number	= Input::post('dealer_phone_number');
	// 	$dealer_cell_phone		= Input::post('dealer_cell_phone');
	// 	$dealer_email			= Input::post('dealer_email');

	// 	if( empty($dealer_company_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_address1) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_city) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_state) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_zip_code) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_contact_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_phone_number) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_email) )			Util::alert( $this->lang->license->error_missing_required, TRUE );

	// 	Util::js('confirm_sendregister();');
	// }

    // // ----------------------------------------------------------------------------------

    // function sendregister()
    // {
	// 	$dealer_company_name	= Input::post('dealer_company_name');
	// 	$dealer_address1		= Input::post('dealer_address1');
	// 	$dealer_address2		= Input::post('dealer_address2');
	// 	$dealer_city			= Input::post('dealer_city');
	// 	$dealer_state			= Input::post('dealer_state');
	// 	$dealer_zip_code		= Input::post('dealer_zip_code');
	// 	$dealer_contact_name	= Input::post('dealer_contact_name');
	// 	$dealer_phone_number	= Input::post('dealer_phone_number');
	// 	$dealer_cell_phone		= Input::post('dealer_cell_phone');
	// 	$dealer_email			= Input::post('dealer_email');

	// 	$register_comment		= str_replace("\n", ' ', Input::post('register_comment'));
	// 	$register_comment		= str_replace("'", "''", $register_comment);

	// 	$current_option		= $this->current_system_option_to_str();

	// 	if( empty($dealer_company_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_address1) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_city) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_state) )			Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_zip_code) )		Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_contact_name) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_phone_number) )	Util::alert( $this->lang->license->error_missing_required, TRUE );
	// 	if( empty($dealer_email) )			Util::alert( $this->lang->license->error_missing_required, TRUE );

	// 	$site_company_name	= Input::post('site_company_name');
	// 	$site_address1		= Input::post('site_address1');
	// 	$site_address2		= Input::post('site_address2');
	// 	$site_city			= Input::post('site_city');
	// 	$site_state			= Input::post('site_state');
	// 	$site_zip_code		= Input::post('site_zip_code');
	// 	$site_contact_name	= Input::post('site_contact_name');
	// 	$site_phone_number	= Input::post('site_phone_number');
	// 	$site_cell_phone	= Input::post('site_cell_phone');
	// 	$site_email			= Input::post('site_email');

	// 	$params = "'{$dealer_company_name}' '{$dealer_address1} {$dealer_address2} {$dealer_city} {$dealer_state} {$dealer_zip_code}' '{$dealer_contact_name}' '{$dealer_phone_number}' '{$dealer_cell_phone}' '{$dealer_email}' '{$register_comment}' '{$site_company_name}' '{$site_address1} {$site_address2} {$site_city} {$site_state} {$site_zip_code}' '{$site_contact_name}' '{$site_phone_number}' '{$site_cell_phone}' '{$site_email}'";

	// 	exec(SPIDER_COMM." sendregistration '{$current_option}' {$params}");
	// 	//echo(SPIDER_COMM." sendregistration '{$current_option}' '{$comment}' {$params}");
	// 	//Util::alert($this->lang->license->sendregister_ok);
    // }

    // // ----------------------------------------------------------------------------------

	// function edit_option()
	// {
	// 	$this->display($vars, 'license_edit_option', FALSE);
    // }

    // // ----------------------------------------------------------------------------------

	// function system_option_to_str($items)
	// {
	// 	if( !is_array($items) || empty($items) )	return 'N/A';

	// 	$option = $this->get_system_option();
	// 	//$option = '1111111110010111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111';

	// 	$labels	= array();
	// 	foreach( $items as $idx=>$label ) {
	// 		if( substr($option, ($idx-1), 1) == '1' ) {
	// 			$labels[] = $label;
	// 		}
	// 	}

	// 	return count($labels) < 1 ? 'No' : implode('<br>', $labels);
    // }

    // // ----------------------------------------------------------------------------------

	// function get_system_option()
	// {
	// 	$license  = $this->net_work->query("SELECT optionValue FROM Option WHERE No=1");
	// 	$license->execute();
	// 	$option = $license->fetchColumn();

	// 	return $option;
    // }

    // // ----------------------------------------------------------------------------------

	// function current_system_option_to_str()
	// {
	// 	$labels	= array();
	// 	$options = EnumTable::$attrModelOptionIndex[$_SESSION['spider_model']];

	// 	$option = $this->get_system_option();
	// 	for($i=0; $i<strlen($option); $i++) {
	// 		$char = substr($option, $i, 1);
	// 		if( $char == '1' ) {
	// 			if( empty($options[($i+1)]) ) {
	// 				$labels[] = $options[($i+1)];
	// 			}
	// 		}
	// 	}

	// 	return implode(', ', $labels);
	// }
}
?>