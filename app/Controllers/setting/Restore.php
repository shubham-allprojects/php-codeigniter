<?php


include APP_DIR.'/libraries/Uploader.php';


class restore extends controller
{

    // ----------------------------------------------------------------------------------

    function index()
    {
        $this->display($vars);
    }

    // ----------------------------------------------------------------------------------

	function confirm_pw()
	{
		$confirm_pw    = Input::post('confirm_pw');

		if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch, TRUE);
			}
		}
		else
		{
			$webuser    = $this->conn->query("SELECT * FROM WebUser WHERE ID = ? AND Password = ? AND Site = ?");
			$webuser->execute(array($_SESSION['spider_id'], $confirm_pw, $_SESSION['spider_site']));
			if( !($webuser = $webuser->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch, TRUE);
			}
		}
		Util::js("show_loading(); $('#form_edit').submit();");
	}

    // ----------------------------------------------------------------------------------

    function _exec()
    {
        set_time_limit(0);
        $sel = Input::post('sel');
        $rt  = "0";
        $msg = $this->lang->common->success_complete_restore;
        $close_connection = $this->close_pdo_connection();

        if( $sel == 'pc' )
        {
            $this->systemLogger("Controller:restore:_exec:sel=pc- now going to receive");
            $file = $this->receive();
            $this->systemLogger("Controller:restore:_exec:file=$file");

            if( $file === FALSE )
            {
                $this->systemLogger("Controller:restore:_exec:file == FALSE");
                Util::alert($this->lang->common->fail_complete_restore_pc_file);
                Util::redirect("/?c=restore");
                exit;
            }
            $restore_op = exec(SPIDER_COMM." restore pc '$file'", $output);
            if($restore_op == '0'){
                $this->systemLogger("Controller:restore:_exec:resultFile result=0");
                $rt = 0;
                $this->systemLogger("Controller:restore:_exec:resultFile rt value changed to 0");
            }else{
                $rt = $restore_op;
                $this->systemLogger("Controller:restore:_exec:resultFile rt value changed to -2");
            }

            if ($rt == "-2")
            	$msg = $this->lang->common->fail_complete_restore_pc_file;
            elseif ($rt != "0")
            	$msg = $this->lang->common->fail_complete_restore_pc;


        }
        elseif( $sel == 'sd' )
        {
            $file_name    = Input::post('file_name');
            $output = false;
            exec(SPIDER_COMM." mnt sd");
            $rt = exec(SPIDER_COMM." restore sd $file_name", $output);

            if ($rt == "-2")
            	$msg = $this->lang->common->fail_complete_restore_sd_file;
            else if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_restore_sd;
        }
        elseif( $sel == 'ftp' )
        {
            $rt = exec(SPIDER_COMM." restore ftp", $output);
            if ($rt == "-1")
            	$msg = $this->lang->common->fail_ftp_disable;
            else if ($rt == "-2")
                $msg = $this->lang->common->fail_complete_restore_ftpsetting;
            else if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_restore_ftp;
        }

        Util::alert($msg);

        @unlink(TMPDATA_URL.'/'.$file);
        $open_connection = $this->open_pdo_connection();

        if ($rt == "0")
        {
            Log::set_log();
            exec(SPIDER_COMM." shutdown");
            Util::redirect("/?c=user&m=logout");
        }
        else
        {
            Log::set_log('restore', '_execfail');
            Util::redirect("/?c=restore");
            exit;
        }
    }

    // ----------------------------------------------------------------------------------

    function receive()
    {
        if( ! is_uploaded_file($_FILES['upload_file']['tmp_name']) )
        {
          $this->systemLogger("controller:restore:receive:is_upload_files failed");
          return FALSE;
        }

        $uploader                   = new Uploader();
        $uploader->upload_dir       = TMPDATA_URL;
        //$uploader->allowed_types    = 'tar|TAR|gz';
        $uploader->allowed_types    = 'enc';
		    $uploader->file_name		= 'restore';

        //if( $uploader->do_upload('upload_file', 40960000) )
        if( $uploader->do_upload('upload_file', (1000 * 1024 * 15)) )
        {
            $this->systemLogger("controller:restore:receive:uploader-do_upload Pass");
            return $uploader->save_file['basename'];
        }
        else
        {
             $this->systemLogger("controller:restore:receive:uploader-do_upload Failed");
             Util::alert( $uploader->get_errors() );
             Util::alert($this->lang->addmsg->error_backup_file);
             return FALSE;
        }
    }

    // ----------------------------------------------------------------------------------

    function file_list()
    {
		$files = array();
		$output = array();

		exec(SPIDER_COMM." sd_list 0", $output);

		foreach( $output as $line )
		{
			$line = trim($line);
			$name_split = explode('.', $line);

			if( $name_split[(count($name_split) - 1)] == 'enc' ) {
				$files[] = $line;
			}
		}

		echo json_encode($files);
    }

    // ----------------------------------------------------------------------------------

}
