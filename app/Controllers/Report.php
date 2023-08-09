<?php

class report extends controller
{

	var $report_tables	= array(
		'door'					=>	'Door',
//		'elevator'				=>	'Elevator',		//DELETE CJMOON 2017.03.20  
		'auxinput'				=>	'Aux Input',
		'auxoutput'				=>	'Aux Output',
		'cardholder'			=>	'Resident',
		'card'					=>	'Card',
		'cardholder_accesslevel'=>	'Resident Access Levels',
		'accesslevel_door'		=>	'Access Level Doors',
		'doorgroup'				=>	'Door Groups'
//		'occupancy'				=>	'Occupancy',    //DELETE CJMOON 2017.03.20
//		'muster'				=>	'Muster'        //DELETE CJMOON 2017.03.20
		);

	var $report_tables_for_essential	= array(
		'door'					=>	'Door',
		'auxinput'				=>	'Aux Input',
		'auxoutput'				=>	'Aux Output',
		'cardholder'			=>	'Card Holder',
		'card'					=>	'Card',
		'cardholder_accesslevel'=>	'Card Holder Access Levels',
		'accesslevel_door'		=>	'Access Level Doors',
		'doorgroup'				=>	'Door Groups'
		);

	// ----------------------------------------------------------------------------------

	function index()
	{
		$vars	= array();
		$vars['floors']			= $this->to_array_floor();
		$vars['cardformats']	= $this->to_array_cardformat();
		$vars['accesslevels']	= $this->to_array_accesslevel();
        $vars['regions']	    = $this->to_array_without_muster_regions();
        $vars['muster_regions']	= $this->to_array_muster_regions();

		$this->display($vars);
	}

	// ----------------------------------------------------------------------------------

	function door()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT Door.No, Door.Name, Door.Mean, Door.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM Door
                        LEFT OUTER JOIN Floor ON Floor.Site=Door.Site AND Floor.No=Door.Floor
                        WHERE Door.Site=? AND Door.Disable=0 ";
        
