<?php 


include APP_DIR.'/libraries/Uploader.php';


class update extends controller
{
    // ----------------------------------------------------------------------------------

    function index()
    {
        $vars['VersionStr']  = $this->get_version();
        $vars['LastVersionStr']  = $this->get_last_version();
        $this->display($vars);
    }

    // ----------------------------------------------------------------------------------
    
    function get_version()
    {
		exec(SPIDER_COMM." ver", $output);

		$version	= @$output[count($output)-1];
        
		return $version;
	}

    // ----------------------------------------------------------------------------------
    
    function get_last_version()
    {
		exec(SPIDER_COMM." update info", $output);

		$version	= @$output[count($output)-1];
        
		return $version;
	}
	
    // ----------------------------------------------------------------------------------

    function _exec()
    {
        set_time_limit(0);
        
		$confirm_pw    = Input::post('confirm_pw');
		
		if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch);
				Util::redirect("/?c=update");
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
				Util::redirect("/?c=update");
				exit;
			}
		}

 		$sel    = Input::post('sel');
	
		$rt  = "0";
		$msg = $this->lang->common->complete_update;
		
        if( $sel == 'pc' )
        {
        	$msg = $this->lang->common->fail_complete_update_pc_file;
            $file = $this->receive();
            if( $file === FALSE )
            {
                Util::redirect("/?c=update");
                exit;
            }
            $rt = exec(SPIDER_COMM." update pc $file");
            
            if ($rt == "0")
            	$msg = $this->lang->common->complete_update;
        }
        elseif( $sel == 'sd' )
        {
            exec(SPIDER_COMM." mnt sd");
            $rt = exec(SPIDER_COMM." update sd");
            
            if ($rt == "-2")
            	$msg = $this->lang->common->fail_complete_update_sd_file;
            else if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_update_sd;
        }
        elseif( $sel == 'ftp' )
        {
            $rt = exec(SPIDER_COMM." update ftp");
            
            if ($rt == "-1")
            	$msg = $this->lang->common->fail_ftp_disable;
            else if ($rt != "0")
            	$msg = $this->lang->common->fail_complete_update_ftp;
        }
        else
        {
        	$msg = $this->lang->common->fail_upload_update_pc_file;
        }
        
		if ($rt == "0")
		{
			//Util::redirect("/");
			//echo("<script>console.log('PageChange Step1');</script>");
			//echo "<meta http-equiv=/'refresh/' content=/'3/' url=$url>";
			//echo("<script>console.log('PageChange Step2');</script>");
			//Util::redirect('/?c=user&m=logout');
			
            Util::alert($msg);
            Util::redirect('/?c=user&m=logout');
		}
		else
		{
            Util::alert($msg);
			Log::set_log("update", "_execfail");
            Util::redirect("/?c=update");
		}
    }

    // ----------------------------------------------------------------------------------

    function receive()
    {
        if( ! is_uploaded_file($_FILES['upload_file']['tmp_name']) )    
        {
        	Util::alert($this->lang->addmsg->error_upload);			//Util::alert("Update File Loading Error." );
        	return FALSE;
        }

        $uploader                   = new Uploader();
        $uploader->upload_dir       = TMPDATA_URL;
        $uploader->allowed_types    = 'tar|TAR|Tar|gz|GZ|Gz';

        if( $uploader->do_upload('upload_file', 100000000) )
        {
            return $uploader->save_file['basename'];
        }
        else
        {
            Util::alert("Update File Loading Error : ".$uploader->get_errors() );
            return FALSE;
        }
    }

    // ----------------------------------------------------------------------------------

/*
    function up_date()
    {       
        $sel = $_POST['sel'];
        
        if($sel == 1)
        {               
            $file = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];
            
            $file_path = '/spider/sicupdate.tar.gz';            
             echo ("<META http-equiv='refresh' content='10; url=/'>");
        }else if($sel == 2){
            exec (SPIDER_COMM." mnt sd");
            exec (SPIDER_COMM." update sd sicu_update.tar.gz");
        }else{
            exec ("/spider/sicu/acsbackup -up ftp");
        }
    }
*/
}
