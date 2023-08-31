<?php
namespace App\Controllers;

class Smtp extends BaseController
{

    public $arr_ip_type = array('0' => 'DHCP', '1' => 'Static');
    public $arr_d_server = array('1' => 'dyndns.org');
    public $arr_plug = array('0' => 'Off', '1' => 'On');

    // ----------------------------------------------------------------------------------

    public function index()
    {
        $vars = ['baseController' => $this];
        $this->display($vars, 'smtp/smtp', ['header' => 'css', 'footer' => '']);
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
            $val['SMTPEnableStr'] = $this->arr_plug[$val['SMTPEnable']];
            $val['SMTPTTLStr'] = $this->enumtable::$attrSMTPTLS[$val['SMTPTTL']];
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
        $SMTPEnable = $this->input::post('SMTPEnable', '0');
        $SMTPServer = strip_tags($this->input::post('SMTPServer'));
        $SMTPID = $this->input::post('SMTPID');
        $SMTPPassword = $this->input::post('SMTPPassword');
        $SMTPSendTo = strip_tags($this->input::post('SMTPSendTo'));
        $SMTPTTL = $this->input::post('SMTPTTL', '0');
        $SMTPNumber = $this->input::post('SMTPNumber', '587');

        if (empty($SMTPNumber)) {
            $SMTPNumber = '587';
        }

        $mode = $this->input::post('mode');
        if ($mode == "save") {
            if ($SMTPEnable == '1') {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET SMTPEnable=?,SMTPServer=?,SMTPID=?,SMTPPassword=?,SMTPSendTo=?,SMTPTTL=?,SMTPNumber=? WHERE No=?");
                $values = array($SMTPEnable, $SMTPServer, $SMTPID, $SMTPPassword, $SMTPSendTo, $SMTPTTL, $SMTPNumber, $No);
            } else {
                $sth = $this->conn->prepare("UPDATE NetworkInfo SET SMTPEnable=? WHERE No=?");
                $values = array($SMTPEnable, $No);

            }

            if ($sth->execute($values)) {
                $this->log::set_log(null, 'update');

                exec(SPIDER_COMM . " smtp save");
                //exec(SPIDER_COMM." send db");
                $this->util::js('update_list("' . $No . '");');
                $this->util::alert($this->lang->common->save_completed);
            } else {
                $this->util::alert($this->lang->common->error_update);
            }
        } else {
            if ($SMTPEnable == '1') {
                exec(SPIDER_COMM . " smtp test $SMTPServer $SMTPID $SMTPPassword $SMTPSendTo $SMTPTTL $SMTPNumber ");
                $this->util::alert($this->lang->addmsg->send_test_mail);
            }
        }
    }

    // ----------------------------------------------------------------------------------

}
