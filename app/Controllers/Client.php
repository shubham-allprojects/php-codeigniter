<?php


class client extends controller
{
    // ----------------------------------------------------------------------------------

    function index()
    {
        $this->systemLogger("Client:index:");
        $this->display($vars, '', 'client');
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
            $count  = $this->net_work->prepare("SELECT COUNT(*) FROM Network");
            $count->execute();
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->net_work->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort FROM Network ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->net_work->prepare("SELECT COUNT(*) FROM Network WHERE $field LIKE ?");
            $count->execute(array("%$word%"));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->net_work->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort FROM Network WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

		$network_info	= $this->network_info();

        foreach( $list as $key=>$val )
        {
			if($val['IPType'] == '0') {
				$val['IPAddress']	= $network_info['addr'];
				$val['Subnet']	= $network_info['mask'];
				$val['Gateway']	= $network_info['bcast'];
			}

            $val['IPTypeStr']   = EnumTable::$attrIPType[$val['IPType']];
            $val['ServerIP']    = $this->get_serverip($val['No']);
            $val['ServerFTP']   = $this->get_serverftpport($val['No']);

            $login   = $this->conn->prepare("SELECT ID, Password FROM Controller WHERE No=?");
            $login->execute(array($val['No']));
            $login   = $login->fetchAll(PDO::FETCH_ASSOC);

            foreach( $login as $logkey=>$logval )
            {
  				$val['ID']           = $logval['ID'];
  				$val['Password']     = $logval['Password'];
	            $val['PrevID']       = $logval['ID'];
   				$val['PasswordStr']  = $logval['Password'];
   				$list[$logkey]       = $logval;
   			}

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

	function network_info()
	{
		exec(SPIDER_COMM.' cmd ifconfig', $lines);

		$output	= implode("\n", $lines);

		$match  = "/^.*addr:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
		$match .= ".*Bcast:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
		$match .= ".*Mask:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}).*$/im";
		if(!preg_match($match, $output, $matches))
			return false;

		$values = array(
			'addr'	=> $matches[1],
			'bcast'	=> $matches[2],
			'mask'	=> $matches[3]
		);

		$lines = '';
		$output = array();
		$matches = array();

		exec(SPIDER_COMM.' cmd route | grep default', $lines);
		$output	= implode("\n", $lines);

		$match  = "/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/im";
		if(preg_match($match, $output, $matches)) {
			$values['bcast']	= $matches[1];
		}

		return $values;
	}

    // ----------------------------------------------------------------------------------
    function update()
    {
        $this->systemLogger("Client:update:");
        $No         = Input::post('No');
        $IPType     = Input::post('IPType', '0');
        $IPAddress  = trim(Input::post('IPAddress'));
        $Subnet     = trim(Input::post('Subnet'));
        $Gateway    = trim(Input::post('Gateway'));
        $DNS1       = trim(Input::post('DNS1'));
        $DNS2       = trim(Input::post('DNS2'));
        $HTTPPort   = Input::post('HTTPPort', '80');
        $ServerIP   = trim(Input::post('ServerIP'));
        $ServerFTP  = trim(Input::post('ServerFTP', '21'));

   		  $ID  		         = Input::post('ID');
        $PrevID          = Input::post('PrevID');
        $Password        = Input::post('Password');
        $PrevPassword    = Input::post('PrevPassword');

		    if( empty($ID) )          			Util::alert( $this->lang->ctrl->error_id_required, TRUE );
        if( empty($Password) )          	Util::alert( $this->lang->ctrl->error_pw_required, TRUE );

		    if( $PrevID != $ID )				Util::alert( $this->lang->ctrl->error_not_matched_id, TRUE );
        if( $PrevPassword != $Password )	Util::alert( $this->lang->ctrl->error_not_matched_pw, TRUE );

        if (!filter_var($ServerIP, FILTER_VALIDATE_IP) === true) {
            Util::alert( $this->lang->client->error_ip_add_required, TRUE );
        }

        if( empty($ServerFTP) )    			Util::alert( $this->lang->client->error_port_required, TRUE );

        if( empty($HTTPPort) )    			$HTTPPort = '80';

        if( $IPType == '1' )
        {
            if (!filter_var($IPAddress, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_ip_add_required, TRUE );
            }

            if (!filter_var($Subnet, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_subnet_required, TRUE );
            }

            if (!filter_var($Gateway, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_gateway_required, TRUE );
            }

            if( !empty($DNS1) ) {

                if (!filter_var($DNS1, FILTER_VALIDATE_IP) === true) {
                    Util::alert( $this->lang->network->error_ip_add_required, TRUE );
                }
            }

            if( !empty($DNS2) ) {
                if (!filter_var($DNS2, FILTER_VALIDATE_IP) === true) {
                    Util::alert( $this->lang->network->error_ip_add_required, TRUE );
                }
            }

            $sth    = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,ServerIP=?,ServerFTPPort=? WHERE No=?");
            $values = array($IPType,$IPAddress,$Subnet,$Gateway,$DNS1,$DNS2,$HTTPPort,$ServerIP,$ServerFTP,$No);
            $this->systemLogger("Client:update:Updated Network IPType($IPType),IPaddress($IPAddress),subnet($Subnet),gateway($Gateway),DNS1($DNS1),DNS2($DNS2),HTTP Port($HTTPPort),Server IP($ServerIP), Server FTP Port($ServerFTP) For No=$No");
        }
        else
        {
            $sth    = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,ServerIP=?,ServerFTPPort=? WHERE No=?");
            $values = array($IPType,'','','',$DNS1,$DNS2,$HTTPPort,$ServerIP,$ServerFTP,$No);
            $this->systemLogger("Client:update:Updated Network IPType($IPType),IP address subnet and gateway will be set by Router,DNS1($DNS1),DNS2($DNS2),HTTP Port($HTTPPort),Server IP($ServerIP), Server FTP Port($ServerFTP) For No=$No");
        }

        if( $sth->execute($values) )
        {
            if( $IPType == '1' )
            {
                $sth    = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
                $values = array($IPType,$IPAddress,$Subnet,$Gateway,$DNS1,$DNS2,$HTTPPort,$No);
            }
            else
            {
                $sth    = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
                $values = array($IPType,'','','',$DNS1,$DNS2,$HTTPPort,$No);
            }
            if( $sth->execute($values) )
            {
              $this->systemLogger("Client:update:Updated Network Successfull");
              $this->systemLogger("Client:update:Not copy database from /tmp to /spider/database");
              //exec("cp -a /tmp/SpiderDB/Network.db /spider/database/Network.db");
                Log::set_log(NULL, 'update');
                //exec (SPIDER_COMM." nconf start");
                exec(SPIDER_COMM." http off {$HTTPPort} 443");
                $this->systemLogger("Client:update:SPIDER_COMM http off {$HTTPPort} 443");
                sleep(5);
                $this->systemLogger("Client:update:Sleep 5");
                Util::alert( $this->lang->common->save_completed_reset );

                exec (SPIDER_COMM." shutdown");                
                $this->systemLogger("Client:update:System shutdown(device reboot)");
                Util::redirect("/?c=client");
            }
            else
            {
                $this->systemLogger("Client:update:Updated Network Failed");
                Util::alert($this->lang->common->error_update);
            }
        }
        else
        {
            $this->systemLogger("Client:update:Updated Network Failed");
            Util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------
    function restart()
    {
    $this->systemLogger("Client: restart:");
		$confirm_pw    = Input::post('confirm_pw');
		$save_type     = Input::post('save_type');

		$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
		$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
		if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
		{
      $this->systemLogger("Client: restart: Webuser/admin password mismatch");
      Util::alert($this->lang->user->error_login_pw_mismatch, TRUE);
		}


    	Log::set_log('client', 'reset');

        //exec("cp -a /tmp/SpiderDB/* /spider/database/.");
        //exec(SPIDER_COMM." sysback");

        //Util::alert($this->lang->common->complete_reset);
        //Util::redirect("/?c=client");
        $this->systemLogger("Client: restart: Restart system");
        exec(SPIDER_COMM." shutdown");
    }

    // ----------------------------------------------------------------------------------
    function get_serverip($No)
    {
        $arr    = array();

        $serverinfo   = $this->net_work->prepare("SELECT ServerIP FROM Network WHERE No=1");
        $serverinfo->execute();
        $serverip     = $serverinfo->fetchColumn();

        return $serverip;
    }

    function get_serverftpport($No)
    {
        $arr    = array();

        $serverinfo   = $this->net_work->prepare("SELECT ServerFTPPort FROM Network WHERE No=1");
        $serverinfo->execute();
        $serverport   = $serverinfo->fetchColumn();

        return $serverport;
    }

    // ----------------------------------------------------------------------------------
    function fdefault()
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

        // ���ɾ� ���� �߰�
        // exec("���ɾ�");
        exec(SPIDER_COMM." factory");

        Util::alert($this->lang->common->success_complete_factory);
        Util::redirect("/?c=client");
    }

}
?>
