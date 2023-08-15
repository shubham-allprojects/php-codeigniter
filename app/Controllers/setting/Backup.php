<?php


class backup extends controller
{
	var $arr_plug       = array('0'=>'Off', '1'=>'On');
	var $arr_device     = array('0'=>'SD Card', '1'=>'FTP');

    // ----------------------------------------------------------------------------------
    function index()
    {
        $this->display();
    }

    // ----------------------------------------------------------------------------------
    function select()
    {
    	$field  = Input::get('f');
        $word   = Input::get('w');
        $page   = Input::get('p', 1);
        $view   = Input::get('v');

        $page_config    = $GLOBALS['page_config'];
        $pagination     = new Pagination();

        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM BackupSchedule WHERE No=1");
            $count->execute();
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM BackupSchedule WHERE No=1 ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM BackupSchedule WHEREE No=1 AND $field LIKE ?");
            $count->execute(array("%$word%"));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM BackupSchedule WHERE No=1 AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach( $list as $key=>$val )
        {
        		$val['BackupName']    = $val['Name'];
            $val['EnableStr']     = $this->arr_plug[$val['Enable']];
            $val['DeviceStr']     = $this->arr_device[$val['Device']];
            if ($val['DeviceStr'] == "")
            	$val['DeviceStr'] = "SD Card";
            $val['BackupTimeStr'] = EnumTable::$attrTimeList[$val['BackupTime']];
            $list[$key] = $val;
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['page']         = $page;
        $result['view']         = $view;
        $result['pages']        = $pagination->get_pages();
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }

	// ----------------------------------------------------------------------------------
	function update()
    {
    	$Name        = strip_tags(Input::post('Name'));
    	$Enable      = Input::post('Enable', '0');
        $DeviceType  = Input::post('DeviceType', '0');
        $BackupTime  = Input::post('BackupTime');

        $sth     = $this->conn->prepare("UPDATE BackupSchedule SET Name=?,Enable=?,BackupTime=?,Device=? WHERE No=1");
        $values  = array($Name, $Enable, $BackupTime, $DeviceType);

        if( $sth->execute($values) )
        {
            Log::set_log(NULL, 'update');
            Util::alert( $this->lang->common->save_completed );
            Util::redirect("/?c=backup");
        }
        else
        {
            Util::alert($this->lang->common->error_update);
        }
    }

    function _exec()
    {
		$confirm_pw    = Input::post('confirm_pw');

		if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch);
        Util::redirect("/?c=backup");
				exit;
			}
		}
		else
		{
			$webuser    = $this->conn->query("SELECT * FROM WebUser WHERE ID = ? AND Password = ? AND Site = ?");
			$webuser->execute(array($_SESSION['spider_id'], $confirm_pw, $_SESSION['spider_site']));
			if( !($webuser = $webuser->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch);
        Util::redirect("/?c=backup");
				exit;
			}
		}

    $sel    = Input::post('sel');
		$this->systemLogger("controller:backup:_exec:sel=$sel");

		$rt  = "0";
		$msg = $this->lang->common->success_complete_backup;
                $password = base64_decode(SECRET_PASSWORD);
        if( $sel == 'pc' )
        {
                $timestamp=date("YmdHis");
                $filenameONLY="bak_$timestamp";
                $filename="bak_$timestamp.enc";
                $this->systemLogger("controller:backup:_exec:sel=pc:filename=$filename");
                $output = false;
                $backup_op = exec(SPIDER_COMM." backup pc $filenameONLY", $output);
                if($backup_op == '0'){
                    $filepath = '/tmp/backup-tmp/' . $filename;
                    $this->systemLogger("controller:backup:_exec:sel=exec output=$output");

                    header("Content-Description: File Transfer");
                    header("Content-Type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=\"".$filename."\"");
                    header("Content-Transfer-Encoding: Binary");
                    header("Pragma: no-cache; public");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Content-Length: " . filesize($filepath));
                    header("Expires: 0");

                    readfile($filepath);
                    unlink($filepath);
                }else{
                    $msg = $this->lang->common->fail_complete_backup_pc;
                    Util::alert($msg);
                    Util::redirect("/?c=backup");
                }

                Log::set_log();

        }
        elseif( $sel == 'sd' )
        {
            $rt = "0";
            exec(SPIDER_COMM." mnt sd");
            $output = false;
            $rt = exec(SPIDER_COMM." backup sd", $output);
            if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_backup_sd;

	    if ($rt == "0"){
                Log::set_log();
            }
	    else
                Log::set_log('backup', '_execfail');

            Util::alert($msg);
            Util::redirect("/?c=backup");
        }
        elseif( $sel == 'ftp' )
        {
            $rt = exec(SPIDER_COMM." backup ftp", $output);
            if ($rt == "-1")
            	$msg = $this->lang->common->fail_ftp_disable;
            else if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_backup_ftp;

 	    if ($rt == "0"){
                Log::set_log();
            }
	    else
		Log::set_log('backup', '_execfail');

            Util::alert($msg);
            Util::redirect("/?c=backup");
        }
    }

    // ----------------------------------------------------------------------------------
/*
    function u_backup()
    {
        $sel = $_POST['sel'];

//      Util::alert($sel);


        if($sel == 1)
        {
            $file = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];

            $file_path = '/spider/sicupdate.tar.gz';

             echo ("<META http-equiv='refresh' content='10; url=/'>");
        }
        else if($sel == 2)
        {
            exec (SPIDER_COMM." mnt sd");
            exec (SPIDER_COMM." backup sd sicu_backup.tar.gz");
        }
        else
        {
            exec ("/spider/sicu/acsbackup -up ftp");
        }
    }

//  function topc(){
//      exec ("/spider/sicu/acsbackup -b web");
//      $tt =  date("Ymd");
//      $result['file'] = "sicubackup-".$tt.".tar.gz";
//      echo json_encode($result);
//  }
//  function tosd(){
//      exec ("/spider/sicu/acsbackup -b sd");
//      $tt =  date("Ymd");
//      $result['file'] = "sicubackup-".$tt.".tar.gz";
//      echo json_encode($result);
//  }
//  function toftp(){
//      exec ("/spider/sicu/acsbackup -b ftp");
//      $tt =  date("Ymd");
//      $result['file'] = "sicubackup-".$tt.".tar.gz";
//      echo json_encode($result);
//  }
*/
}
