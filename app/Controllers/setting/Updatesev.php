<?php


class updatesev extends controller
{

    var $arr_ip_type    = array('0'=>'DHCP', '1'=>'Static');
    var $arr_d_server   = array('1'=>'dyndns.org');
    var $arr_plug       = array('0'=>'Off', '1'=>'On');

    // ----------------------------------------------------------------------------------

    function index()
    {
        $this->display();
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

            $list   = $this->conn->prepare("SELECT * FROM NetworkInfo ORDER BY No DESC LIMIT ?, ?");
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

            $list   = $this->conn->prepare("SELECT * FROM NetworkInfo WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array("%$word%", $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach( $list as $key=>$val )
        {
            $val['UpdateEnableStr']  = $this->arr_plug[$val['UpdateEnable']];
            $val['UpdatePassiveStr'] = $this->arr_plug[$val['UpdatePassive']];
            $list[$key] = $val;
        }

        $result['field']  = $field;
        $result['word'] = $word;
        $result['page'] = $page;
        $result['view'] = $view;
        $result['pages']    = $pagination->get_pages();
        $result['count']    = $count;
        $result['list']     = $list;

        echo json_encode($result);
    }

    // ----------------------------------------------------------------------------------

    function update3()
    {
        $No             = Input::post('No');
        $UpdateEnable   = Input::post('UpdateEnable', '0');
        $UpdateAddress  = Input::post('UpdateAddress');
        $UpdatePort     = Input::post('UpdatePort', '21');
        $UpdateID       = Input::post('UpdateID');
        $UpdatePassword = Input::post('UpdatePassword');
        $UpdatePassive  = Input::post('UpdatePassive', '0');
        $UpdateDir      = Input::post('UpdateDir');

        if( $UpdateEnable == '1' )
        {
            $sth    = $this->conn->prepare("UPDATE NetworkInfo SET UpdateEnable=?,UpdateAddress=?,UpdatePort=?,UpdateID=?,UpdatePassword=?,UpdatePassive=?,UpdateDir=? WHERE No=?");
            $values = array($UpdateEnable,$UpdateAddress,$UpdatePort,$UpdateID,$UpdatePassword,$UpdatePassive,$UpdateDir,$No);
        }
        else
        {
            $sth    = $this->conn->prepare("UPDATE NetworkInfo SET UpdateEnable=? WHERE No=?");
            $values = array($UpdateEnable,$No);
        }

        if( $sth->execute($values) )
        {
            Log::set_log(NULL, 'update');
            Util::js('update_list("'.$No.'");');
            Util::alert( $this->lang->common->save_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

}


?>