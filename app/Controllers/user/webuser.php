<?php


class webuser extends controller
{
    // ----------------------------------------------------------------------------------
    public $attrUserRole;
    
    function index()
    {
        $this->attrUserRole = $this->to_array_userrole();
        $this->display($vars);
    }

    // ----------------------------------------------------------------------------------

    function select()
    {
        $field      = Input::get('f');
        $word       = Input::get('w');
        $page       = Input::get('p', 1);

        $page_config    = $GLOBALS['page_config'];
        $pagination     = new Pagination();
        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM WebUser WHERE Site=?");
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();
            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM WebUser WHERE Site=? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM WebUser WHERE Site=? AND $field LIKE ?");
            $count->execute(array($_SESSION['spider_site'], Util::parse_search_string($word)));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM WebUser WHERE Site=? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], Util::parse_search_string($word), $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        $attrUserRole   = $this->to_array_userrole();
        $attrFloor      = $this->to_array_floor();
        
        foreach( $list as $key=>$val )
        {
        	$val['ID2']         = $val['ID'];
            $val['TypeStr']     = EnumTable::$attrUserType[$val['Type']];
            $val['UserRoleStr'] = $attrUserRole[$val['UserRole']];
			$val['LanguageStr'] = EnumTable::$attrLanguage[$val['Language']];
			$val['DefaultPageStr'] = EnumTable::$attrDefaultPage[$val['DefaultPage']];
			$val['DefaultFloorStr'] = $attrFloor[$val['DefaultFloorNo']];
			$val['DefaultFloorSateStr'] = EnumTable::$attrYesNo[$val['DefaultFloorState']];
			$val['AutoDisconnectTimeStr'] = EnumTable::$attrAutoDisconnectTime[$val['AutoDisconnectTime']];
            $list[$key] = $val;
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['page']         = $page;
        $result['pages']        = $pagination->get_pages();
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }
    
    // ----------------------------------------------------------------------------------
    
