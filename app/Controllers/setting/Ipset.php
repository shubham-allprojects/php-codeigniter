<?php
class ipset extends controller
{

    var $arr_ip_type    = array('0'=>'DHCP', '1'=>'Static');
    var $arr_d_server   = array('1'=>'dyndns.org');
    var $arr_plug       = array('0'=>'Off', '1'=>'On');

    // ----------------------------------------------------------------------------------

    function index()
    {
		if( Input::get('wizard') == '1' )	$this->display($vars, '', 'none');
		else								$this->display($vars);
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
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM NetworkInfo");
            $count->execute();
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort, HTTPS, HTTPSPORT FROM NetworkInfo ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM NetworkInfo WHERE $field LIKE ?");
            $count->execute(array("%$word%"));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort, HTTPS, HTTPSPORT FROM NetworkInfo WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
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

            $val['IPTypeStr']       = $this->arr_ip_type[$val['IPType']];
            $val['HTTPSStr']        = ($val['HTTPS'] == '' ? 'Off' : $this->arr_plug[$val['HTTPS']]);
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
    function update1()
    {
		$confirm_pw    = Input::post('confirm_pw');

		if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
				Util::alert($this->lang->user->error_login_pw_mismatch);
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
				exit;
			}
		}

        $No         = Input::post('No');
        $IPType     = Input::post('IPType', '0');
        $IPAddress  = trim(Input::post('IPAddress'));
        $Subnet     = trim(Input::post('Subnet'));
        $Gateway    = trim(Input::post('Gateway'));
        $DNS1       = trim(Input::post('DNS1'));
        $DNS2       = trim(Input::post('DNS2'));
        $HTTPPort   = Input::post('HTTPPort', '80');
        $HTTPS      = Input::post('HTTPS');
        $HTTPSPort  = Input::post('HTTPSPORT', '443');

		if(empty($HTTPPort)) {
			$HTTPPort = '80';
		}

		if(empty($HTTPS)) {
			$HTTPS = '0';
		}

		if(empty($HTTPSPort)) {
			$HTTPSPort = '443';
		}

        if( $HTTPPort == '20000' || $HTTPPort == '20001' || $HTTPSPort == '20000' || $HTTPSPort == '20001' ){
            Util::alert( $this->lang->network->error_port_allowed, TRUE );
        }
            
        
        if( $IPType == '1' )
        {
            
            //2015.06.02 by Zeno 
            if (!filter_var($IPAddress, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_ip_add_required, TRUE );
            }
            
            if (!filter_var($Subnet, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_subnet_required, TRUE );
            }
            
            if( trim($Subnet) == '255.255.255.255' )
            {
				Util::alert( $this->lang->network->error_subnet_invalid, TRUE );
            }                       
            
            if (!filter_var($Gateway, FILTER_VALIDATE_IP) === true) {
                Util::alert( $this->lang->network->error_gateway_required, TRUE );
            }
            
            if(!empty($DNS1)) {
                if (!filter_var($DNS1, FILTER_VALIDATE_IP) === true) {
                    Util::alert( $this->lang->network->error_ip_add_required, TRUE );
                }
            }
            
            if(!empty($DNS2)) {
                if (!filter_var($DNS2, FILTER_VALIDATE_IP) === true) {
                    Util::alert( $this->lang->network->error_ip_add_required, TRUE );
                }
            }
            /*
			if(!$this->check_ip($IPAddress))     
				                        Util::alert( $this->lang->network->error_ip_add_required, TRUE );
            if( empty($Subnet) )        Util::alert( $this->lang->network->error_subnet_required, TRUE );
            if( trim($Subnet) == '255.255.255.255' )
										Util::alert( $this->lang->network->error_subnet_invalid, TRUE );
            if( empty($Gateway) )       Util::alert( $this->lang->network->error_gateway_required, TRUE );
            */
            
            $sth    = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
            $values = array($IPType,$IPAddress,$Subnet,$Gateway,$DNS1,$DNS2,$HTTPPort,$No);
        }
        else
        {
            $sth    = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
            $values = array($IPType,'','','',$DNS1,$DNS2,$HTTPPort,$No);
        }

