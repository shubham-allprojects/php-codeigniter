<?php


class userrole extends controller
{

	// ----------------------------------------------------------------------------------

	function index()
	{
		if( $this->is_auth(56, 0) != TRUE )
		{
			Util::alert($this->lang->user->error_not_permission);
			Util::back();
			exit;
		}

		if( Input::get('wizard') == '1' )	$this->display($vars, '', 'none');
		else								$this->display($vars);
	}

	// ----------------------------------------------------------------------------------

	function select()
	{
		$field	= Input::get('f');
		$word	= Input::get('w');
		$page	= Input::get('p', 1);
		$view	= Input::get('v');

		$page_config	= $GLOBALS['page_config'];
		$pagination		= new Pagination();

		if( empty($field) || empty($word) )
		{
			$count	= $this->conn->prepare("SELECT COUNT(*) FROM UserRole WHERE Site=?");
			$count->execute(array($_SESSION['spider_site']));
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? ORDER BY No DESC LIMIT ?, ?");
			$list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(PDO::FETCH_ASSOC);
		}
		else
		{
			$count	= $this->conn->prepare("SELECT COUNT(*) FROM UserRole WHERE Site=? AND $field LIKE ?");
			$count->execute(array($_SESSION['spider_site'], Util::parse_search_string($word)));
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
			$list->execute(array($_SESSION['spider_site'], Util::parse_search_string($word), $pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(PDO::FETCH_ASSOC);
		}

		$result['field']		= $field;
		$result['word']			= $word;
		$result['page']			= $page;
		$result['view']			= $view;
		$result['pages']		= $pagination->get_pages();
		$result['count']		= $count;
		$result['list']			= $list;

		echo json_encode($result);
	}

	// ----------------------------------------------------------------------------------

	function to_array_userrole()
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? ORDER BY Name ASC");
		$list->execute(array($_SESSION['spider_site']));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[$row['No']]	= $row['Name'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_camera()
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM Camera WHERE Site=? ORDER BY Name ASC");
		$list->execute(array($_SESSION['spider_site']));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[$row['No']]	= $row['Name'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_dvr()
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM Dvr WHERE Site=? AND ViewerType =1 ORDER BY Name ASC");
		$list->execute(array($_SESSION['spider_site']));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[$row['No']]	= $row['Name'];
		}

		return $arr;
	}
    
    // ----------------------------------------------------------------------------------
    
    function to_array_nvr()
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM Dvr WHERE Site=? AND ViewerType =2 ORDER BY Name ASC");
		$list->execute(array($_SESSION['spider_site']));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[$row['No']]	= $row['Name'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_report()
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM SR_Main WHERE Site=? ORDER BY Name ASC");
		if( TARGET_BOARD == "EVB") 
		{
			$list	= $this->conn->prepare("SELECT * FROM SR_Main WHERE Site=? AND No != 7 ORDER BY Name ASC");
		}
		$list->execute(array($_SESSION['spider_site']));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[$row['No']]	= $row['Name'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_userrolecamera($userrole)
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM UserRoleCamera WHERE Site=? AND UserRole=?");
		$list->execute(array($_SESSION['spider_site'], $userrole));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[]	= $row['CameraNo'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_userroledvr($userrole)
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM UserRoleDvr WHERE Site=? AND UserRole=?");
		$list->execute(array($_SESSION['spider_site'], $userrole));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[]	= $row['DvrNo'];
		}

		return $arr;
	}
    
    // ----------------------------------------------------------------------------------

	function to_array_userrolenvr($userrole)
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM UserRoleDvr WHERE Site=? AND UserRole=?");
		$list->execute(array($_SESSION['spider_site'], $userrole));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[]	= $row['DvrNo'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_userrolereport($userrole)
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT * FROM UserRoleReport WHERE Site=? AND UserRole=?");
		
		$list->execute(array($_SESSION['spider_site'], $userrole));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			$arr[]	= $row['ReportNo'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function to_array_userroledevice($userrole, $device)
	{
		$arr	= array();

		$list	= $this->conn->prepare("SELECT A.*, B.* FROM UserRoleGroup AS A, UserRole AS B WHERE A.Site=? AND A.UserRole=? AND A.DeviceKind=? AND A.Site=B.Site AND A.UserRole=B.No");
		$list->execute(array($_SESSION['spider_site'], $userrole, $device));
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		foreach($list as $row)
		{
			switch( $device )
			{
				case "1":	
					if ($row['DoorSelType'] == '0')
						$list2	= $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND No=?");			
					else
						$list2	= $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND No=? AND GroupKind=2");
					break;
				case "2":	
					if ($row['ElevatorSelType'] == '0')
						$list2	= $this->conn->prepare("SELECT * FROM Elevator WHERE Site=? AND No=?");		
					else
						$list2	= $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND No=? AND GroupKind=11");
					break;
				case "3":	
					if ($row['AuxInSelType'] == '0')
						$list2	= $this->conn->prepare("SELECT * FROM AuxInput WHERE Site=? AND No=?");		
					else
						$list2	= $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND No=? AND GroupKind=6");
					break;
				case "4":	
					if ($row['AuxOutSelType'] == '0')
						$list2	= $this->conn->prepare("SELECT * FROM AuxOutput WHERE Site=? AND No=?");	
					else
						$list2	= $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND No=? AND GroupKind=7");
					break;
			}
			$list2->execute(array($_SESSION['spider_site'], $row['DeviceNo']));

			if( $list2 = $list2->fetch(PDO::FETCH_ASSOC) )	$arr[$row['DeviceNo']] = $list2['Name'];
		}

		return $arr;
	}

	// ----------------------------------------------------------------------------------

	function get_userroletable($userrole)
	{
        $userroletables = $this->conn->prepare("SELECT * FROM UserRoleTable WHERE Site=? AND UserRole=?");
		$userroletables->execute(array($_SESSION['spider_site'], $userrole));
        $userroletables	= $userroletables->fetchAll(PDO::FETCH_ASSOC);

		$arr_userroletable	= array();
		foreach( $userroletables as $key=>$userroletable )
		{
			$arr_userroletable[$userroletable['FormIndex']][$userroletable['Authority']]	= $userroletable['FormIndex'].'::'.$userroletable['Authority'];
		}

		return $arr_userroletable;
	}

	// ----------------------------------------------------------------------------------

	function search_device()
	{
		$device  = Input::get('device');
		$seltype = Input::get('seltype');
		$keyword = Input::get('keyword');
		
		switch($device)
		{
			case '1':	$tablename = 'Door';      $group_kind = '2';  break;
			case '2':	$tablename = 'Elevator';  $group_kind = '12'; break;
			case '3':	$tablename = 'AuxInput';  $group_kind = '6';  break;
			case '4':	$tablename = 'AuxOutput'; $group_kind = '7';  break;
		}

		if( $seltype == '1' )
		{
			$list	= $this->conn->prepare("SELECT No,Name FROM GroupTable WHERE Site=? AND GroupKind=? AND Name LIKE ?");
			$list->execute(array($_SESSION['spider_site'], $group_kind, "%{$keyword}%"));
		}
		else
		{
			$list	= $this->conn->prepare("SELECT No,Name FROM $tablename WHERE Site=? AND Name LIKE ?");
			$list->execute(array($_SESSION['spider_site'], "%{$keyword}%"));
		}
		$list	= $list->fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($list);

	}

	// ----------------------------------------------------------------------------------

	function view()
	{
		/*
		if( $this->is_auth(56, 3) != TRUE )
		{
			Util::alert($this->lang->user->error_not_permission, FALSE, TRUE);
			Util::js('$("#new_section").hide();', TRUE, TRUE);
		}
		*/

        $No             = Input::get('No');
		$default        = Input::get('default');

        $row = $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? AND No=?");
        $row->execute(array($_SESSION['spider_site'], $No));
        $row = $row->fetch(PDO::FETCH_ASSOC);

		if( $default )
		{
			$row2 = $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? AND No=?");
			$row2->execute(array($_SESSION['spider_site'], $default));
			$row2 = $row2->fetch(PDO::FETCH_ASSOC);

			$row2['No']		= $row['No'];
			$row2['Name']	= $row['Name'];
			$vars['row']	= $row2;

			$vars['userroletables'] = $this->get_userroletable($default);
		}
		else
		{
			$vars['row']	= $row;
			$vars['userroletables'] = $this->get_userroletable($No);
		}

		$vars['default']	= $default;

		$this->display($vars, 'userrole_view', FALSE);
	}

	// ----------------------------------------------------------------------------------

	function newrow()
	{
		/*
		if( $this->is_auth(56, 1) != TRUE )
		{
			Util::alert($this->lang->user->error_not_permission, FALSE, TRUE);
			Util::js('$("#new_section").hide();', TRUE, TRUE);
		}
		*/

		$default        = Input::get('default');

		if( $default )
		{
			$row = $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? AND No=?");
			$row->execute(array($_SESSION['spider_site'], $default));
			$row = $row->fetch(PDO::FETCH_ASSOC);

			$row['No']		= '';
			$row['Name']	= '';
			$vars['row']	= $row;

			$vars['userroletables'] = $this->get_userroletable($default);
		}

		$vars['default']	= $default;

		$this->display($vars, 'userrole_new', FALSE);
	}

	// ----------------------------------------------------------------------------------

	function edit()
	{
        $No             = Input::get('No');

        $row = $this->conn->prepare("SELECT * FROM UserRole WHERE Site=? AND No=?");
        $row->execute(array($_SESSION['spider_site'], $No));
        $row = $row->fetch(PDO::FETCH_ASSOC);

        $vars['row']	= $row;
        $vars['userroletables'] = $this->get_userroletable($No);

        $this->display($vars, 'userrole_edit', FALSE);
	}

	// ----------------------------------------------------------------------------------

	function get_use_group()
	{
		$UseDoorSet = '0';
		$DoorSelType = '0';
		if( Input::post('UseDoorGroup') == '1' )
		{
			$UseDoorSet = '1';
			if( Input::post('DoorSelType') == '1' ) { $DoorSelType = '1'; }
		}
		$UseElevatorSet = '0';
		$ElevatorSelType = '0';
		if( Input::post('UseElevatorGroup') == '1' )
		{
			$UseElevatorSet = '1';
			if( Input::post('ElevatorSelType') == '1' ) { $ElevatorSelType = '1'; }
		}
		$UseAuxInSet = '0';
		$AuxInSelType = '0';
		if( Input::post('UseAuxInGroup') == '1' )
		{
			$UseAuxInSet = '1';
			if( Input::post('AuxInSelType') == '1' ) { $AuxInSelType = '1'; }
		}
		$UseAuxOutSet = '0';
		$AuxOutSelType = '0';
		if( Input::post('UseAuxOutGroup') == '1' )
		{
			$UseAuxOutSet = '1';
			if( Input::post('AuxOutSelType') == '1' ) { $AuxOutSelType = '1'; }
		}

		return array(
			'UseDoorSet'	  => $UseDoorSet,
			'UseElevatorSet'  => $UseElevatorSet,
			'UseAuxInSet'	  => $UseAuxInSet,
			'UseAuxOutSet'	  => $UseAuxOutSet,
			'DoorSelType'	  => $DoorSelType,
			'ElevatorSelType' => $ElevatorSelType,
			'AuxInSelType'	  => $AuxInSelType,
			'AuxOutSelType'	  => $AuxOutSelType
			);
	}

	// ----------------------------------------------------------------------------------

	function insert()
	{
		$max	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][13];

		if ($max <= Util::GetRecordCount('UserRole'))
    	{
            Util::js('parent.hide_loading();');
    		Util::alert($this->lang->menu->error_too_many_userrole, TRUE);
    	}

		$use = $this->get_use_group();
		
        $Name  = strip_tags(trim(Input::post('Name')));
        if( empty($Name) )  
        {
            Util::js('parent.hide_loading();');
            Util::alert( $this->lang->menu->error_name_required, TRUE );		
        }

		/*
		if($_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL)
		{
			$count = $this->conn->prepare("SELECT COUNT(No) FROM UserRole");
			$count->execute();
			$count = $count->fetchColumn();
			if($count >= 3)	Util::alert( $this->lang->menu->error_too_many_userrole, TRUE );
		}
		*/

        $No = $this->conn->prepare("SELECT MAX(No) FROM UserRole");
        $No->execute();
        $No = $No->fetchColumn() + 1;

		$sth    = $this->conn->prepare("INSERT INTO UserRole (Site,No,Name,UseDoorSet,UseElevatorSet,UseAuxInSet,UseAuxOutSet,DoorSelType,ElevatorSelType,AuxInSelType,AuxOutSelType) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
		if( !$sth->execute(array(
			$_SESSION['spider_site'],
			$No,
			$Name,
			$use['UseDoorSet'],
			$use['UseElevatorSet'],
			$use['UseAuxInSet'],
			$use['UseAuxOutSet'],
			$use['DoorSelType'],
			$use['ElevatorSelType'],
			$use['AuxInSelType'],
			$use['AuxOutSelType']
		)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_insert, TRUE);
        }

		$this->save_userroletable($No);
		$this->save_userrolecamera($No);
		$this->save_userroledvr($No);
        $this->save_userrolenvr($No);
		$this->save_userrolereport($No);
		$this->save_device($No, '1');
		$this->save_device($No, '2');
		$this->save_device($No, '3');
		$this->save_device($No, '4');

		//exec(SPIDER_COMM." send db");
        Log::set_log_message($Name);
		//Util::js('parent.load_list(); parent.open_edit("'.$No.'");');
		Util::js('parent.load_list();');
        Util::alert( $this->lang->common->save_completed );
        Util::js('parent.hide_loading();');
	}

	// ----------------------------------------------------------------------------------

	function update()
	{
		$use = $this->get_use_group();

        $No    = Input::post('No');
        $Name  = strip_tags(trim(Input::post('Name')));
        
        if( empty($Name) )  
        {
            Util::js('parent.hide_loading();');
            Util::alert( $this->lang->menu->error_name_required, TRUE );
        }

		$sth    = $this->conn->prepare("UPDATE UserRole SET Name=?, UseDoorSet=?, UseElevatorSet=?, UseAuxInSet=?, UseAuxOutSet=?, DoorSelType=?, ElevatorSelType=?, AuxInSelType=?, AuxOutSelType=? WHERE Site=? AND No=?");
		if( !$sth->execute(array(
			$Name,
			$use['UseDoorSet'],
			$use['UseElevatorSet'],
			$use['UseAuxInSet'],
			$use['UseAuxOutSet'],
			$use['DoorSelType'],
			$use['ElevatorSelType'],
			$use['AuxInSelType'],
			$use['AuxOutSelType'],
			$_SESSION['spider_site'],
			$No
		)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_update, TRUE);
        }

		$this->save_userroletable($No);
		$this->save_userrolecamera($No);
		$this->save_userroledvr($No);
        $this->save_userrolenvr($No);
		$this->save_userrolereport($No);
		$this->save_device($No, '1');
		$this->save_device($No, '2');
		$this->save_device($No, '3');
		$this->save_device($No, '4');

		//exec(SPIDER_COMM." send db");
        Log::set_log_message($Name);
        Util::js('parent.load_list(); parent.open_edit("'.$No.'");');
        Util::alert( $this->lang->common->save_completed );
        Util::js('parent.hide_loading();');
	}

    // ----------------------------------------------------------------------------------

    function check_dependency()
    {
        $no     = Input::get('no');

        $item   = $this->conn->prepare("SELECT * FROM WebUser WHERE Site=? AND UserRole=?");
        $item->execute(array($_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(PDO::FETCH_ASSOC) )
		{
			Util::js('confirm_dependency("'.$no.'")', TRUE);
		}

		Util::js('del_data_prepass("'.$no.'")', TRUE);
    }

	// ----------------------------------------------------------------------------------

	function delete()
	{
        $No     = Input::get('no');
        $Name   = Util::GetRecordName('UserRole', $No);
    	
		$sth = $this->conn->prepare("DELETE FROM UserRole WHERE Site=? AND No=?");
		if( $sth->execute(array($_SESSION['spider_site'],$No)) )
        {
         	$sth = $this->conn->prepare("DELETE FROM UserRoleTable WHERE Site=? AND UserRole=?");
 			if( $sth->execute(array($_SESSION['spider_site'],$No)) )
 			{
	         	$sth = $this->conn->prepare("DELETE FROM UserRoleReport WHERE Site=? AND UserRole=?");
 				$sth->execute(array($_SESSION['spider_site'],$No));
 				
 				$sth = $this->conn->prepare("DELETE FROM UserRoleGroup WHERE Site=? AND UserRole=?");
 				$sth->execute(array($_SESSION['spider_site'],$No));
 				
 				$sth = $this->conn->prepare("DELETE FROM UserRoleCamera WHERE Site=? AND UserRole=?");
 				$sth->execute(array($_SESSION['spider_site'],$No));
 				
 				$sth = $this->conn->prepare("DELETE FROM UserRoleDvr WHERE Site=? AND UserRole=?");
 				$sth->execute(array($_SESSION['spider_site'],$No));
 			
 				//exec(SPIDER_COMM." send db");	
 				Log::set_log_message($Name);
                Util::alert( $this->lang->common->delete_completed );
 			}
        }
	}

	// ----------------------------------------------------------------------------------

	function save_userroletable($No)
	{
            $addcardflash = '15';
            $default_role_value = Input::post('default', 0);

            if($default_role_value == 0){
                $userRole_condition = $No;
            }else{
                $userRole_condition = $default_role_value;
            }
                
            $count = $this->conn->prepare("SELECT * FROM UserRoleTable WHERE FormIndex = 1 AND Authority IN (25,24,23,22) AND UserRole = ?");
            $count->execute(array($userRole_condition));
            $count = $count->fetchColumn();
            if($count > 0)
                $permission = TRUE;
            else
                $permission = FALSE;

            $sth    = $this->conn->prepare("DELETE FROM UserRoleTable WHERE Site=? AND UserRole=?");
            if( !$sth->execute(array($_SESSION['spider_site'], $No)) )
            {
                Util::js('parent.hide_loading();');
                Util::alert($this->lang->common->error_delete, TRUE);
            }

            $UserRoleTableNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleTable");
            $UserRoleTableNo->execute();
            $UserRoleTableNo = $UserRoleTableNo->fetchColumn() + 1;

            $userroletables = Input::post('userroletables', array());
                
            if($permission){
                // Added default permissions if user type is Super User/User - Rakesh Shetty
                array_push($userroletables, "1::25", "1::24","1::23","1::22");
            }
                
            $sth    = $this->conn->prepare("INSERT INTO UserRoleTable (Site,No,UserRole,FormIndex,Authority) VALUES (?,?,?,?,?)");
            foreach( $userroletables as $key=>$value )
            {
                    $values = explode('::', $value);
                    $sth->execute( array($_SESSION['spider_site'], $UserRoleTableNo, $No, $values[0], $values[1]) );
                    /** 2017.05.25 CJMOON bug fix (card_holder == resident)  */
                    if($values[0] /*FormIndex*/ == '3' /*card_holder*/) {
                            $UserRoleTableNo++;
                            $sth->execute( array($_SESSION['spider_site'], $UserRoleTableNo, $No, '13'/*resident*/, $values[1]) );
                    }

                $UserRoleTableNo++;
            }
	}

	// ----------------------------------------------------------------------------------

	function save_userrolecamera($No)
	{
		$sth    = $this->conn->prepare("DELETE FROM UserRoleCamera WHERE Site=? AND UserRole=?");
		if( !$sth->execute(array($_SESSION['spider_site'], $No)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_delete, TRUE);
        }

        $UserRoleCameraNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleCamera");
        $UserRoleCameraNo->execute();
        $UserRoleCameraNo = $UserRoleCameraNo->fetchColumn() + 1;

		$userrolecameras = Input::post('userrolecameras', array());
		$sth    = $this->conn->prepare("INSERT INTO UserRoleCamera (Site,No,UserRole,CameraNo) VALUES (?,?,?,?)");
		foreach( $userrolecameras as $key=>$value )
		{
			$sth->execute( array($_SESSION['spider_site'], $UserRoleCameraNo, $No, $value) );
			$UserRoleCameraNo++;
		}
	}

	// ----------------------------------------------------------------------------------

	function save_userroledvr($No)
	{
		$sth    = $this->conn->prepare("DELETE FROM UserRoleDvr WHERE Site=? AND UserRole=?");
		if( !$sth->execute(array($_SESSION['spider_site'], $No)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_delete, TRUE);
        }

        $UserRoleDvrNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleDvr");
        $UserRoleDvrNo->execute();
        $UserRoleDvrNo = $UserRoleDvrNo->fetchColumn() + 1;

		$userroledvrs = Input::post('userroledvrs', array());
		$sth    = $this->conn->prepare("INSERT INTO UserRoleDvr (Site,No,UserRole,DvrNo) VALUES (?,?,?,?)");
		foreach( $userroledvrs as $key=>$value )
		{
			$sth->execute( array($_SESSION['spider_site'], $UserRoleDvrNo, $No, $value) );
			$UserRoleDvrNo++;
		}
	}
    
    function save_userrolenvr($No)
	{
		$sth    = $this->conn->prepare("DELETE FROM UserRoleDvr WHERE Site=? AND UserRole=?");
		if( !$sth->execute(array($_SESSION['spider_site'], $No)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_delete, TRUE);
        }

        $UserRoleDvrNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleDvr");
        $UserRoleDvrNo->execute();
        $UserRoleDvrNo = $UserRoleDvrNo->fetchColumn() + 1;

		$userroledvrs = Input::post('userroledvrs', array());
		$sth    = $this->conn->prepare("INSERT INTO UserRoleDvr (Site,No,UserRole,DvrNo) VALUES (?,?,?,?)");
		foreach( $userroledvrs as $key=>$value )
		{
			$sth->execute( array($_SESSION['spider_site'], $UserRoleDvrNo, $No, $value) );
			$UserRoleDvrNo++;
		}
	}

	// ----------------------------------------------------------------------------------

	function save_userrolereport($No)
	{
		$sth    = $this->conn->prepare("DELETE FROM UserRoleReport WHERE Site=? AND UserRole=?");
		if( !$sth->execute(array($_SESSION['spider_site'], $No)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_delete, TRUE);
        }

        $UserRoleReportNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleReport");
        $UserRoleReportNo->execute();
        $UserRoleReportNo = $UserRoleReportNo->fetchColumn() + 1;

		$userrolereports = Input::post('userrolereports', array());
		$sth    = $this->conn->prepare("INSERT INTO UserRoleReport (Site,No,UserRole,ReportNo) VALUES (?,?,?,?)");
		foreach( $userrolereports as $key=>$value )
		{
			$sth->execute( array($_SESSION['spider_site'], $UserRoleReportNo, $No, $value) );
			$UserRoleReportNo++;
		}
	}

	// ----------------------------------------------------------------------------------

	function save_device($No, $device)
	{
		switch( $device )
		{
			case '1':	$tablename = 'Door';      $device_name = 'Door';  break;
			case '2':	$tablename = 'Elevator';  $device_name = 'Elevator'; break;
			case '3':	$tablename = 'AuxInput';  $device_name = 'AuxIn';  break;
			case '4':	$tablename = 'AuxOutput'; $device_name = 'AuxOut';  break;
		}

		$sth    = $this->conn->prepare("DELETE FROM UserRoleGroup WHERE Site=? AND UserRole=? AND DeviceKind=?");
		if( !$sth->execute(array($_SESSION['spider_site'], $No, $device)) )
        {
            Util::js('parent.hide_loading();');
            Util::alert($this->lang->common->error_delete, TRUE);
        }

		if( Input::post('Use'.$device_name.'Group') == '1' )
		{
			$rowNo = $this->conn->prepare("SELECT MAX(No) FROM UserRoleGroup");
			$rowNo->execute();
			$rowNo = $rowNo->fetchColumn() + 1;

			$devices = Input::post('userrole'.strtolower($device_name).'s', array());
			$sth    = $this->conn->prepare("INSERT INTO UserRoleGroup (Site,No,UserRole,DeviceKind,DeviceNo,Disable) VALUES (?,?,?,?,?,?)");
			foreach( $devices as $key=>$value )
			{
				$sth->execute( array($_SESSION['spider_site'], $rowNo, $No, $device, $value, '0') );
				$rowNo++;
			}
		}
	}

	// ----------------------------------------------------------------------------------

}


?>