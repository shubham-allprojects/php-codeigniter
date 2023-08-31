<?php
namespace App\Controllers;

class Timesvr extends BaseController
{
    public $arr_ip_type = array('0' => 'DHCP', '1' => 'Static');
    public $arr_d_server = array('1' => 'dyndns.org');
    public $arr_plug = array('0' => 'Off', '1' => 'On');
    //var $arr_ntp        = array('0'=>'Manual Time Setting','1'=>'NTP Server Synchronization');
    public $arr_sync_time = array('30' => '30 Minute', '60' => '1 Hour', '120' => '2 Hour', '360' => '6 Hour');

    // ----------------------------------------------------------------------------------

    public function index()
    {
        $vars = ['baseController' => $this];
        $this->display($vars, 'timesvr/timesvr', ['header' => 'css', 'footer' => '']);

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

            $list = $this->conn->prepare("SELECT * FROM NetworkInfo ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $count = $this->conn->prepare("SELECT COUNT(*) FROM NetworkInfo WHERE $field LIKE ?");
            $count->execute(array("%$word%"));
            $count = $count->fetchColumn();

            $page_config['current_page'] = $page;
            $page_config['total_row'] = $count;
            $pagination->init($page_config);

            $list = $this->conn->prepare("SELECT *, DSTEnable FROM NetworkInfo WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        foreach ($list as $key => $val) {
            $val['NTPSyncTime'] = empty($val['NTPSyncTime']) ? '30' : $val['NTPSyncTime'];
            $val['NTPSyncTimeZone'] = empty($val['NTPSyncTimeZone']) ? '-5' : $val['NTPSyncTimeZone'];

            $val['NTPSyncStr'] = $this->enumtable::$attrNTPServer[$val['NTPSync']];
            $val['NTPServerStr'] = $val['NTPServer'];
            $val['NTPSyncTimeStr'] = $this->arr_sync_time[$val['NTPSyncTime']];
            $val['NTPSyncZoneStr'] = $this->enumtable::$attrTimeZone[$val['NTPSyncTimeZone']];
            if (empty($val['DSTEnable'])) {
                $val['DSTEnable'] == '0';
            }
            /*
            $val['DSTStart'] = (empty($val['DSTStart']) ? '' : date("m-d-Y", $val['DSTStart']));
            $val['DSTEnd']   = (empty($val['DSTEnd']) ? '' : date("m-d-Y", $val['DSTEnd']));
            if ($val['DSTEnable'] == "1")
            {
            $val['DSTEnableStr']   = $this->arr_plug[$val['DSTEnable']].' '.$val['DSTStart'].'~'.$val['DSTEnd'];
            } else {
            $val['DSTEnableStr']   = $this->arr_plug[$val['DSTEnable']];
            }
             */
            $val['DSTEnableStr'] = $this->arr_plug[$val['DSTEnable']];

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

    public function update4()
    {
        $No = $this->input::post('No');
        $NTPSync = $this->input::post('NTPSync', '0');
        $NTPServer = $this->input::post('NTPServer');
        $NTPSyncTime = $this->input::post('NTPSyncTime');
        $NTPSyncTimeZone = $this->input::post('NTPSyncTimeZone');
        $DSTEnable = $this->input::post('DSTEnable', '0');
        $TimeZoneSyncServer_check = $this->input::post('timezonesync_check'); //NXG-3809

        //$DSTStart    = $this->input::post('DSTStart');
        //$DSTEnd      = $this->input::post('DSTEnd');
        //$DSTStart    = $this->to_timestamp($DSTStart);
        //$DSTEnd      = $this->to_timestamp($DSTEnd) + 24*60*60 -1;

        if ($DSTEnable == '1') {
            //if( empty($DSTStart) )     $this->util::alert( $this->lang->holiday->error_startdate_empty, TRUE );
            //if( empty($DSTEnd) )       $this->util::alert( $this->lang->holiday->error_enddate_empty, TRUE );
            //if( $DSTStart > $DSTEnd ) $this->util::alert( $this->lang->holiday->error_date_value, TRUE );
        } else {
            //$DSTStart    = '';
            //$DSTEnd      = '';
        }

        $SetDate = $this->input::post('SetDateStr');

        $SetTime = "";
        if ($NTPSync == '1') {
            $IPType = $this->input::post('IPType');
            $DNS1 = $this->input::post('DNS1');
            $DNS2 = $this->input::post('DNS2');

            // Validaton addded for time server text field - 16-08-2021
            if ((empty($NTPServer) || $NTPServer == "")) {
                $this->util::alert($this->lang->addmsg->empty_dns_server, true);
            }

            // By SUN07 - 2012.03.23 : Static �ΰ�� DNS Server �� ��ϵǾ�� Time Server ����� ����� �� ����
            if ($IPType == 1 && (empty($DNS1) || $DNS1 == "") && (empty($DNS1) || $DNS1 == "")) {
                $this->util::alert($this->lang->addmsg->empty_dns_server, true);
            }

            $sth = $this->conn->prepare("UPDATE NetworkInfo SET NTPSync=?,NTPServer=?,NTPSyncTime=?,NTPSyncTimeZone=?,DSTEnable=? WHERE No=?");
            $values = array($NTPSync, $NTPServer, $NTPSyncTime, $NTPSyncTimeZone, $DSTEnable, $No);

            if ($sth->execute($values)) {
                //NXG-3809
                if ($TimeZoneSyncServer_check == 1) {
                    $sth = $this->conn->prepare("UPDATE Controller SET TimeZone=?,  DST=? ");
                } else {
                    $sth = $this->conn->prepare("UPDATE Controller SET TimeZone=?, DST=? WHERE No=1");
                }

                $values = array($NTPSyncTimeZone, $DSTEnable);
                $sth->execute($values);
                //NXG-3809

                if ($sth->execute($values)) {
                    exec(SPIDER_COMM . " time set", $result);

                    foreach ($result as $line) {
                        $line = strtolower(trim($line));
                        $action = explode(':', $line);
                        $status = trim(@$action[0]);

                        if (strpos($status, 'result') === 0) {
                            if ($action[1] == '0') {
                                //exec(SPIDER_COMM." send db");
                                $this->log::set_log(null, 'update');
                                $this->util::js('update_list("' . $No . '");');
                                $this->util::alert($this->lang->common->save_completed);
                            } else {
                                $this->util::alert($this->lang->common->fail_NTP);
                            }

                        }
                    }

                } else {
                    $this->util::alert($this->lang->common->error_update);
                }
            } else {
                $this->util::alert($this->lang->common->error_update);
            }
        } else {
            $SetTimeStr = $this->input::post('SetTimeStr');
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $SetTimeStr)) {
                $this->util::alert($this->lang->addmsg->error_time_invalid_format, true);
            }

            list($SetDateHour, $SetDateMin, $SetDateSec) = @explode(':', $SetTimeStr, 3);

            //if( empty($SetDate) )
            //    $this->util::alert( $this->lang->network->error_date_required, TRUE );
            if ($SetDateHour > 23 || $SetDateHour < 0 || $SetDateHour > 59 || $SetDateSec < 0 || $SetDateSec > 59) {
                $this->util::alert($this->lang->network->error_time_required, true);
            }

            $sth = $this->conn->prepare("UPDATE NetworkInfo SET NTPSync=?,NTPServer='',NTPSyncTime='',NTPSyncTimeZone=?,DSTEnable=? WHERE No=?");
            $values = array($NTPSync, $NTPSyncTimeZone, $DSTEnable, $No);

            if ($sth->execute($values)) {
                //NXG-3809
                if ($TimeZoneSyncServer_check == 1) {
                    $sth = $this->conn->prepare("UPDATE Controller SET TimeZone=?,  DST=? ");
                } else {
                    $sth = $this->conn->prepare("UPDATE Controller SET TimeZone=?, DST=? WHERE No=1");
                }

                $values = array($NTPSyncTimeZone, $DSTEnable);
                $sth->execute($values);
                //NXG-3809

                if (!empty($SetDate)) {
                    $timeval = explode('-', trim($SetDate));

                    if (count($timeval) >= 3) {

                        $month = $timeval[0];
                        $day = $timeval[1];
                        $year = $timeval[2];
                        //$SetTime = $month.$day.$SetDateHour.$SetDateMin.$year.".".$SetDateSec;
                        $SetTime = sprintf('%02d%02d%02d%02d%04d.%02d', $month, $day, $SetDateHour, $SetDateMin, $year, $SetDateSec);
                        exec(SPIDER_COMM . " time set " . $SetTime);
                        $this->log::set_log(null, 'update');
                    } else {
                        $this->util::alert($this->lang->addmsg->incorrect_date_format, true);
                    }
                }

                $this->util::js('update_list("' . $No . '");');
                $this->util::alert($this->lang->common->save_completed);
            } else {
                $this->util::alert($this->lang->common->error_update);
            }
        }
    }

    // ----------------------------------------------------------------------------------

    public function to_timestamp($date = "", $hour = 0, $min = 0, $sec = 0)
    {
        if ($date != "") {
            $timeval = explode('-', trim($date));

            if (count($timeval) < 3) {
                return "";
            }

            $month = $timeval[0];
            $day = $timeval[1];
            $year = $timeval[2];
            return mktime($hour, $min, $sec, $month, $day, $year);
        }
        return $date;
    }

    // ----------------------------------------------------------------------------------

}
