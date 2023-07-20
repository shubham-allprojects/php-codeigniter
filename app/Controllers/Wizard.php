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
        return view('wizard/index');
    }
    public function wizard_language()
    {
        $list = $this->defaultModel->selectAllRows("Site", "Country, Language", ['No' => 1]);
        $data = [];
        foreach ($list as $val) {
            $data['country'] = $val['Country'];
            $data['language'] = $val['Language'];
        }
        return view('wizard/language', $data);
    }

    public function language_insert()
    {
        $country = $this->input::post('country');
        $language = $this->input::post('language');

        $site = $this->defaultModel->selectSingleRow("Site", "Country, Language", ['No' => 1]);
        $sth = $this->defaultModel->updateRow("Site", ['Country' => $country, 'Language' => $language], ['No' => 1]);
        if (!$sth) {
            $this->util::alert(lang('Message.Common.error_insert'), true);
        }
        $sth = $this->defaultModel->updateRow("Controller", ['Language' => $language], ['No' => 1]);

        if (!$sth) {
            $this->util::alert(lang('Message.Common.error_insert'), true);
            $this->systemLogger("Wizard Feature: Language($language) and Country($country) update FAIL");
        }

        $this->systemLogger("Wizard Feature: Language($language) and Country($country) update");

        if ($site['Country'] == $country) {
            $this->util::js('parent.document.getElementById("wizard_body").contentWindow.restore_holiday("' . $country . '")', TRUE);
        }
    }

    function restore_holiday()
    {
        $Country = $this->input::get('country');

        $holiday = $this->defaultModel->deleteRow('Holiday', ['site' => $_SESSION['spider_site']]);

        $InsertNo = $this->defaultModel->query("SELECT MAX(No) as max_no FROM Holiday")->getRowArray();
        $InsertNo = $InsertNo['max_no'] + 1;

        $path = WRITEPATH . '/holiday/holiday-' . EnumTable::$attrCountryCode[$Country] . '.csv';
        $handle = @fopen($path, "r");
        if ($handle) {
            while (($buffer = fgetcsv($handle)) !== false) {
                $start_time = $this->to_timestamp_ymd($buffer[0]);
                $end_time = $this->to_timestamp_ymd($buffer[1], 23, 59, 59);
                $name = $buffer[2];

                $values = [
                    'Site' => $_SESSION['spider_site'],
                    'No' => $InsertNo,
                    'Name' => $name,
                    'StartTime' => $start_time,
                    'EndTime' => $end_time,
                    'Holiday1' => '0',
                    'Holiday2' => '0',
                    'Holiday3' => '0',
                    'Holiday4' => '0',
                ];
                $this->defaultModel->insertRow('Holiday', $values);
                $InsertNo++;
            }
            fclose($handle);
        }
        $this->util::js('alert("' . lang('Message.addmsg.restore_holiday_ok') . '");');
    }

    // ----------------------------------------------------------------------------------

    public function dealer()
    {
        $dealer = $this->defaultModel->selectSingleRow("ToDoTable1", "*", ['No' => 1]);
        $site = $this->defaultModel->selectSingleRow("ToDoTable1", "*", ['No' => 2]);

        $vars = array();
        $vars['dealer'] = $dealer;
        $vars['site'] = $site;

        return view('wizard_dealer', $vars);
    }

    public function restart()
    {
        //return view('wizard_restart', $vars);
    }

    // ----------------------------------------------------------------------------------

    public function restart_exe()
    {
        $this->systemLogger("Wizard Feature: Start Save(restart_exe())");
        $backup_sd = $this->input::post('backup_sd');
        $default_page = $this->input::post('default_page');

        if ($_SESSION['spider_type'] == 'spider') {
            $sth = $this->defaultModel->updateRow("Controller", ['DefaultFloorNo' => $default_page, 'DefaultPage' => $default_page], ['No' => 1]);

        } else {

            $sth = $this->defaultModel->updateRow("WebUser", ['DefaultFloorNo' => $default_page], ['Site' => $_SESSION['spider_site'], 'No' => $_SESSION['spider_userno']]);

        }
        $this->systemLogger("Wizard Feature: Start Save: updated default floor($default_page) and default page($default_page)");
        if ($backup_sd == '1') {
            exec(SPIDER_COMM . " mnt sd");
            $rt = exec(SPIDER_COMM . " backup sd");
            if ($rt != "0")
                $this->util::alert( lang('Message.Common.fail_complete_backup_sd'));
            if ($rt == "0")
                $this->log::set_log('backup', '_exec');
            else
                $this->log::set_log('backup', '_execfail');
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

        $count = $this->networkModel->selectRowsCount('Network', ['LicenseKey !=' => '']);
        if ($count > 0)
            echo '$(".wizard-menu.wizard2").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('CardFormat', []);
        if ($count > 0)
            echo '$(".wizard-menu.wizard3").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('Holiday', ['Site' => session()->get('spider_site')]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard4").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('Schedule', ['Site' => session()->get('spider_site')]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard5").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('AccessLevel', ['Site' => session()->get('spider_site')]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard7").addClass("wizard-menu-checked");';

        echo '$(".wizard-menu.wizard6").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('User', ['Site' => session()->get('spider_site')]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard8").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('Card', ['Site' => session()->get('spider_site')]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard9").addClass("wizard-menu-checked");';

        echo '$(".wizard-menu.wizard10").addClass("wizard-menu-checked");';

        $count = $this->defaultModel->selectRowsCount('ToDoTable1', ['No' => 1]);
        if ($count > 0)
            echo '$(".wizard-menu.wizard11").addClass("wizard-menu-checked");';
    }

    public function update_server_time( $ctime)
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