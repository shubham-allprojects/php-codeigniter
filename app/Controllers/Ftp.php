<?php
namespace App\Controllers;

class Ftp extends BaseController
{

    public $arr_ip_type = array('0' => 'DHCP', '1' => 'Static');
    public $arr_d_server = array('1' => 'dyndns.org');
    public $arr_plug = array('0' => 'Off', '1' => 'On');

    // ----------------------------------------------------------------------------------

    public function index()
    {   $vars = ['baseController'=> $this ];
        $this->display($vars, 'ftp/ftp', ['header' => 'css', 'footer' => '']);
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

            $list = $this->conn->prepare("SELECT * FROM NetworkInfo WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        foreach ($list as $key => $val) {
            $val['IPTypeStr'] = $this->arr_ip_type[$val['IPType']];
            $val['DDNSEnableStr'] = $this->arr_plug[$val['DDNSEnable']];
            $val['DDNSServerStr'] = $this->arr_d_server[$val['DDNSServer']];
            $val['FTPEnableStr'] = $this->arr_plug[$val['FTPEnable']];
            $val['FTPPassiveStr'] = $this->arr_plug[$val['FTPPassive']];
            $val['SMSEnableStr'] = $this->arr_plug[$val['SMSEnable']];
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

    public function update3()
    {
        $No = $this->input::post('No');
        $FTPEnable = $this->input::post('FTPEnable', '0');
        $FTPAddress = $this->input::post('FTPAddress');
        $FTPPort = $this->input::post('FTPPort', '21');
        $FTPID = $this->input::post('FTPID');
        $FTPPassword = $this->input::post('FTPPassword');
        $FTPPassive = $this->input::post('FTPPassive', '0');
        $FTPDir = $this->input::post('FTPDir');

        $mode = $this->input::post('mode');
        if ($mode == "save") {
            if ($FTPEnable == '1') {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET FTPEnable=?,FTPAddress=?,FTPPort=?,FTPID=?,FTPPassword=?,FTPPassive=?,FTPDir=? WHERE No=?");
                $values = array($FTPEnable, $FTPAddress, $FTPPort, $FTPID, $FTPPassword, $FTPPassive, $FTPDir, $No);
            } else {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET FTPEnable=? WHERE No=?");
                $values = array($FTPEnable, $No);
            }

            if ($sth->execute($values)) {
                $this->log::set_log(null, 'update');
                $this->util::js('update_list("' . $No . '");');
                $this->util::alert($this->lang->common->save_completed);
            } else {
                $this->util::alert($this->lang->common->error_update);
            }
        } else {
            $rt = exec(SPIDER_COMM . " ftptest $FTPAddress $FTPPort $FTPID $FTPPassword $FTPPassive $FTPDir");
            $msg = $this->lang->common->success_complete_ftptest;
            if ($rt == "-1") {
                $msg = $this->lang->common->fail_ftp_disable;
            } else if ($rt != "0") {
                $msg = $this->lang->common->fail_complete_ftptest;
            }

            $this->util::alert($msg);
        }
    }

    // ----------------------------------------------------------------------------------
}