        if( $sth->execute($values) )
        {
            if( $IPType == '1' )
            {
                $sth    = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,HTTPS=?,HTTPSPORT=? WHERE No=?");
                $values = array($IPType,$IPAddress,$Subnet,$Gateway,$DNS1,$DNS2,$HTTPPort,$HTTPS,$HTTPSPort,$No);
            }
            else
            {
                $sth    = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,HTTPS=?,HTTPSPORT=? WHERE No=?");
                $values = array($IPType,'','','',$DNS1,$DNS2,$HTTPPort,$HTTPS,$HTTPSPort,$No);
            }
            if( $sth->execute($values) )
            {
				$HTTPSStr = strtolower($HTTPS == '' ? 'off' : $this->arr_plug[$HTTPS]);
				exec(SPIDER_COMM." http {$HTTPSStr} {$HTTPPort} {$HTTPSPort}");

                Log::set_log(NULL, 'update');
                //exec("cp -a /tmp/SpiderDB/* /spider/database/.");
                exec(SPIDER_COMM." sysback");
                
                Util::alert($this->lang->common->reboot_system);
				flush();

                //exec (SPIDER_COMM." nconf start");
                //exec ("killall spider-ipfind");
                //exec ("/spider/sicu/spider-ipfind &");
                
                //Util::redirect('/?c=user&m=logout');

                exec(SPIDER_COMM." shutdown");
            }
            else
            {
                Util::alert($this->lang->common->error_update);
            }
        }
        else
        {
            Util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    function save_cert()
    {
        $confirm_pw    = Input::post('confirm_pw');

        if( $_SESSION['spider_type'] == 'spider' )
		{
			$ctrl   = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
			$ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
			if( !($ctrl = $ctrl->fetch(PDO::FETCH_ASSOC)) )
			{
                Util::alert($this->lang->user->error_login_pw_mismatch);
                Util::js('parent.hide_loading();');
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
                Util::js('parent.hide_loading();');
				exit;
			}
		}
        
        $privatekey         = Input::post('privatekey');
        $certificate        = Input::post('certificate');
        //$provide_password   = Input::post('provide_password');

        if( empty($privatekey) )        
        {
            Util::alert( $this->lang->network->error_privatekey_required);
            Util::js('parent.hide_loading();');
            exit;
        }
        if( empty($certificate) )       
        {
            Util::alert( $this->lang->network->error_certificate_required);
            Util::js('parent.hide_loading();');
            exit;
        }
 
        //2015.11.02 by Zeno #1973 - SSL ToolBox에서 새로운 Cert Key를 입력하고 Save하면 HTTPS 체크란에 자동으로 등록되도록
        $sth  = $this->conn->prepare("UPDATE NetworkInfo SET HTTPS=1 WHERE No=1");
        $sth->execute();
        
        
        $CertInfo = $this->conn->prepare("SELECT HTTPPORT, HTTPSPORT FROM NetworkInfo WHERE No=1");
        $CertInfo->execute();
        $CertInfo = $CertInfo->fetchAll(PDO::FETCH_ASSOC);
        
        $HTTPPort   = $CertInfo['HTTPPORT'];
        $HTTPSPort  = $CertInfo['HTTPSPORT'];
      
      
        if(empty($HTTPPort)) {
			$HTTPPort = '80';
		}

		if(empty($HTTPSPort)) {
			$HTTPSPort = '443';
        }
        
        exec(SPIDER_COMM." http on {$HTTPPort} {$HTTPSPort}");
       
        //----------------------------------
        
        $filename = uniqid('sicunet.') . '.crt';
        file_put_contents( "/usr/local/lighttpd/cert/{$filename}", $privatekey . "\n" . $certificate );
        exec(SPIDER_COMM.' cert ' . $filename);
        
        Util::alert($this->lang->common->save_completed_reset);
        Util::js('parent.hide_loading();');
        Util::js('self.Opener = self;');
        Util::js('window.close();');
    }
    
    // ----------------------------------------------------------------------------------

    function cert()
    {
        //-----BEGIN CERTIFICATE-----
        $content = file_get_contents('/usr/local/lighttpd/cert/sicunet.crt');

        $pos = strpos($content, '-----BEGIN CERTIFICATE-----');

        $vars['privatekey']  = substr($content, 0, $pos);
        $vars['certificate'] = substr($content, $pos);

		$this->display($vars, 'ipset_cert', 'none');
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
			//'bcast'	=> $matches[2],
			'mask'	=> $matches[3]
		);

		$lines = '';
		$output = array();
		$matches = array();

		exec(SPIDER_COMM.' getroute', $lines);
		
		foreach( $lines as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$values['bcast']	= trim($action[1]);
				break;
			}
		}

		return $values;
	}

    // ----------------------------------------------------------------------------------

	function check_ip($ip) 
	{
		$values = true;
		$match = "/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";
		
	    if(empty($ip)) 
		{
			$values = false;
		}
		else if(!preg_match($match, $ip))
		{
			$values = false;
		}
		
		return $values;
	}
}
?>