<?php 
class fdefault extends controller
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

        // 명령어 실행 추가
        exec(SPIDER_COMM." factory");

        //Util::alert($this->lang->common->success_complete_factory);
        Util::redirect("/?c=user&m=logout");
    }
}