    function insert()
    {
    	$max_user  = Util::GetLimitCount(ConstTable::MAX_CONNECT_1, 
		                                 ConstTable::MAX_CONNECT_2, 
        		                         ConstTable::MAX_CONNECT_3);
		if ($max_user <= Util::GetRecordCount('WebUser'))
    	{
    		Util::alert($this->lang->addmsg->error_limit_connect, TRUE);
    	}
    	
        $No = $this->conn->prepare("SELECT MAX(No) from WebUser WHERE Site=?");
        $No->execute(array($_SESSION['spider_site']));
        $No    = $No->fetchColumn() + 1;
            
        $Name     = strip_tags(trim(Input::post('Name')));
        $Type     = Input::post('Type');
        $ID       = strip_tags(Input::post('ID'));
        $Password = Input::post('Password');
        $UserRole = Input::post('UserRole');
        $Language = Input::post('Language');
        $DefaultPage = Input::post('DefaultPage');
        $DefaultFloorNo = Input::post('DefaultFloorNo');
        $DefaultFloorState = Input::post('DefaultFloorState');
        $AutoDisconnectTime = Input::post('AutoDisconnectTime');
        
        if( empty($ID) )   			Util::alert( $this->lang->menu->error_id_required, TRUE );
        if( empty($Password) )   	Util::alert( $this->lang->menu->error_password_required, TRUE );
        
        $idcount  = Util::GetRecordCount("WebUser", " WHERE Site='".$_SESSION['spider_site']."' AND ID='".$ID."'");
        if( $idcount > 0)			Util::alert( $this->lang->addmsg->already_id_exist, TRUE );
        
        if( empty($Name) )          Util::alert( $this->lang->menu->error_name_required, TRUE );

        $sth = $this->conn->prepare("INSERT INTO WebUser (Site,No,Name,Type,ID,Password,UserRole,Language,DefaultPage,DefaultFloorNo,DefaultFloorState,AutoDisconnectTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $values = array($_SESSION['spider_site'],$No,$Name,$Type,$ID,$Password,$UserRole,$Language,$DefaultPage,$DefaultFloorNo,$DefaultFloorState,$AutoDisconnectTime);
        if( $sth->execute($values) )
        {
            Log::set_log_message($Name);
            Util::js('load_list();');
            Util::alert( $this->lang->common->save_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_insert);
        }
    }

    function update()
    {
        $No       = Input::post('No');
        $ID       = strip_tags(Input::post('ID'));
        $Password = Input::post('Password');
        $Name     = strip_tags(trim(Input::post('Name')));
        $Type     = Input::post('Type');
        $UserRole = Input::post('UserRole');
        $Language = Input::post('Language');
        $DefaultPage = Input::post('DefaultPage');
        $DefaultFloorNo = Input::post('DefaultFloorNo');
        $DefaultFloorState = Input::post('DefaultFloorState');
        $AutoDisconnectTime = Input::post('AutoDisconnectTime');

        if( empty($ID) )   			Util::alert( $this->lang->menu->error_id_required, TRUE );        
        if( empty($Password) )   	Util::alert( $this->lang->menu->error_password_required, TRUE );
        if( empty($Name) )          Util::alert( $this->lang->menu->error_name_required, TRUE );

        $sth    = $this->conn->prepare("UPDATE WebUser SET Name=?,Type=?,ID=?,Password=?,UserRole=?,Language=?,DefaultPage=?,DefaultFloorNo=?,DefaultFloorState=?,AutoDisconnectTime=? WHERE Site=? AND No=?");

        $values = array($Name,$Type,$ID,$Password,$UserRole,$Language,$DefaultPage,$DefaultFloorNo,$DefaultFloorState,$AutoDisconnectTime,$_SESSION['spider_site'],$No);
        if( $sth->execute($values) )
        {
            Log::set_log_message($Name);
            Util::js('update_list("'.$No.'");');
            Util::alert( $this->lang->common->save_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    function check_dependency()
    {
        $no     = Input::get('no');

        $userrole   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND WEBLevel=?");
        $userrole->execute(array($_SESSION['spider_site'], $no));
        if( $userrole = $userrole->fetchAll(PDO::FETCH_ASSOC) )
		{
			Util::js('confirm_dependency()', TRUE);
		}

		Util::js('del_data_prepass()', TRUE);
    }

    // ----------------------------------------------------------------------------------

    function delete()
    {
        $no     = Input::get('no');
        $Name   = Util::GetRecordName('WebUser', $no);

        $User   = $this->conn->query("DELETE FROM WebUser WHERE Site=? AND No=?");
        if( $User->execute(array($_SESSION['spider_site'], $no)) )
        {
            Log::set_log_message($Name);
            Util::js('update_list();');
            Util::alert( $this->lang->common->delete_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_delete);
        }
    }

    // ----------------------------------------------------------------------------------
    function to_array_userrole()
    {
        $list   = $this->conn->prepare("SELECT * FROM UserRole WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach ($list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }        

        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function to_array_floor()
    {
        $list   = $this->conn->prepare("SELECT * FROM Floor WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach ($list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }        

        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function floor_show()
    {
		if( $_SESSION['spider_type'] == 'spider' ) {
			$sth    = $this->conn->prepare("UPDATE Controller SET DefaultFloorState=? WHERE No=?");
			$values = array('1','1');
			$sth->execute($values);
		} else {
	        $sth    = $this->conn->prepare("UPDATE WebUser SET DefaultFloorState=? WHERE Site=? AND No=?");
			$values = array('1',$_SESSION['spider_site'],$_SESSION['spider_userno']);
			$sth->execute($values);
		}

		$_SESSION['spider_floorstate'] = '1';
		session_write_close();
    }

    // ----------------------------------------------------------------------------------

    function floor_hide()
    {
		if( $_SESSION['spider_type'] == 'spider' ) {
	        $sth    = $this->conn->prepare("UPDATE Controller SET DefaultFloorState=? WHERE No=?");
			$values = array('0','1');
			$sth->execute($values);
		} else {
	        $sth    = $this->conn->prepare("UPDATE WebUser SET DefaultFloorState=? WHERE Site=? AND No=?");
			$values = array('0',$_SESSION['spider_site'],$_SESSION['spider_userno']);
			$sth->execute($values);
		}

		$_SESSION['spider_floorstate'] = '0';
		session_write_close();
    }

    // ----------------------------------------------------------------------------------

	function check_max_count()
	{
    	$max_user  = Util::GetLimitCount(ConstTable::MAX_CONNECT_1, 
		                                 ConstTable::MAX_CONNECT_2, 
        		                         ConstTable::MAX_CONNECT_3);
		if ($max_user <= Util::GetRecordCount('WebUser'))
    	{
			Util::js('close_new();');
    		Util::alert($this->lang->addmsg->error_limit_connect);
    	} else {
			Util::js('open_new();');
			Util::js("init_form_data('new');");
		}
	}

    // ----------------------------------------------------------------------------------
}


?>