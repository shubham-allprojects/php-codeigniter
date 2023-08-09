<?php


class log_report extends controller
{
	var $read_codes = array(400, 1105022);
	var $trigger_codes = array(110315, 110316, 110314, 110328);

	function index()
	{
		$vars['is_localtime_display']        = $this->is_localtime_display();
		$this->display($vars);
	}

	function select()
	{
		$page	= Input::get('p', 1);
		$field	= Input::get('f');
		$word	= Input::get('w');
		$view	= Input::get('v');

		$page_config	= $GLOBALS['page_config'];
		$pagination	= new Pagination();

        $spider_db_path = DATABASE_DIR.'/Spider.db';
        $this->conn_log->exec("ATTACH DATABASE '{$spider_db_path}' AS Spider");
        
		if( empty($field) || empty($word) )
		{
            if( $this->is_SuperAdmin() == true )
            {
                $count	= $this->conn_log->prepare("SELECT COUNT(*) FROM log WHERE Site is NULL OR Site=0 OR Site=?");
            }
            else{
                $count	= $this->conn_log->prepare("SELECT COUNT(*) FROM   log AS A
                                                           JOIN   host AS B ON IFNULL(A.ClientMAC, '') = (CASE WHEN B.No = 1 THEN '' ELSE B.MAC END) AND (B.ByAdminId = '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                    WHERE  A.Type <> 7 AND A.Type <> 8  AND (A.Type <> 0 OR A.UserName = '".$_SESSION['spider_id']."')
                                                    AND   (A.Site is NULL OR A.Site=0 OR A.Site=?)");
            }
			$count->execute(array($_SESSION['spider_site']));
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config, 50);

            if( $this->is_SuperAdmin() == true )
            {
                $list	= $this->conn_log->prepare("SELECT No,Type,Port,EventCode,UserNo,CardNo,IFNULL(UserName,'') AS UserName,IFNULL(DeviceName,'') AS DeviceName,strftime('%m-%d-%Y %H:%M:%S',datetime(date,'unixepoch'))as dd, strftime('%m-%d-%Y %H:%M:%S',datetime(ClientDate,'unixepoch'))as LocalTime, Message, Res1 FROM log WHERE Site is NULL OR Site=0 OR Site=? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list	= $this->conn_log->prepare("SELECT A.No, A.Type, A.Port, A.EventCode, A.UserNo, A.CardNo, IFNULL(A.UserName,'') AS UserName, IFNULL(A.DeviceName,'') AS DeviceName,strftime('%m-%d-%Y %H:%M:%S',datetime(A.date,'unixepoch'))as dd, strftime('%m-%d-%Y %H:%M:%S',datetime(A.ClientDate,'unixepoch'))as LocalTime, A.Message, A.Res1 
                                                        FROM  log AS A
                                                        JOIN  host AS B ON IFNULL(A.ClientMAC, '') = (CASE WHEN B.No = 1 THEN '' ELSE B.MAC END) AND (B.ByAdminId = '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                    WHERE A.Type <> 7 AND A.Type <> 8  AND (A.Type <> 0 OR A.UserName = '".$_SESSION['spider_id']."')
                                                    AND   (A.Site is NULL OR A.Site=0 OR A.Site=?) ORDER BY A.No DESC LIMIT ?, ?");
            }
			$list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(PDO::FETCH_ASSOC);
		}
		else
		{
            if( $this->is_SuperAdmin() == true )
            {
                $count	= $this->conn_log->prepare("SELECT COUNT(*) FROM log WHERE (Site is NULL OR Site=0 OR Site=?) AND $field LIKE ?");
            }
            else{
                $count	= $this->conn_log->prepare("SELECT COUNT(*) FROM log  AS A
                                                            JOIN   host AS B ON IFNULL(A.ClientMAC, '') = (CASE WHEN B.No = 1 THEN '' ELSE B.MAC END) AND (B.ByAdminId = '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                    WHERE A.Type <> 7 AND A.Type <> 8 AND A.Type <> 0  AND (A.Type <> 0 OR A.UserName = '".$_SESSION['spider_id']."')
                                                    AND   (A.Site is NULL OR A.Site=0 OR A.Site=?) AND A.$field LIKE ?");
            }
			$count->execute(array($_SESSION['spider_site'], "%$word%"));
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config, 50);

            if( $this->is_SuperAdmin() == true )
            {
                $list	= $this->conn_log->prepare("SELECT No,Type,Port,EventCode,UserNo,CardNo,IFNULL(UserName,'') AS UserName,IFNULL(DeviceName,'') AS DeviceName,strftime('%m-%d-%Y %H:%M:%S',datetime(date,'unixepoch'))as dd, strftime('%m-%d-%Y %H:%M:%S',datetime(ClientDate,'unixepoch'))as LocalTime, Message, Res1 FROM log WHERE (Site is NULL OR Site=0 OR Site=?) AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list	= $this->conn_log->prepare("SELECT A.No, A.Type, A.Port, A.EventCode, A.UserNo, A.CardNo, IFNULL(A.UserName,'') AS UserName, IFNULL(A.DeviceName,'') AS DeviceName,strftime('%m-%d-%Y %H:%M:%S',datetime(A.date,'unixepoch'))as dd, strftime('%m-%d-%Y %H:%M:%S',datetime(A.ClientDate,'unixepoch'))as LocalTime, A.Message, A.Res1 
                                                        FROM  log AS A
                                                        JOIN  host AS B ON IFNULL(A.ClientMAC, '') = (CASE WHEN B.No = 1 THEN '' ELSE B.MAC END) AND (B.ByAdminId = '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                    WHERE A.Type <> 7 AND A.Type <> 8 AND  (A.Type <> 0 OR A.UserName = '".$_SESSION['spider_id']."')
                                                    AND  (A.Site is NULL OR A.Site=0 OR A.Site=?) AND A.$field LIKE ? ORDER BY A.No DESC LIMIT ?, ?");
            }
			$list->execute(array($_SESSION['spider_site'], "%$word%", $pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(PDO::FETCH_ASSOC);
		}

		foreach( $list as $key=>$val )
		{
			$eventcode_name = $val['EventCode'];
			$val['EventDescription']	= $this->lang->eventcode->$eventcode_name;
			if( in_array($val['EventCode'], $this->read_codes) )
			{
				if( $this->exists_master_reader($val['Port']) )
				{
					$val['EventDescription'] .= " Read In";
				}
				else if( $this->exists_slave_reader($val['Port']) )
				{
					$val['EventDescription'] .= " Read Out";
				}
			}
			if( in_array($val['EventCode'], $this->trigger_codes) )
			{
				if( $val['Message'] != '' && $val['Message'] != null )
				{
					$val['EventDescription'] .= " (Triggered by {$val['Message']})";
				}
			}
                        
                        // Condition to show Door lock/unlock message through schedule event - added by Rakesh Shetty.
                        if( ($eventcode_name == '600' || $eventcode_name == '601') && ( $val['Message'] != '' && $val['Message'] != null )){
                                $val['EventDescription'] .= " {$val['Message']}";
                        }

			if( $val['EventCode'] == '730' && $val['Res1'] > 0 )
			{
                $channel	= $this->conn->prepare("SELECT No, Name, DvrNo, Type FROM DVRChannel WHERE No=?");
                $channel->execute( array($val['Res1']) );
                if(($channel = $channel->fetch(PDO::FETCH_ASSOC)) != false) 
                {
					$val['EventDescription'] .= "&nbsp;&nbsp;<a href=\"javascript:openLogViewer('{$val['No']}', '{$val['Res1']}', '{$channel['Type']}');\"><img src=\"/img/videoclip.png\" /></a>";

                }
            }
			$list[$key]	= $val;
		}

        $this->conn_log->exec("DETACH DATABASE '{$spider_db_path}' AS Spider");
        
		$result['field']	= $field;
		$result['word']	    = $word;
		$result['page']	    = $page;
		$result['view']	    = $view;
		$result['pages']	= $pagination->get_pages();
		$result['count']	= $count;
		$result['list']		= $list;

		echo json_encode($result);
	}

	function exists_master_reader($port)
	{
		global $s_db;

		$count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE MasterReader=?");
		$count->execute(array($port));
		$count  = $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	function exists_slave_reader($port)
	{
		global $s_db;

		$count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE SlaveReader=?");
		$count->execute(array($port));
		$count  = $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

    // ----------------------------------------------------------------------------------

    function is_localtime_display() {
		$count	= $this->conn->prepare("SELECT COUNT(*) FROM Controller");
		$count->execute();
		$count	= $count->fetchColumn();
		if($count <= 1) return FALSE;

		if($_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL) return FALSE;

        return TRUE;
    }

    // ----------------------------------------------------------------------------------

}