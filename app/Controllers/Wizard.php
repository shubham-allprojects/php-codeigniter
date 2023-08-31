<?php
namespace App\Controllers;

use App\Models\CommonModel;

class Wizard extends BaseController
{
    protected $networkModel;
    protected $defaultModel;
    public function __construct()
    {
        $this->defaultModel = new CommonModel('default');
        $this->networkModel = new CommonModel('network');
    }

    public function wizard()
    {
        $this->display([], 'wizard/index', ['header' => '', 'footer' => '']);
    }
    public function wizard_language()
    {
        $list = $this->conn->prepare("SELECT Country, Language FROM Site WHERE No=1");
        $list->execute();
        $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        $data = [];
        foreach ($list as $val) {
            $data['country'] = $val['Country'];
            $data['language'] = $val['Language'];
        }

        $this->display($data, 'wizard/language', ['header' => 'css', 'footer' => '']);
    }

    public function language_insert()
    {
        $country = $this->input::post('country');
        $language = $this->input::post('language');

        $site = $this->conn->prepare("SELECT Country, Language FROM Site WHERE No=1");
        $site->execute();
        $site = $site->fetch(\PDO::FETCH_ASSOC);
        $sth = $this->conn->prepare("UPDATE Site SET Country=?, Language=? WHERE No=?");

        if (!$sth) {
            $this->util::alert(lang('Message.Common.error_insert'), true);
        }

        $sth = $this->conn->prepare("UPDATE Controller SET Language=? WHERE No=?");

        if (!$sth) {
            $this->util::alert(lang('Message.Common.error_insert'), true);
            $this->systemLogger("Wizard Feature: Language($language) and Country($country) update FAIL");
        }

        $this->systemLogger("Wizard Feature: Language($language) and Country($country) update");

        if ($site['Country'] == $country) {
            $this->util::js('parent.document.getElementById("wizard_body").contentWindow.restore_holiday("' . $country . '")', true);
        }
    }

    public function restore_holiday()
    {
        $Country = $this->input::get('country');

        $holiday = $this->conn->prepare("DELETE FROM Holiday WHERE Site=?");
        $holiday->execute(array($_SESSION['spider_site']));

        $InsertNo = $this->conn->prepare("SELECT MAX(No) FROM Holiday");
        $InsertNo->execute();
        $InsertNo = $InsertNo->fetchColumn() + 1;

        $holiday = $this->conn->prepare("INSERT INTO Holiday (Site,No,Name,StartTime,EndTime,Holiday1,Holiday2,Holiday3,Holiday4) VALUES (?,?,?,?,?,?,?,?,?)");
        $path = WRITEPATH . '/holiday/holiday-' . $this->enumtable::$attrCountryCode[$Country] . '.csv';
        $handle = @fopen($path, "r");
        if ($handle) {
            while (($buffer = fgetcsv($handle)) !== false) {
                $start_time = $this->to_timestamp_ymd($buffer[0]);
                $end_time = $this->to_timestamp_ymd($buffer[1], 23, 59, 59);
                $name = $buffer[2];

                $values = array($_SESSION['spider_site'], $InsertNo, $name, $start_time, $end_time, '0', '0', '0', '0');
                $holiday->execute($values);
                $InsertNo++;
            }
            fclose($handle);
        }

        $this->util::js('alert("' . $this->lang->addmsg->restore_holiday_ok . '");');
    }

    // ----------------------------------------------------------------------------------

    public function dealer()
    {
        $dealer = $this->conn->prepare("SELECT * FROM ToDoTable1 WHERE No = 1");
        $dealer->execute();
        $dealer = $dealer->fetch(\PDO::FETCH_ASSOC);

        $site = $this->conn->prepare("SELECT * FROM ToDoTable1 WHERE No = 2");
        $site->execute();
        $site = $site->fetch(\PDO::FETCH_ASSOC);

        $vars = array();
        $vars['dealer'] = $dealer;
        $vars['site'] = $site;

        $this->display($vars, 'wizard/dealer', 'none');
    }

    public function restart()
    {
        $this->display([], 'wizard/restart', 'none');
    }

    // ----------------------------------------------------------------------------------