            $sql_count = " SELECT COUNT(*)
                            FROM Door
                            LEFT OUTER JOIN Floor ON Floor.Site=Door.Site AND Floor.No=Door.Floor
                            WHERE Door.Site=? AND Door.Disable=0 ";

        }  
        else{
            $sql = " SELECT Door.No, Door.Name, Door.Mean, Door.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM Door
                        LEFT OUTER JOIN Floor ON Floor.Site=Door.Site AND Floor.No=Door.Floor
                        JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                        WHERE Door.Site=? AND Door.Disable=0 ";
                        
            $sql_count = " SELECT COUNT(*)
                            FROM Door
                            LEFT OUTER JOIN Floor ON Floor.Site=Door.Site AND Floor.No=Door.Floor
                            JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            WHERE Door.Site=? AND Door.Disable=0 ";
            
        }
            
        
		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_door_no');
		$filter['name']			= Input::param('filter_door_name');
		$filter['mean']			= Input::param('filter_door_mean');
		$filter['floorname']	= Input::param('filter_door_floorname');
		$filter['floorno']		= Input::param('filter_door_floorno');

		if( trim($filter['no']) != '' )			{	$where .= " AND Door.No LIKE ?";	$params[] = Util::parse_search_string($filter['no']);			}
		if( trim($filter['name']) != '' )		{	$where .= " AND Door.Name LIKE ?";	$params[] = Util::parse_search_string($filter['name']);			}
		if( trim($filter['mean']) != '' )		{	$where .= " AND Door.Mean LIKE ?";	$params[] = Util::parse_search_string($filter['mean']);			}
		if( trim($filter['floorname']) != '' )	{	$where .= " AND Floor.Name LIKE ?";	$params[] = Util::parse_search_string($filter['floorname']);	}
		if( trim($filter['floorno']) != '' )	{	$where .= " AND Floor.No = ?";		$params[] = $filter['floorno'];									}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Door.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_door_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Door.No ASC");
			$list->execute($params);

			$filename   = "report_door_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->name
				.','. $this->lang->report->description
				.','. $this->lang->report->floor
				.','. $this->lang->report->port
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['Name'] .'"'
					.',"'. $row['Mean'] .'"'
					.',"'. $row['FloorName'] .'"'
					.',"'. $row['Port'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
            $count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Door.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_door', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function elevator()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT Elevator.No, Elevator.Name, Elevator.Mean, Elevator.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM Elevator
                        LEFT OUTER JOIN Floor ON Floor.Site=Elevator.Site AND Floor.No=Elevator.Floor
                        WHERE Elevator.Site=? AND Elevator.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM Elevator
                            LEFT OUTER JOIN Floor ON Floor.Site=Elevator.Site AND Floor.No=Elevator.Floor
                            WHERE Elevator.Site=? AND Elevator.Disable=0 ";
        }
        else{
            $sql = " SELECT Elevator.No, Elevator.Name, Elevator.Mean, Elevator.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM Elevator
                        LEFT OUTER JOIN Floor ON Floor.Site=Elevator.Site AND Floor.No=Elevator.Floor
                        JOIN Host ON Elevator.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                        WHERE Elevator.Site=? AND Elevator.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM Elevator
                            LEFT OUTER JOIN Floor ON Floor.Site=Elevator.Site AND Floor.No=Elevator.Floor
                            JOIN Host ON Elevator.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            WHERE Elevator.Site=? AND Elevator.Disable=0 ";
        }

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_elevator_no');
		$filter['name']			= Input::param('filter_elevator_name');
		$filter['mean']			= Input::param('filter_elevator_mean');
		$filter['floorname']	= Input::param('filter_elevator_floorname');
		$filter['floorno']		= Input::param('filter_elevator_floorno');

		if( trim($filter['no']) != '' )			{	$where .= " AND Elevator.No LIKE ?";	$params[] = Util::parse_search_string($filter['no']);			}
		if( trim($filter['name']) != '' )		{	$where .= " AND Elevator.Name LIKE ?";	$params[] = Util::parse_search_string($filter['name']);			}
		if( trim($filter['mean']) != '' )		{	$where .= " AND Elevator.Mean LIKE ?";	$params[] = Util::parse_search_string($filter['mean']);			}
		if( trim($filter['floorname']) != '' )	{	$where .= " AND Floor.Name LIKE ?";		$params[] = Util::parse_search_string($filter['floorname']);	}
		if( trim($filter['floorno']) != '' )	{	$where .= " AND Floor.No = ?";			$params[] = $filter['floorno'];									}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Elevator.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_elevator_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Elevator.No ASC");
			$list->execute($params);

			$filename   = "report_elevator_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->name
				.','. $this->lang->report->description
				.','. $this->lang->report->floor
				.','. $this->lang->report->port
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['Name'] .'"'
					.',"'. $row['Mean'] .'"'
					.',"'. $row['FloorName'] .'"'
					.',"'. $row['Port'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Elevator.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_elevator', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function auxinput()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT AuxInput.No, AuxInput.Name, AuxInput.Mean, AuxInput.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM AuxInput
                        LEFT OUTER JOIN Floor ON Floor.Site=AuxInput.Site AND Floor.No=AuxInput.Floor
                        WHERE AuxInput.Site=? AND AuxInput.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM AuxInput
                            LEFT OUTER JOIN Floor ON Floor.Site=AuxInput.Site AND Floor.No=AuxInput.Floor
                            WHERE AuxInput.Site=? AND AuxInput.Disable=0 ";
        }
        else{
            $sql = " SELECT AuxInput.No, AuxInput.Name, AuxInput.Mean, AuxInput.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                        FROM AuxInput
                        LEFT OUTER JOIN Floor ON Floor.Site=AuxInput.Site AND Floor.No=AuxInput.Floor
                        JOIN Host ON AuxInput.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                        WHERE AuxInput.Site=? AND AuxInput.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM AuxInput
                            LEFT OUTER JOIN Floor ON Floor.Site=AuxInput.Site AND Floor.No=AuxInput.Floor
                            JOIN Host ON AuxInput.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            WHERE AuxInput.Site=? AND AuxInput.Disable=0 ";
        }

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_auxinput_no');
		$filter['name']			= Input::param('filter_auxinput_name');
		$filter['mean']			= Input::param('filter_auxinput_mean');
		$filter['floorname']	= Input::param('filter_auxinput_floorname');
		$filter['floorno']		= Input::param('filter_auxinput_floorno');

		if( trim($filter['no']) != '' )			{	$where .= " AND AuxInput.No LIKE ?";	$params[] = Util::parse_search_string($filter['no']);			}
		if( trim($filter['name']) != '' )		{	$where .= " AND AuxInput.Name LIKE ?";	$params[] = Util::parse_search_string($filter['name']);			}
		if( trim($filter['mean']) != '' )		{	$where .= " AND AuxInput.Mean LIKE ?";	$params[] = Util::parse_search_string($filter['mean']);			}
		if( trim($filter['floorname']) != '' )	{	$where .= " AND Floor.Name LIKE ?";		$params[] = Util::parse_search_string($filter['floorname']);	}
		if( trim($filter['floorno']) != '' )	{	$where .= " AND Floor.No = ?";			$params[] = $filter['floorno'];									}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxInput.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_auxinput_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxInput.No ASC");
			$list->execute($params);

			$filename   = "report_auxinput_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->name
				.','. $this->lang->report->description
				.','. $this->lang->report->floor
				.','. $this->lang->report->port
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['Name'] .'"'
					.',"'. $row['Mean'] .'"'
					.',"'. $row['FloorName'] .'"'
					.',"'. $row['Port'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxInput.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_auxinput', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function auxoutput()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT AuxOutput.No, AuxOutput.Name, AuxOutput.Mean, AuxOutput.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                    FROM AuxOutput
                    LEFT OUTER JOIN Floor ON Floor.Site=AuxOutput.Site AND Floor.No=AuxOutput.Floor
                    WHERE AuxOutput.Site=? AND AuxOutput.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM AuxOutput
                            LEFT OUTER JOIN Floor ON Floor.Site=AuxOutput.Site AND Floor.No=AuxOutput.Floor
                            WHERE AuxOutput.Site=? AND AuxOutput.Disable=0 ";
        }
        else{
            $sql = " SELECT AuxOutput.No, AuxOutput.Name, AuxOutput.Mean, AuxOutput.Port, Floor.No AS FloorNo, Floor.Name AS FloorName
                    FROM AuxOutput
                    LEFT OUTER JOIN Floor ON Floor.Site=AuxOutput.Site AND Floor.No=AuxOutput.Floor
                    JOIN Host ON AuxOutput.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                    WHERE AuxOutput.Site=? AND AuxOutput.Disable=0 ";

            $sql_count = " SELECT COUNT(*)
                            FROM AuxOutput
                            LEFT OUTER JOIN Floor ON Floor.Site=AuxOutput.Site AND Floor.No=AuxOutput.Floor
                            JOIN Host ON AuxOutput.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            WHERE AuxOutput.Site=? AND AuxOutput.Disable=0 ";
        }

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_auxoutput_no');
		$filter['name']			= Input::param('filter_auxoutput_name');
		$filter['mean']			= Input::param('filter_auxoutput_mean');
		$filter['floorname']	= Input::param('filter_auxoutput_floorname');
		$filter['floorno']		= Input::param('filter_auxoutput_floorno');

		if( trim($filter['no']) != '' )			{	$where .= " AND AuxOutput.No LIKE ?";	$params[] = Util::parse_search_string($filter['no']);			}
		if( trim($filter['name']) != '' )		{	$where .= " AND AuxOutput.Name LIKE ?";	$params[] = Util::parse_search_string($filter['name']);			}
		if( trim($filter['mean']) != '' )		{	$where .= " AND AuxOutput.Mean LIKE ?";	$params[] = Util::parse_search_string($filter['mean']);			}
		if( trim($filter['floorname']) != '' )	{	$where .= " AND Floor.Name LIKE ?";		$params[] = Util::parse_search_string($filter['floorname']);	}
		if( trim($filter['floorno']) != '' )	{	$where .= " AND Floor.No = ?";			$params[] = $filter['floorno'];									}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxOutput.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_auxoutput_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxOutput.No ASC");
			$list->execute($params);

			$filename   = "report_auxoutput_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->name
				.','. $this->lang->report->description
				.','. $this->lang->report->floor
				.','. $this->lang->report->port
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['Name'] .'"'
					.',"'. $row['Mean'] .'"'
					.',"'. $row['FloorName'] .'"'
					.',"'. $row['Port'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AuxOutput.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_auxoutput', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function cardholder()
	{
		$action	= Input::get('action', 'search');
		$sql = " SELECT User.No, User.FirstName, User.MiddleName, User.LastName, User.ImageFile, Card.No AS CardID, Card.CardNo AS CardNumber, Card.CardStatus, Card.SelectType, Card.CardFormatNo
                    FROM User
                    LEFT OUTER JOIN Card ON Card.UserNo=User.No AND Card.Site=User.Site
                    WHERE User.Site=? ";

		$sql_count = " SELECT COUNT(*) FROM User
                        LEFT OUTER JOIN Card ON Card.UserNo=User.No AND Card.Site=User.Site
                        WHERE User.Site=? ";

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_cardholder_no');
		$filter['lastname']		= Input::param('filter_cardholder_lastname');
		$filter['firstname']	= Input::param('filter_cardholder_firstname');
		$filter['cardnumber']	= Input::param('filter_cardholder_cardnumber');
		$filter['cardstatus']	= Input::param('filter_cardholder_cardstatus');

		if( trim($filter['no']) != '' )			{	$where .= " AND User.No LIKE ?";		$params[] = Util::parse_search_string($filter['no']);			}
		if( trim($filter['lastname']) != '' )	{	$where .= " AND LastName LIKE ?";		$params[] = Util::parse_search_string($filter['lastname']);			}
		if( trim($filter['firstname']) != '' )	{	$where .= " AND FirstName LIKE ?";		$params[] = Util::parse_search_string($filter['firstname']);		}
		if( trim($filter['cardnumber']) != '' )	{	$where .= " AND Card.CardNo LIKE ?";	$params[] = Util::parse_search_string($filter['cardnumber']);	}
		if( trim($filter['cardstatus']) != '' )	{	$where .= " AND Card.CardStatus = ?";	$params[] = $filter['cardstatus'];								}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_cardholder_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC");
			$list->execute($params);

			$filename   = "report_cardholder_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->lastname
				.','. $this->lang->report->firstname
				.','. $this->lang->report->cardnumber
				.','. $this->lang->report->accesslevels
				.','. $this->lang->report->cardstatus
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['LastName'] .'"'
					.',"'. $row['FirstName'] .'"'
					.',"'. ($row['CardNumber'] == ''  ? '' : $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")") .'"'
					.',"'. ($row['SelectType'] == '2' ? $this->get_groupaccess_str($row['CardID']) : $this->get_access_str($row['CardID'])) .'"'
					.',"'. EnumTable::$attrCardStatus[$row['CardStatus']] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_cardholder', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function card()
	{
		$action	= Input::get('action', 'search');
		$sql = " SELECT Card.CardNo AS CardNumber, Card.CardStatus, Card.CardType, Card.NeverExpire, Card.ActDate, Card.ExpDate, CardFormat.No AS CardFormatNo, CardFormat.Name AS CardFormatName, User.No, User.FirstName, User.MiddleName, User.LastName, User.Phone
                    FROM Card
                    LEFT OUTER JOIN CardFormat ON CardFormat.No=Card.CardFormatNo
                    LEFT OUTER JOIN User ON User.No=Card.UserNo AND User.Site=Card.Site
                    WHERE Card.Site=? ";

		$sql_count = " SELECT COUNT(*)
                        FROM Card
                        LEFT OUTER JOIN CardFormat ON CardFormat.No=Card.CardFormatNo
                        LEFT OUTER JOIN User ON User.No=Card.UserNo AND User.Site=Card.Site
                        WHERE Card.Site=? ";

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['cardnumber']	= Input::param('filter_card_cardnumber');
		$filter['cardformatno']	= Input::param('filter_card_cardformatno');
		$filter['cardformatname']	= Input::param('filter_card_cardformatname');
		$filter['cardstatus']	= Input::param('filter_card_cardstatus');
        $filter['cardtype']	    = Input::param('filter_card_cardtype');
		$filter['lastname']		= Input::param('filter_card_lastname');
		$filter['firstname']	= Input::param('filter_card_firstname');
		$filter['phone']		= Input::param('filter_card_phone');

		if( trim($filter['cardnumber']) != '' )		{	$where .= " AND Card.CardNo LIKE ?";		$params[] = Util::parse_search_string($filter['cardnumber']);		}
		if( trim($filter['cardformatno']) != '' )	{	$where .= " AND CardFormat.No = ?";			$params[] = $filter['cardformatno'];								}
		if( trim($filter['cardformatname']) != '' )	{	$where .= " AND CardFormat.Name LIKE ?";	$params[] = Util::parse_search_string($filter['cardformatname']);	}
		if( trim($filter['cardstatus']) != '' )		{	$where .= " AND Card.CardStatus = ?";	    $params[] = $filter['cardstatus'];		                            }
        if( trim($filter['cardtype']) != '' )		{	$where .= " AND Card.CardType = ?";	        $params[] = $filter['cardtype'];		                            }
		if( trim($filter['lastname']) != '' )		{	$where .= " AND User.LastName LIKE ?";		$params[] = Util::parse_search_string($filter['lastname']);			}
		if( trim($filter['firstname']) != '' )		{	$where .= " AND User.FirstName LIKE ?";		$params[] = Util::parse_search_string($filter['firstname']);		}
		if( trim($filter['phone']) != '' )			{	$where .= " AND User.Phone LIKE ?";			$params[] = Util::parse_search_string($filter['phone']);			}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Card.CardNo ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_card_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Card.CardNo ASC");
			$list->execute($params);

			$filename   = "report_card_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->cardnumber
				.','. $this->lang->report->cardformat
				.','. $this->lang->report->cardstatus
				.','. $this->lang->report->cardtype
				.','. $this->lang->report->id
				.','. $this->lang->report->lastname
				.','. $this->lang->report->firstname
				.','. $this->lang->report->phonenumber
				."\n";

			$rownum = 1;
            $today = date('Y-m-d');
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
                $CardStatus = EnumTable::$attrCardStatus[$row['CardStatus']];
                if($row['NeverExpire'] == 0) {
                    if($row['ActDate'] != '0' && $row['ActDate'] != '' && $today < date('Y-m-d', $row['ActDate'])) {
                        $CardStatus .= ' ('. $this->lang->card->inactive .')';
                    }
                    if($row['ExpDate'] != '0' && $row['ExpDate'] != '' && $today > date('Y-m-d', $row['ExpDate'])) {
                        $CardStatus .= ' ('. $this->lang->card->expired .')';
                    }
                }
				echo '"'. $rownum++ .'"'
					.',"'. ($row['CardNumber'] == ''  ? '' : $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")") .'"'
					.',"'. $row['CardFormatName'] .'"'
					.',"'. $CardStatus .'"'
					.',"'. EnumTable::$attrCardType[$row['CardType']] .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['LastName'] .'"'
					.',"'. $row['FirstName'] .'"'
					.',"'. $row['Phone'] .'"'
					.',"'. $row['NeverExpire'] .'"'
					.',"'. $row['ActDate'] .'"'
					.',"'. $row['ExpDate'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Card.CardNo ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_card', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function cardholder_accesslevel()
	{
		$action	= Input::get('action', 'search');
		$sql = " SELECT User.Site, User.No, User.FirstName, User.MiddleName, User.LastName, CardAccess.CardNumber, AccessDoor.AccessLevelName, AccessDoor.AccessLevelName, AccessDoor.DoorNo, Door.Name AS DoorName, CardAccess.CardFormatNo
                    FROM User
                    INNER JOIN ( SELECT Card.Site AS Site, Card.CardNo AS CardNumber, Card.UserNo AS UserNo, Card.SelectType AS SelectType, Card.CardFormatNo AS CardFormatNo, CardAccessLevel.AccessLevelNo AS AccessLevelNo
                                    FROM Card
                                    LEFT OUTER JOIN CardAccessLevel ON CardAccessLevel.Site=Card.Site AND CardAccessLevel.CardNo=Card.No
                                    WHERE Card.SelectType=1
                                UNION
                                    SELECT Card.Site AS Site, Card.CardNo AS CardNumber, Card.UserNo AS UserNo, Card.SelectType AS SelectType, Card.CardFormatNo AS CardFormatNo, GroupElement.ElementNo AS AccessLevelNo
                                    FROM Card
                                    LEFT OUTER JOIN CardAccessLevel ON CardAccessLevel.Site=Card.Site AND CardAccessLevel.CardNo=Card.No
                                    LEFT OUTER JOIN GroupElement ON GroupElement.Site=CardAccessLevel.Site AND GroupElement.GroupNo=CardAccessLevel.AccessLevelNo AND GroupElement.GroupKind=5
                                    WHERE Card.SelectType=2
                                ) AS CardAccess ON User.Site=CardAccess.Site AND User.No=CardAccess.UserNo
                    LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                        FROM AccessLevel
                                        LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                        WHERE AccessLevel.SelectType=1
                                    UNION
                                        SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                        FROM AccessLevel
                                        LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                        LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                        WHERE AccessLevel.SelectType=2
                                    ) AS AccessDoor ON AccessDoor.Site=CardAccess.Site AND AccessDoor.AccessLevelNo=CardAccess.AccessLevelNo
                    LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                    WHERE User.Site=? ";

		$sql_count = " SELECT COUNT(User.No)
                        FROM User
                        INNER JOIN ( SELECT Card.Site AS Site, Card.CardNo AS CardNumber, Card.UserNo AS UserNo, Card.SelectType AS SelectType, CardAccessLevel.AccessLevelNo AS AccessLevelNo
                                        FROM Card
                                        LEFT OUTER JOIN CardAccessLevel ON CardAccessLevel.Site=Card.Site AND CardAccessLevel.CardNo=Card.No
                                        WHERE Card.SelectType=1
                                    UNION
                                        SELECT Card.Site AS Site, Card.CardNo AS CardNumber, Card.UserNo AS UserNo, Card.SelectType AS SelectType, GroupElement.ElementNo AS AccessLevelNo
                                        FROM Card
                                        LEFT OUTER JOIN CardAccessLevel ON CardAccessLevel.Site=Card.Site AND CardAccessLevel.CardNo=Card.No
                                        LEFT OUTER JOIN GroupElement ON GroupElement.Site=CardAccessLevel.Site AND GroupElement.GroupNo=CardAccessLevel.AccessLevelNo AND GroupElement.GroupKind=5
                                        WHERE Card.SelectType=2
                                    ) AS CardAccess ON User.Site=CardAccess.Site AND User.No=CardAccess.UserNo
                        LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                            FROM AccessLevel
                                            LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                            WHERE AccessLevel.SelectType=1
                                        UNION
                                        SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                            FROM AccessLevel
                                            LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                            LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                            WHERE AccessLevel.SelectType=2
                                        ) AS AccessDoor ON AccessDoor.Site=CardAccess.Site AND AccessDoor.AccessLevelNo=CardAccess.AccessLevelNo
                        LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                        WHERE User.Site=? ";

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter					= array();
		$filter['no']			= Input::param('filter_cardholder_accesslevel_no');
		$filter['lastname']		= Input::param('filter_cardholder_accesslevel_lastname');
		$filter['firstname']	= Input::param('filter_cardholder_accesslevel_firstname');
		$filter['cardnumber']	= Input::param('filter_cardholder_accesslevel_cardnumber');
		$filter['accesslevelname']	= Input::param('filter_cardholder_accesslevel_accesslevelname');
		$filter['accesslevelno']	= Input::param('filter_cardholder_accesslevel_accesslevelno');
		$filter['doorno']		= Input::param('filter_cardholder_accesslevel_doorno');
		$filter['doorname']		= Input::param('filter_cardholder_accesslevel_doorname');

		if( trim($filter['no']) != '' )				{	$where .= " AND User.No LIKE ?";				$params[] = Util::parse_search_string($filter['no']);				}
		if( trim($filter['lastname']) != '' )		{	$where .= " AND User.LastName LIKE ?";			$params[] = Util::parse_search_string($filter['lastname']);			}
		if( trim($filter['firstname']) != '' )		{	$where .= " AND User.FirstName LIKE ?";			$params[] = Util::parse_search_string($filter['firstname']);		}
		if( trim($filter['cardnumber']) != '' )		{	$where .= " AND CardNumber LIKE ?";				$params[] = Util::parse_search_string($filter['cardnumber']);		}
		if( trim($filter['accesslevelname']) != '' ){	$where .= " AND AccessLevelName LIKE ?";		$params[] = Util::parse_search_string($filter['accesslevelname']);	}
		if( trim($filter['accesslevelno']) != '' )	{	$where .= " AND AccessDoor.AccessLevelNo = ?";	$params[] = $filter['accesslevelno'];								}
		if( trim($filter['doorno']) != '' )			{	$where .= " AND DoorNo LIKE ?";					$params[] = Util::parse_search_string($filter['doorno']);			}
		if( trim($filter['doorname']) != '' )		{	$where .= " AND Door.Name LIKE ?";				$params[] = Util::parse_search_string($filter['doorname']);			}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_cardholder_accesslevel_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC");
			$list->execute($params);

			$filename   = "report_cardholder_accesslevel_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->lastname
				.','. $this->lang->report->firstname
				.','. $this->lang->report->cardnumber
				.','. $this->lang->report->accesslevel
				.','. $this->lang->report->doorno
				.','. $this->lang->report->doorname
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['No'] .'"'
					.',"'. $row['LastName'] .'"'
					.',"'. $row['FirstName'] .'"'
					.',"'. ($row['CardNumber'] == ''  ? '' : $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")") .'"'
					.',"'. $row['AccessLevelName'] .'"'
					.',"'. $row['DoorNo'] .'"'
					.',"'. $row['DoorName'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			set_time_limit(0);
			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY User.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_cardholder_accesslevel', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function accesslevel_door()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT AccessDoor.Site, AccessDoor.AccessLevelNo, AccessDoor.AccessLevelName, Reader.No AS ReaderNo, Reader.Name AS ReaderName, AccessDoor.DoorNo, Door.Name AS DoorName
                        FROM (  SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                FROM AccessLevel
                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                WHERE AccessLevel.SelectType=1
                            UNION
                                SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                FROM AccessLevel
                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                WHERE AccessLevel.SelectType=2
                            ) AS AccessDoor
                        LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                        LEFT OUTER JOIN Reader ON Reader.Site=Door.Site AND Reader.HostNo=Door.HostNo AND Reader.No IN (Door.MasterReader, Door.SlaveReader)
                        WHERE AccessDoor.Site=? ";

            $sql_count = " SELECT COUNT(*)
                            FROM ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                    FROM AccessLevel
                                    LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                    WHERE AccessLevel.SelectType=1
                                UNION
                                    SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                    FROM AccessLevel
                                    LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                    LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                    WHERE AccessLevel.SelectType=2
                                 ) AS AccessDoor
                            LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                            LEFT OUTER JOIN Reader ON Reader.Site=Door.Site AND Reader.HostNo=Door.HostNo AND Reader.No IN (Door.MasterReader, Door.SlaveReader)
                            WHERE AccessDoor.Site=? ";
        }
        else{
            $sql = " SELECT AccessDoor.Site, AccessDoor.AccessLevelNo, AccessDoor.AccessLevelName, Reader.No AS ReaderNo, Reader.Name AS ReaderName, AccessDoor.DoorNo, Door.Name AS DoorName
                        FROM (  SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                FROM AccessLevel
                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                WHERE AccessLevel.SelectType=1
                            UNION
                                SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                FROM AccessLevel
                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                WHERE AccessLevel.SelectType=2
                            ) AS AccessDoor
                        LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                        JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                        LEFT OUTER JOIN Reader ON Reader.Site=Door.Site AND Reader.HostNo=Door.HostNo AND Reader.No IN (Door.MasterReader, Door.SlaveReader)
                        WHERE AccessDoor.Site=? ";

            $sql_count = " SELECT COUNT(*)
                            FROM ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, AccessLevelReader.ReaderNo AS DoorNo
                                    FROM AccessLevel
                                    LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                    WHERE AccessLevel.SelectType=1
                                UNION
                                    SELECT AccessLevel.Site AS Site, AccessLevel.No AS AccessLevelNo, AccessLevel.Name AS AccessLevelName, AccessLevel.SelectType AS SelectType, GroupElement.ElementNo AS DoorNo
                                    FROM AccessLevel
                                    LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                    LEFT OUTER JOIN GroupElement ON GroupElement.Site=AccessLevelReader.Site AND GroupElement.GroupNo=AccessLevelReader.ReaderNo AND GroupKind=2
                                    WHERE AccessLevel.SelectType=2
                                 ) AS AccessDoor
                            LEFT OUTER JOIN Door ON Door.Site=AccessDoor.Site AND Door.No=AccessDoor.DoorNo
                            JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            LEFT OUTER JOIN Reader ON Reader.Site=Door.Site AND Reader.HostNo=Door.HostNo AND Reader.No IN (Door.MasterReader, Door.SlaveReader)
                            WHERE AccessDoor.Site=? ";
        }

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter						= array();
		$filter['accesslevelno']	= Input::param('filter_accesslevel_door_accesslevelno');
		$filter['accesslevelname']	= Input::param('filter_accesslevel_door_accesslevelname');
		$filter['readerno']			= Input::param('filter_accesslevel_door_readerno');
        $filter['readername']		= Input::param('filter_accesslevel_door_readername');
        $filter['doorno']			= Input::param('filter_accesslevel_door_doorno');
        $filter['doorname']			= Input::param('filter_accesslevel_door_doorname');

		if( trim($filter['accesslevelno']) != '' )	{	$where .= " AND AccessDoor.AccessLevelNo LIKE ?";	$params[] = Util::parse_search_string($filter['accesslevelno']);	}
		if( trim($filter['accesslevelname']) != '' ){	$where .= " AND AccessLevelName LIKE ?";			$params[] = Util::parse_search_string($filter['accesslevelname']);	}
		if( trim($filter['readerno']) != '' )		{	$where .= " AND Reader.No LIKE ?";					$params[] = Util::parse_search_string($filter['readerno']);			}
        if( trim($filter['readername']) != '' )		{	$where .= " AND Reader.Name LIKE ?";				$params[] = Util::parse_search_string($filter['readername']);		}
		if( trim($filter['doorno']) != '' )			{	$where .= " AND DoorNo LIKE ?";						$params[] = Util::parse_search_string($filter['doorno']);			}
		if( trim($filter['doorname']) != '' )		{	$where .= " AND Door.Name LIKE ?";					$params[] = Util::parse_search_string($filter['doorname']);			}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AccessDoor.AccessLevelNo ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_accesslevel_door_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AccessDoor.AccessLevelNo ASC");
			$list->execute($params);

			$filename   = "report_accesslevel_door_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->accesslevel
                .','. $this->lang->report->readerno
				.','. $this->lang->report->readername
				.','. $this->lang->report->doorno
				.','. $this->lang->report->doorname
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['AccessLevelNo'] .'"'
					.',"'. $row['AccessLevelName'] .'"'
					.',"'. $row['ReaderNo'] .'"'
                    .',"'. $row['ReaderName'] .'"'
					.',"'. $row['DoorNo'] .'"'
					.',"'. $row['DoorName'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY AccessDoor.AccessLevelNo ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_accesslevel_door', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function doorgroup()
	{
		$action	= Input::get('action', 'search');
        if( $this->is_SuperAdmin() == true )
        {
            $sql = " SELECT GroupTable.No AS GroupNo, GroupTable.Name AS GroupName, Door.No AS DoorNo, Door.Name AS DoorName, AccessLevel.Name AS AccessLevelName
                        FROM GroupTable
                        LEFT OUTER JOIN GroupElement ON GroupElement.Site=GroupTable.Site AND GroupElement.GroupNo=GroupTable.No
                        LEFT OUTER JOIN Door ON Door.Site=GroupElement.Site AND Door.No=GroupElement.ElementNo
                        LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS No, AccessLevel.Name AS Name, AccessLevelReader.ReaderNo AS GroupNo
                                            FROM AccessLevel
                                            LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                            WHERE AccessLevel.SelectType=2
                                        ) AS AccessLevel ON AccessLevel.Site=GroupTable.Site AND AccessLevel.GroupNo=GroupTable.No
                        WHERE GroupTable.Site=? AND GroupTable.GroupKind=2 ";

            $sql_count = " SELECT COUNT(*)
                            FROM GroupTable
                            LEFT OUTER JOIN GroupElement ON GroupElement.Site=GroupTable.Site AND GroupElement.GroupNo=GroupTable.No
                            LEFT OUTER JOIN Door ON Door.Site=GroupElement.Site AND Door.No=GroupElement.ElementNo
                            LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS No, AccessLevel.Name AS Name, AccessLevelReader.ReaderNo AS GroupNo
                                                FROM AccessLevel
                                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                                WHERE AccessLevel.SelectType=2
                                            ) AS AccessLevel ON AccessLevel.Site=GroupTable.Site AND AccessLevel.GroupNo=GroupTable.No
                            WHERE GroupTable.Site=? AND GroupTable.GroupKind=2 ";
        }
        else{
            $sql = " SELECT GroupTable.No AS GroupNo, GroupTable.Name AS GroupName, Door.No AS DoorNo, Door.Name AS DoorName, AccessLevel.Name AS AccessLevelName
                        FROM GroupTable
                        LEFT OUTER JOIN GroupElement ON GroupElement.Site=GroupTable.Site AND GroupElement.GroupNo=GroupTable.No
                        LEFT OUTER JOIN Door ON Door.Site=GroupElement.Site AND Door.No=GroupElement.ElementNo
                        JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                        LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS No, AccessLevel.Name AS Name, AccessLevelReader.ReaderNo AS GroupNo
                                            FROM AccessLevel
                                            LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                            WHERE AccessLevel.SelectType=2
                                        ) AS AccessLevel ON AccessLevel.Site=GroupTable.Site AND AccessLevel.GroupNo=GroupTable.No
                        WHERE GroupTable.Site=? AND GroupTable.GroupKind=2 ";

            $sql_count = " SELECT COUNT(*)
                            FROM GroupTable
                            LEFT OUTER JOIN GroupElement ON GroupElement.Site=GroupTable.Site AND GroupElement.GroupNo=GroupTable.No
                            LEFT OUTER JOIN Door ON Door.Site=GroupElement.Site AND Door.No=GroupElement.ElementNo
                            JOIN Host ON Door.HostNo = Host.No AND (Host.ByAdminId = '".$_SESSION['spider_id']."' OR Host.ByAdminId = '' OR Host.ByAdminId IS null)
                            LEFT OUTER JOIN ( SELECT AccessLevel.Site AS Site, AccessLevel.No AS No, AccessLevel.Name AS Name, AccessLevelReader.ReaderNo AS GroupNo
                                                FROM AccessLevel
                                                LEFT OUTER JOIN AccessLevelReader ON AccessLevelReader.Site=AccessLevel.Site AND AccessLevelReader.AccessLevelNo=AccessLevel.No
                                                WHERE AccessLevel.SelectType=2
                                            ) AS AccessLevel ON AccessLevel.Site=GroupTable.Site AND AccessLevel.GroupNo=GroupTable.No
                            WHERE GroupTable.Site=? AND GroupTable.GroupKind=2 ";
        }
                        
		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter						= array();
		$filter['groupno']			= Input::param('filter_doorgroup_groupno');
		$filter['groupname']		= Input::param('filter_doorgroup_groupname');
		$filter['doorno']			= Input::param('filter_doorgroup_doorno');
		$filter['doorname']			= Input::param('filter_doorgroup_doorname');
		$filter['accesslevelname']	= Input::param('filter_doorgroup_accesslevelname');
		$filter['accesslevelno']	= Input::param('filter_doorgroup_accesslevelno');

		if( trim($filter['groupno']) != '' )		{	$where .= " AND GroupTable.No LIKE ?";		$params[] = Util::parse_search_string($filter['groupno']);			}
		if( trim($filter['groupname']) != '' )		{	$where .= " AND GroupTable.Name LIKE ?";	$params[] = Util::parse_search_string($filter['groupname']);		}
		if( trim($filter['doorno']) != '' )			{	$where .= " AND Door.No LIKE ?";			$params[] = Util::parse_search_string($filter['doorno']);			}
		if( trim($filter['doorname']) != '' )		{	$where .= " AND Door.Name LIKE ?";			$params[] = Util::parse_search_string($filter['doorname']);			}
		if( trim($filter['accesslevelname']) != '' ){	$where .= " AND AccessLevelName LIKE ?";	$params[] = Util::parse_search_string($filter['accesslevelname']);	}
		if( trim($filter['accesslevelno']) != '' )	{	$where .= " AND AccessLevel.No = ?";		$params[] = $filter['accesslevelno'];								}

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY GroupTable.No ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_doorgroup_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY GroupTable.No ASC");
			$list->execute($params);

			$filename   = "report_doorgroup_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->no
				.','. $this->lang->report->id
				.','. $this->lang->report->groupname
				.','. $this->lang->report->doorno
				.','. $this->lang->report->doorname
				.','. $this->lang->report->accesslevel
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. $rownum++ .'"'
					.',"'. $row['GroupNo'] .'"'
					.',"'. $row['GroupName'] .'"'
					.',"'. $row['DoorNo'] .'"'
					.',"'. $row['DoorName'] .'"'
					.',"'. $row['AccessLevelName'] .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY GroupTable.No ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_doorgroup', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function occupancy()
	{
		$action	= Input::get('action', 'search');
		$sql = " SELECT Occupancy.Date, RegionFrom.Name AS FromRegionName, RegionTo.Name AS ToRegionName, User.FirstName, User.MiddleName, User.LastName, Card.CardNo AS CardNumber, CardFormat.FacilityCode AS FacilityCode
                    FROM Occupancy
                    LEFT OUTER JOIN Region AS RegionFrom ON RegionFrom.Site=Occupancy.Site AND RegionFrom.No=Occupancy.FromRegion
                    LEFT OUTER JOIN Region AS RegionTo ON RegionTo.Site=Occupancy.Site AND RegionTo.No=Occupancy.ToRegionNo
                    LEFT OUTER JOIN User ON User.Site=Occupancy.Site AND User.No=Occupancy.UserNo
                    LEFT OUTER JOIN Card ON Card.Site=Occupancy.Site AND Card.No=Occupancy.CardNo
                    LEFT OUTER JOIN CardFormat ON CardFormat.No=Card.CardFormatNo
                    WHERE Occupancy.EntryType IN (2, 3) AND Occupancy.Site = ? ";

		$sql_count = " SELECT COUNT(*)
                        FROM Occupancy
                        WHERE Occupancy.EntryType IN (2, 3) AND Occupancy.Site = ? ";

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter						= array();
		$filter['regionno']			= Input::param('filter_occupancy_regionno');
        $filter_regions             = $this->get_child_region($filter['regionno']);

		if( trim($filter['regionno']) != '' )	{
            $where .= " AND Occupancy.ToRegionNo IN (". implode(',', $filter_regions) .")";
        }

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Occupancy.Date ASC");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_occupancy_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Occupancy.Date ASC");
			$list->execute($params);

			$filename   = "report_occupancy_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->date
				.','. $this->lang->report->region
				.','. $this->lang->report->cardholder
				.','. $this->lang->report->cardnumber
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
				echo '"'. date('m-d-Y H:i:s', $row['Date']) .'"'
					.',"'. $row['ToRegionName'] .'"'
					.',"'. $row['FirstName'] .' '. $row['LastName'] .'"'
                    .',"'. ($row['CardNumber'] == ''  ? '' : $row['CardNumber']."(".$row['FacilityCode'].")") .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} ORDER BY Occupancy.Date ASC LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_occupancy', false);
		}
	}

	// ----------------------------------------------------------------------------------

	function Muster()
	{
		$action	= Input::get('action', 'search');
		$sql = " SELECT Muster.MuTagDate, Muster.PrRegionNo, Muster.MuRegionNo, RegionPrev.Name AS PrevRegionName, RegionMuster.Name AS MusterRegionName, User.FirstName, User.MiddleName, User.LastName, Card.CardNo AS CardNumber, CardFormat.FacilityCode AS FacilityCode
                    FROM Muster
                    LEFT OUTER JOIN Region AS RegionPrev ON RegionPrev.Site=Muster.Site AND RegionPrev.No=Muster.PrRegionNo
                    LEFT OUTER JOIN Region AS RegionMuster ON RegionMuster.Site=Muster.Site AND RegionMuster.No=Muster.MuRegionNo
                    LEFT OUTER JOIN User ON User.Site=Muster.Site AND User.No=Muster.UserNo
                    LEFT OUTER JOIN Card ON Card.Site=Muster.Site AND Card.No=Muster.CardNo
                    LEFT OUTER JOIN CardFormat ON CardFormat.No=Card.CardFormatNo
                    WHERE Muster.Site = ? ";

		$sql_count = " SELECT COUNT(*)
                        FROM Muster
                        WHERE Muster.Site = ? ";

		$where	= '';
		$params	= array($_SESSION['spider_site']);

		$filter						= array();
		$filter['regionno']			= Input::param('filter_muster_regionno');

		if( trim($filter['regionno']) != '' )	{
            $where .= " AND Muster.MuRegionNo = ?";
            $params[] = $filter['regionno'];
        }

		if ($action == 'preview') {
			$list	= $this->conn->prepare("{$sql} {$where}");
			$list->execute($params);

			$vars = array();
			$vars['list']	= $list;
			$vars['rownum']	= 1;

			set_time_limit(0);
			$this->display($vars, 'report_muster_preview', 'none');

		} else if ($action == 'download') {
			$list	= $this->conn->prepare("{$sql} {$where}");
			$list->execute($params);

			$filename   = "report_muster_" . date("YmdHis") .".csv";

			set_time_limit(0);
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Transfer-Encoding: Binary");
			header("Pragma: no-cache; public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");

			echo $this->lang->report->date
			    .','. $this->lang->report->musterregion
				.','. $this->lang->report->prevregion
				.','. $this->lang->report->cardholder
				.','. $this->lang->report->cardnumber
				."\n";

			$rownum = 1;
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
                if( $row['PrRegionNo'] == '' || $row['PrRegionNo'] == '0' ) {
                    $row['PrevRegionName'] = $this->lang->door->uncontrolled_space;
                }

				echo '"'. date('m-d-Y H:i:s', $row['MuTagDate']) .'"'
					.',"'. $row['MusterRegionName'] .'"'
					.',"'. $row['PrevRegionName'] .'"'
					.',"'. $row['FirstName'] .' '. $row['LastName'] .'"'
                    .',"'. ($row['CardNumber'] == ''  ? '' : $row['CardNumber']."(".$row['FacilityCode'].")") .'"'
					."\n";
			}

		} else {
			$page	= Input::get('page', 1);

			$count	= $this->conn->prepare("{$sql_count} {$where}");
			$count->execute($params);
			$count	= $count->fetchColumn();

			$pagination		= new Pagination();
			$page_config	= $GLOBALS['page_config'];
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$params[]	= $pagination->offset;
			$params[]	= $pagination->row_size;

			$list	= $this->conn->prepare("{$sql} {$where} LIMIT ?, ?");
			$list->execute($params);

			$vars = array();
			$vars['page']	= $page;
			$vars['count']	= $count;
			$vars['list']	= $list;
			$vars['pages']	= $pagination->get_pages();
			$vars['rownum']	= $pagination->offset + 1;

			$this->display($vars, 'report_muster', false);
		}
	}

	// ----------------------------------------------------------------------------------

    function to_array_floor()
    {
        $list = $this->conn->prepare("SELECT * FROM Floor WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr  = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]  = $val['Name'];
        }

        return $arr;
    }

	// ----------------------------------------------------------------------------------

    function to_array_cardformat()
    {
        $list = $this->conn->prepare("SELECT * FROM CardFormat");
        $list->execute();
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr  = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]  = $val['Name'];
        }

        return $arr;
    }

	// ----------------------------------------------------------------------------------

    function to_array_accesslevel()
    {
        $list = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr  = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]  = $val['Name'];
        }

        return $arr;
    }

	// ----------------------------------------------------------------------------------

    function to_array_without_muster_regions()
    {
        $list = $this->conn->prepare("SELECT * FROM Region WHERE Site=? AND OnlyMuster != 1");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr  = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]  = $val['Name'];
        }

        return $arr;
    }

	// ----------------------------------------------------------------------------------

    function to_array_muster_regions()
    {
        $list = $this->conn->prepare("SELECT * FROM Region WHERE Site=? AND OnlyMuster=1");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr  = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]  = $val['Name'];
        }

        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function get_groupaccess_str($No)
    {
        $arr    = array();

        $list   = $this->conn->prepare("SELECT * FROM CardAccessLevel WHERE Site=? AND CardNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site'],ConstTable::GROUP_ACCESS_LEVEL));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    function get_access_str($No)
    {
        $arr    = array();

        $list   = $this->conn->prepare("SELECT * FROM CardAccessLevel WHERE Site=? AND CardNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    function get_child_region($No)
    {
        $arr    = array($No);

        $list   = $this->conn->prepare("SELECT No FROM Region WHERE Site=? AND ParentNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $children = $this->get_child_region($val['No']);
            $arr = array_merge($arr, $children);
        }

        return $arr;
    }

	// ----------------------------------------------------------------------------------

}

?>