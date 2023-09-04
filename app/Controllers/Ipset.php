<?php
namespace App\Controllers;

class Ipset extends BaseController
{

    public $arr_ip_type = array('0' => 'DHCP', '1' => 'Static');
    public $arr_d_server = array('1' => 'dyndns.org');
    public $arr_plug = array('0' => 'Off', '1' => 'On');

    // ----------------------------------------------------------------------------------

    public function index()
    {
        $vars = ['baseController' => $this];
        $this->display($vars, 'ipset/ipset', ['header' => 'header', 'footer' => 'footer']);

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
            $count = $this->conn->prepare("SELECT COUNT(*) FROM NetworkInfo");
            $count->execute();
            $count = $count->fetchColumn();

            $page_config['current_page'] = $page;
            $page_config['total_row'] = $count;
            $pagination->init($page_config);

            $list = $this->conn->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort, HTTPS, HTTPSPORT FROM NetworkInfo ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $count = $this->conn->prepare("SELECT COUNT(*) FROM NetworkInfo WHERE $field LIKE ?");
            $count->execute(array("%$word%"));
            $count = $count->fetchColumn();

            $page_config['current_page'] = $page;
            $page_config['total_row'] = $count;
            $pagination->init($page_config);

            $list = $this->conn->prepare("SELECT No, IFNULL(IPType, 0) AS IPType, IPAddress, Subnet, Gateway, DNS1, DNS2, HTTPPort, HTTPS, HTTPSPORT FROM NetworkInfo WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        $network_info = $this->network_info();

        foreach ($list as $key => $val) {
            if ($val['IPType'] == '0') {
                $val['IPAddress'] = $network_info['addr'];
                $val['Subnet'] = $network_info['mask'];
                $val['Gateway'] = $network_info['bcast'];
            }

            $val['IPTypeStr'] = $this->arr_ip_type[$val['IPType']];
            $val['HTTPSStr'] = ($val['HTTPS'] == '' ? 'Off' : $this->arr_plug[$val['HTTPS']]);
            $list[$key] = $val;
        }

        $result['field'] = $field;
        $result['word'] = $word;
        $result['page'] = $page;
        $result['view'] = $view;
        $result['pages'] = $pagination->get_pages();
        $result['count'] = $count;
        $result['list'] = $list;

        echo json_encode($result);
    }

    // ----------------------------------------------------------------------------------
    public function update1()
    {
        $confirm_pw = $this->input::post('confirm_pw');

        if ($_SESSION['spider_type'] == 'spider') {
            $ctrl = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
            $ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
            if (!($ctrl = $ctrl->fetch(\PDO::FETCH_ASSOC))) {
                $this->util::alert($this->lang->user->error_login_pw_mismatch);
                exit;
            }
        } else {
            $webuser = $this->conn->query("SELECT * FROM WebUser WHERE ID = ? AND Password = ? AND Site = ?");
            $webuser->execute(array($_SESSION['spider_id'], $confirm_pw, $_SESSION['spider_site']));
            if (!($webuser = $webuser->fetch(\PDO::FETCH_ASSOC))) {
                $this->util::alert($this->lang->user->error_login_pw_mismatch);
                exit;
            }
        }

        $No = $this->input::post('No');
        $IPType = $this->input::post('IPType', '0');
        $IPAddress = trim($this->input::post('IPAddress'));
        $Subnet = trim($this->input::post('Subnet'));
        $Gateway = trim($this->input::post('Gateway'));
        $DNS1 = trim($this->input::post('DNS1'));
        $DNS2 = trim($this->input::post('DNS2'));
        $HTTPPort = $this->input::post('HTTPPort', '80');
        $HTTPS = $this->input::post('HTTPS');
        $HTTPSPort = $this->input::post('HTTPSPORT', '443');

        if (empty($HTTPPort)) {
            $HTTPPort = '80';
        }

        if (empty($HTTPS)) {
            $HTTPS = '0';
        }

        if (empty($HTTPSPort)) {
            $HTTPSPort = '443';
        }

        if ($HTTPPort == '20000' || $HTTPPort == '20001' || $HTTPSPort == '20000' || $HTTPSPort == '20001') {
            $this->util::alert($this->lang->network->error_port_allowed, true);
        }

        if ($IPType == '1') {

            //2015.06.02 by Zeno
            if (!filter_var($IPAddress, FILTER_VALIDATE_IP) === true) {
                $this->util::alert($this->lang->network->error_ip_add_required, true);
            }

            if (!filter_var($Subnet, FILTER_VALIDATE_IP) === true) {
                $this->util::alert($this->lang->network->error_subnet_required, true);
            }

            if (trim($Subnet) == '255.255.255.255') {
                $this->util::alert($this->lang->network->error_subnet_invalid, true);
            }

            if (!filter_var($Gateway, FILTER_VALIDATE_IP) === true) {
                $this->util::alert($this->lang->network->error_gateway_required, true);
            }

            if (!empty($DNS1)) {
                if (!filter_var($DNS1, FILTER_VALIDATE_IP) === true) {
                    $this->util::alert($this->lang->network->error_ip_add_required, true);
                }
            }

            if (!empty($DNS2)) {
                if (!filter_var($DNS2, FILTER_VALIDATE_IP) === true) {
                    $this->util::alert($this->lang->network->error_ip_add_required, true);
                }
            }
            /*
            if(!$this->check_ip($IPAddress))
            $this->util::alert( $this->lang->network->error_ip_add_required, TRUE );
            if( empty($Subnet) )        $this->util::alert( $this->lang->network->error_subnet_required, TRUE );
            if( trim($Subnet) == '255.255.255.255' )
            $this->util::alert( $this->lang->network->error_subnet_invalid, TRUE );
            if( empty($Gateway) )       $this->util::alert( $this->lang->network->error_gateway_required, TRUE );
             */

            $sth = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
            $values = array($IPType, $IPAddress, $Subnet, $Gateway, $DNS1, $DNS2, $HTTPPort, $No);
        } else {
            $sth = $this->net_work->prepare("UPDATE Network SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=? WHERE No=?");
            $values = array($IPType, '', '', '', $DNS1, $DNS2, $HTTPPort, $No);
        }

        if ($sth->execute($values)) {
            if ($IPType == '1') {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,HTTPS=?,HTTPSPORT=? WHERE No=?");
                $values = array($IPType, $IPAddress, $Subnet, $Gateway, $DNS1, $DNS2, $HTTPPort, $HTTPS, $HTTPSPort, $No);
            } else {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET IPType=?,IPAddress=?,Subnet=?,Gateway=?,DNS1=?,DNS2=?,HTTPPort=?,HTTPS=?,HTTPSPORT=? WHERE No=?");
                $values = array($IPType, '', '', '', $DNS1, $DNS2, $HTTPPort, $HTTPS, $HTTPSPort, $No);
            }
            if ($sth->execute($values)) {
                $HTTPSStr = strtolower($HTTPS == '' ? 'off' : $this->arr_plug[$HTTPS]);
                exec(SPIDER_COMM . " http {$HTTPSStr} {$HTTPPort} {$HTTPSPort}");

                $this->log::set_log(null, 'update');
                //exec("cp -a /tmp/SpiderDB/* /spider/database/.");
                exec(SPIDER_COMM . " sysback");

                $this->util::alert($this->lang->common->reboot_system);
                flush();

                //exec (SPIDER_COMM." nconf start");
                //exec ("killall spider-ipfind");
                //exec ("/spider/sicu/spider-ipfind &");

                //$this->util::redirect('/?c=user&m=logout');

                exec(SPIDER_COMM . " shutdown");
            } else {
                $this->util::alert($this->lang->common->error_update);
            }
        } else {
            $this->util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    public function save_cert()
    {
        $confirm_pw = $this->input::post('confirm_pw');

        if ($_SESSION['spider_type'] == 'spider') {
            $ctrl = $this->conn->query("SELECT * FROM Controller WHERE ID = ? AND Password = ? AND No = 1");
            $ctrl->execute(array($_SESSION['spider_id'], $confirm_pw));
            if (!($ctrl = $ctrl->fetch(\PDO::FETCH_ASSOC))) {
                $this->util::alert($this->lang->user->error_login_pw_mismatch);
                $this->util::js('parent.hide_loading();');
                exit;
            }
        } else {
            $webuser = $this->conn->query("SELECT * FROM WebUser WHERE ID = ? AND Password = ? AND Site = ?");
            $webuser->execute(array($_SESSION['spider_id'], $confirm_pw, $_SESSION['spider_site']));
            if (!($webuser = $webuser->fetch(\PDO::FETCH_ASSOC))) {
                $this->util::alert($this->lang->user->error_login_pw_mismatch);
                $this->util::js('parent.hide_loading();');
                exit;
            }
        }

        $privatekey = $this->input::post('privatekey');
        $certificate = $this->input::post('certificate');
        //$provide_password   = $this->input::post('provide_password');

        if (empty($privatekey)) {
            $this->util::alert($this->lang->network->error_privatekey_required);
            $this->util::js('parent.hide_loading();');
            exit;
        }
        if (empty($certificate)) {
            $this->util::alert($this->lang->network->error_certificate_required);
            $this->util::js('parent.hide_loading();');
            exit;
        }

        //2015.11.02 by Zeno #1973 - SSL ToolBox에서 새로운 Cert Key를 입력하고 Save하면 HTTPS 체크란에 자동으로 등록되도록
        $sth = $this->conn->prepare("UPDATE NetworkInfo SET HTTPS=1 WHERE No=1");
        $sth->execute();

        $CertInfo = $this->conn->prepare("SELECT HTTPPORT, HTTPSPORT FROM NetworkInfo WHERE No=1");
        $CertInfo->execute();
        $CertInfo = $CertInfo->fetchAll(\PDO::FETCH_ASSOC);

        $HTTPPort = $CertInfo['HTTPPORT'];
        $HTTPSPort = $CertInfo['HTTPSPORT'];

        if (empty($HTTPPort)) {
            $HTTPPort = '80';
        }

        if (empty($HTTPSPort)) {
            $HTTPSPort = '443';
        }

        exec(SPIDER_COMM . " http on {$HTTPPort} {$HTTPSPort}");

        //----------------------------------

        $filename = uniqid('sicunet.') . '.crt';
        file_put_contents("/usr/local/lighttpd/cert/{$filename}", $privatekey . "\n" . $certificate);
        exec(SPIDER_COMM . ' cert ' . $filename);

        $this->util::alert($this->lang->common->save_completed_reset);
        $this->util::js('parent.hide_loading();');
        $this->util::js('self.Opener = self;');
        $this->util::js('window.close();');
    }

    // ----------------------------------------------------------------------------------

    public function cert()
    {
        //-----BEGIN CERTIFICATE-----
        $content = file_get_contents('/usr/local/lighttpd/cert/sicunet.crt');

        $pos = strpos($content, '-----BEGIN CERTIFICATE-----');

        $vars['privatekey'] = substr($content, 0, $pos);
        $vars['certificate'] = substr($content, $pos);

        // $this->display($vars, 'ipset_cert', 'none');
        $this->display($vars, 'ipset/ipset_cert', ['header' => 'header', 'footer' => 'footer']);
    }

    // ----------------------------------------------------------------------------------

    public function network_info()
    {
        exec(SPIDER_COMM . ' cmd ifconfig', $lines);
        $output = implode("\n", $lines);

        $match = "/^.*addr:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
        $match .= ".*Bcast:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
        $match .= ".*Mask:([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}).*$/im";
        if (!preg_match($match, $output, $matches)) {
            return false;
        }

        $values = array(
            'addr' => $matches[1],
            //'bcast'    => $matches[2],
            'mask' => $matches[3],
        );

        $lines = '';
        $output = array();
        $matches = array();

        exec(SPIDER_COMM . ' getroute', $lines);

        foreach ($lines as $line) {
            $line = strtolower(trim($line));
            $action = explode(':', $line);
            $status = trim(@$action[0]);

            if (strpos($status, 'result') === 0) {
                $values['bcast'] = trim($action[1]);
                break;
            }
        }

        return $values;
    }

    // ----------------------------------------------------------------------------------

    public function check_ip($ip)
    {
        $values = true;
        $match = "/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";

        if (empty($ip)) {
            $values = false;
        } else if (!preg_match($match, $ip)) {
            $values = false;
        }

        return $values;
    }
}
