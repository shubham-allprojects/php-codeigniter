<?php
namespace App\Controllers;

class Rmc extends BaseController
{

    public $propertieFile = '/spider/webtunnel/WebTunnelServer.properties';

    public function index()
    {
        $vars = array();
        $vars['connect'] = $this->enumtable::$attrOnOff[($this->checkProcess() ? '0' : '1')];
        $vars['properties'] = $this->readProperties();
        $vars['baseController'] = $this;
        $this->display($vars, 'rmc/rmc', ['header' => 'header', 'footer' => 'footer']);
    }

    public function edit()
    {
        $vars = array();
        $vars['connect'] = $this->checkProcess();
        $vars['properties'] = $this->readProperties();
        $vars['baseController'] = $this;
        $this->display($vars, 'rmc/rmc_edit', ['header' => 'header', 'footer' => 'footer']);
    }

    public function save()
    {
        $connect = $this->input::post('connect', 0);
        $reflectorURI = $this->input::post('reflectorURI', '');
        $domain = $this->input::post('domain', '');
        $deviceId = $this->input::post('deviceId', '');

        if (empty($reflectorURI)) {
            $this->util::alert($this->lang->common->error_reflectorURI_required);
            $this->util::js('parent.parent.hide_loading();');
            exit;
        }

        if (empty($domain)) {
            $this->util::alert($this->lang->common->error_domain_required);
            $this->util::js('parent.parent.hide_loading();');
            exit;
        }

        if (empty($deviceId)) {
            $this->util::alert($this->lang->common->error_deviceId_required);
            $this->util::js('parent.parent.hide_loading();');
            exit;
        }

        $this->stopProcess();
        $this->writeProperties('webtunnel.reflectorURI', $reflectorURI);
        $this->writeProperties('webtunnel.domain', $domain);
        $this->writeProperties('webtunnel.deviceId', $deviceId);

        $sth = $this->conn->prepare("UPDATE NetworkInfo SET DDNSEnable = ? WHERE No = 1");
        $values = array($connect);
        $sth->executeSilent($values);

        if ($connect == '1') {
            $this->startProcess();
        }

        $this->util::js("location.href='/?c=rmc';");
    }

    public function readProperties()
    {
        $txtProperties = file_get_contents($this->propertieFile);
        $result = array();
        $lines = split("\n", $txtProperties);
        $key = "";
        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line) || (!$isWaitingOtherLine && strpos($line, "#") === 0)) {
                continue;
            }

            $key = trim(substr($line, 0, strpos($line, '=')));
            $value = trim(substr($line, strpos($line, '=') + 1, strlen($line)));

            if ($key == 'webtunnel.reflectorURI') {
                if (substr($value, 0, 8) == 'https://') {
                    $value = substr($value, 8);
                } else if (substr($value, 0, 7) == 'http://') {
                    $value = substr($value, 7);
                }
            } else if ($key == 'webtunnel.deviceId') {
                $value = str_replace('-${system.nodeId}', '', $value);
            }

            $result[$key] = $value;
            unset($lines[$i]);
        }

        return $result;
    }

    public function writeProperties($name, $value)
    {
        if ($name == 'webtunnel.reflectorURI') {
            $value = 'https://' . $value;
        } else if ($name == 'webtunnel.deviceId') {
            $value = $value . '-${system.nodeId}';
        }

        $txtProperties = file_get_contents($this->propertieFile);
        $result = array();
        $lines = split("\n", $txtProperties);
        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line) || (!$isWaitingOtherLine && strpos($line, "#") === 0)) {
                $result[] = $line;
                continue;
            }

            $key = trim(substr($line, 0, strpos($line, '=')));

            if ($key == $name) {
                $result[] = "{$key} = {$value}";
            } else {
                $result[] = $line;
            }
        }

        file_put_contents($this->propertieFile, implode("\n", $result));
    }

    public function checkProcess()
    {
        $network_info = $this->conn->prepare("SELECT DDNSEnable FROM NetworkInfo WHERE No = 1");
        $network_info->execute();
        if ($network_info = $network_info->fetch(\PDO::FETCH_ASSOC)) {
            $output = $network_info['DDNSEnable'];
        } else {
            $output = '0';
        }

        return !empty($output);
    }

    public function startProcess()
    {
        exec(SPIDER_COMM . " rmccontrol start");
    }

    public function stopProcess()
    {
        exec(SPIDER_COMM . " rmccontrol stop");
    }

}