    public function restart_exe()
    {
        $this->systemLogger("Wizard Feature: Start Save(restart_exe())");
        $backup_sd = $this->input::post('backup_sd');
        $default_page = $this->input::post('default_page');

        if ($_SESSION['spider_type'] == 'spider') {
            $sth = $this->conn->prepare("UPDATE Controller SET DefaultFloorNo=?, DefaultPage=? WHERE No=1");
            $values = array($default_page, $default_page);
            $sth->execute($values);
        } else {
            $sth = $this->conn->prepare("UPDATE WebUser SET DefaultPage=? WHERE Site=? AND No=?");
            $values = array($default_page, $_SESSION['spider_site'], $_SESSION['spider_userno']);
            $sth->execute($values);
        }
        $this->systemLogger("Wizard Feature: Start Save: updated default floor($default_page) and default page($default_page)");
        if ($backup_sd == '1') {
            exec(SPIDER_COMM . " mnt sd");
            $rt = exec(SPIDER_COMM . " backup sd");
            if ($rt != "0") {
                $this->util::alert($this->lang->common->fail_complete_backup_sd);
            }

            if ($rt == "0") {
                $this->log::set_log('backup', '_exec');
            } else {
                $this->log::set_log('backup', '_execfail');
            }

            $this->systemLogger("Wizard Feature: Start Save: mount sd card and take backup in sd card");
        }
        $this->systemLogger("Wizard Feature: Start Save: Not copy database from /tmp/ to /spider/");
        //exec("cp -a /tmp/SpiderDB/* /spider/database/.");
        exec(SPIDER_COMM . " sysback");
        $this->util::js('top.location.href = "/";');
        //exec(SPIDER_COMM." clnt reset all");
        //exec(SPIDER_COMM." shutdown");
    }

    public function check_data()
    {
        echo '$(".wizard-menu.wizard1").addClass("wizard-menu-checked");';

        $count = $this->net_work->prepare("SELECT COUNT(*) FROM Network WHERE LicenseKey != ''");
        $count->execute();
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard2").addClass("wizard-menu-checked");';
        }

        $count = $this->conn->prepare("SELECT COUNT(*) FROM CardFormat");
        $count->execute();
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard3").addClass("wizard-menu-checked");';
        }

        $count = $this->conn->prepare("SELECT COUNT(*) FROM Holiday WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard4").addClass("wizard-menu-checked");';
        }

        $count = $this->conn->prepare("SELECT COUNT(*) FROM Schedule WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard5").addClass("wizard-menu-checked");';
        }

        $count = $this->conn->prepare("SELECT COUNT(*) FROM AccessLevel WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard7").addClass("wizard-menu-checked");';
        }

        echo '$(".wizard-menu.wizard6").addClass("wizard-menu-checked");';

        $count = $this->conn->prepare("SELECT COUNT(*) FROM User WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard8").addClass("wizard-menu-checked");';
        }

        $count = $this->conn->prepare("SELECT COUNT(*) FROM Card WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard9").addClass("wizard-menu-checked");';
        }

        echo '$(".wizard-menu.wizard10").addClass("wizard-menu-checked");';

        $count = $this->conn->prepare("SELECT COUNT(*) FROM ToDoTable1 WHERE No=1");
        $count->execute();
        $count = $count->fetchColumn();
        if ($count > 0) {
            echo '$(".wizard-menu.wizard11").addClass("wizard-menu-checked");';
        }

    }

    public function update_server_time($ctime)
    {
        //$ctime = $this->input::get('ctime');

        if (!empty($ctime)) {
            $arr = explode(',', $ctime);

            $SetTime = sprintf("%02d%02d%02d%02d%04d.%02d", $arr[1], $arr[2], $arr[3], $arr[4], $arr[0], $arr[5]);
            exec(SPIDER_COMM . " time set " . $SetTime);
        }

        $this->util::js('try{$.modal.close();} catch (e){}');
    }

    public function to_timestamp_ymd($date = "", $hour = 0, $min = 0, $sec = 0)
    {
        if ($date != "") {
            $timeval = explode('-', trim($date));

            if (count($timeval) < 3) {
                return "";
            }

            $year = $timeval[0];
            $month = $timeval[1];
            $day = $timeval[2];
            return mktime($hour, $min, $sec, $month, $day, $year);
        }
        return $date;
    }
}
