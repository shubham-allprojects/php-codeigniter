<?php
class reset extends controller
{

    // ----------------------------------------------------------------------------------

    function index()
    {
        $this->display($vars);
    }

    function _exe()
    {
		$confirm_pw    = Input::post('confirm_pw');

		if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
        $this->systemLogger("Save Data and Reboot Feature admin user password mismatch");
        Util::alert($this->lang->user->error_login_pw_mismatch, TRUE);
        Util::redirect("/?c=reset");
			}
		}
		else
		{
			$webuser    = $this->conn->query("SELECT * FROM WebUser WHERE ID = ? AND Password = ? AND Site = ?");
			$webuser->execute(array($_SESSION['spider_id'], $confirm_pw, $_SESSION['spider_site']));
			if( !($webuser = $webuser->fetch(PDO::FETCH_ASSOC)) )
			{
        $this->systemLogger("Save Data and Reboot Feature password mismatch for web user");
        Util::alert($this->lang->user->error_login_pw_mismatch, TRUE);
        Util::redirect("/?c=reset");
			}
		}


        $save_type     = Input::post('save_type');
        $this->systemLogger("Selected option 'save data' or 'save data and reboot' option=$save_type");
        // 명령어 실행 추가
        // exec("명령어");
        $this->systemLogger("Not copy database from tmp to spider");
        //exec("cp -a /tmp/SpiderDB/* /spider/database/.");
        exec(SPIDER_COMM." sysback");

        $rebootready = false;
		if( $save_type  == 'reboot' )
		{
			Log::set_log('reset', 'reset');
      $this->systemLogger("Reset system and Shutting down system");
      exec(SPIDER_COMM." shutdown");
      Util::alert($this->lang->common->save_completed_reset);
      Util::redirect('/?c=user&m=logout');
      //$rebootready = true;
		}
		else
		{
      Log::set_log('backup', '_exec');
      $this->systemLogger("Only taking log backup not rebooting system","web");
      Util::alert($this->lang->common->save_completed);
      Util::redirect("/?c=reset");
		}
    /*
        if( $rebootready == true ){
            exec(SPIDER_COMM." shutdown");
        }
        */

        //Util::redirect("/?c=reset");
    }
}
