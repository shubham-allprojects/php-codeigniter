<?php


class alevel extends controller
{

    // ----------------------------------------------------------------------------------

    function index()
    {
        $vars['arr_reader']         = $this->to_array_reader();
        $vars['arr_reader_group']   = $this->to_array_reader_group();
        $vars['arr_schedule']       = $this->to_array_schedule();

		if( Input::get('wizard') == '1' )	$this->display($vars, '', 'none');
		else								$this->display($vars);
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
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM AccessLevel WHERE Site = ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*)  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?");
            }
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site = ? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list  = $this->conn->prepare("SELECT Site, No, Name, Mean, ScheduleNo, SelectType  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                ORDER BY No DESC LIMIT ?, ?");
            }
            
            
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM AccessLevel WHERE Site = ? AND $field LIKE ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*)  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                AND $field LIKE ?");
            }
            
            $count->execute(array($_SESSION['spider_site'], Util::parse_search_string($word)));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site = ? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list  = $this->conn->prepare("SELECT Site, No, Name, Mean, ScheduleNo, SelectType  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                AND $field LIKE ? 
                                                ORDER BY No DESC LIMIT ?, ?");
            }
            $list->execute(array($_SESSION['spider_site'], Util::parse_search_string($word), $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        $arr_reader         = $this->to_array_reader();
        $arr_reader_group   = $this->to_array_reader_group();
        $arr_schedule       = $this->to_array_schedule();

        foreach( $list as $key=>$val )
        {
			$val['ScheduleNo']		= empty($val['ScheduleNo']) ? '1' : $val['ScheduleNo'];
			$val['SelectType']		= empty($val['SelectType']) ? '1' : $val['SelectType'];

            $val['ScheduleName']   = $arr_schedule[$val['ScheduleNo']];
            $val['SelectTypeName'] = EnumTable::$attrGroup[$val['SelectType']];
            $val['DoorList2']      = $this->get_element_select_value($val['No']);
            $val['DoorList2Str']   = $this->get_reader_str($val['No'], $val['SelectType']);
            
            $list[$key] = $val;
        }

        $result['field'] = $field;
        $result['word']  = $word;
        $result['page']  = $page;
        $result['view']  = $view;
        $result['pages'] = $pagination->get_pages();
        $result['count'] = $count;
        $result['list']  = $list;

        echo json_encode($result);
    }
    
    function find()
    {
        $field  = Input::get('f');
        $word   = Input::get('w');

        if( empty($field) || empty($word) )
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM AccessLevel WHERE Site=?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*)  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?");
            }
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=? ORDER BY No DESC");
            }
            else{
                $list  = $this->conn->prepare("SELECT Site, No, Name, Mean, ScheduleNo, SelectType  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                ORDER BY No DESC");
            }
            $list->execute(array($_SESSION['spider_site']));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM AccessLevel WHERE Site=? AND $field LIKE ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*)  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                AND $field LIKE ?");
            }
            
            $count->execute(array($_SESSION['spider_site'], "%".$word."%"));
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND $field LIKE ? ORDER BY No DESC");
            }
            else{
                $list  = $this->conn->prepare("SELECT Site, No, Name, Mean, ScheduleNo, SelectType  FROM 
                                               (SELECT Z.* From AccessLevel AS Z
                                                           JOIN Door AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 1)
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           Group By Z.No

                                                UNION	   
                                                SELECT Z.* FROM AccessLevel AS Z
                                                           JOIN GroupTable AS A ON A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site=Z.Site AND AccessLevelNo = Z.No AND Z.SelectType = 2)
                                                           JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                           JOIN Door AS C ON B.ElementNo = C.No
                                                           JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)  
                                                           GROUP BY Z.No  )
                                                WHERE Site = ?
                                                AND $field LIKE ? 
                                                ORDER BY No DESC");
            }
            $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND $field LIKE ? ORDER BY No DESC");
            $list->execute(array($_SESSION['spider_site'], "%".$word."%"));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }
    
    function elementsearch()
    {
        $field  = Input::get('f');
        $word   = Input::get('w');
        $page   = Input::get('p', 1);
        $view   = Input::get('v');

        $page_config    = $GLOBALS['page_config'];
        $pagination     = new Pagination();

        if( empty($field) || empty($word) )
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count   = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? ");
            }
            else{
                $count   = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           WHERE A.Site=? ");
            }
            
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            
            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? 
                                                           ORDER BY A.No DESC LIMIT ?, ?");
            }
            
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND $field LIKE ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           WHERE A.Site=? 
                                                           AND   A.$field LIKE ? "); 
            }
            
            $count->execute(array($_SESSION['spider_site'], "%$word%"));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            
            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                           WHERE A.Site=? 
                                                           AND   A.$field LIKE ? 
                                                           ORDER BY A.No DESC LIMIT ?, ?");
            }
            
            $list->execute(array($_SESSION['spider_site'], "%$word%", $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach( $list as $key=>$val )
        {
        	$val['PrevName']        = $val['Name'];
            $val['ElementList']     = $this->get_element_select_value($val['No']);
            $val['ElementListStr']  = $this->get_element_str($val['No']);
            
            $list[$key] = $val;
        }

        $result['field'] = $field;
        $result['word']  = $word;
        $result['page']  = $page;
        $result['view']  = $view;
        $result['pages'] = $pagination->get_pages();
        $result['count'] = $count;
        $result['list']  = $list;

        echo json_encode($result);
    }

    // ----------------------------------------------------------------------------------

    function insert()
    {
		/*
    	$max_access= Util::GetLimitCount(ConstTable::MAX_ACCESSLEVEL_1, 
		                                 ConstTable::MAX_ACCESSLEVEL_2, 
        		                         ConstTable::MAX_ACCESSLEVEL_3);
		*/
		$max_access	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][11];

		if ($max_access <= Util::GetRecordCount('AccessLevel'))
    	{
    		Util::alert($this->lang->addmsg->error_limit_over_accesslevel, TRUE);
    	}

        $No = $this->conn->prepare("SELECT MAX(No) from AccessLevel WHERE Site=?");
        $No->execute(array($_SESSION['spider_site']));
        $No    = $No->fetchColumn() + 1;
        
        $Name       = strip_tags(trim(Input::post('Name')));
        $Mean       = strip_tags(Input::post('Mean'));
        $ScheduleNo = Input::post('ScheduleNo');
        $SelectType = Input::post('SelectType');
        $DoorList2  = Input::post('DoorList2', array());

        if( empty($Name) )      Util::alert( $this->lang->alevel->error_name_required, TRUE );

		$fields = array($_SESSION['spider_site'], $Name);
        $acccount = Util::GetRecordCountSet("AccessLevel", " WHERE Site=? AND Name=?", $fields);
        if ($acccount > 0)
        {
        	Util::alert( $this->lang->addmsg->exist_access_name, TRUE );
        }

        $sth    = $this->conn->prepare("INSERT INTO AccessLevel (Site,No,Name,Mean,ScheduleNo,SelectType) VALUES (?,?,?,?,?,?)");

        $values = array($_SESSION['spider_site'],$No,$Name,$Mean,$ScheduleNo,$SelectType);
        if( $sth->execute($values) )
        {
            $ALRNo = $this->conn->prepare("SELECT MAX(No) from AccessLevelReader WHERE Site=?");
            $ALRNo->execute(array($_SESSION['spider_site']));
            $ALRNo = $ALRNo->fetchColumn() + 1;
    
            $sth    = $this->conn->prepare("INSERT INTO AccessLevelReader (Site,No,AccessLevelNo,ReaderNo) VALUES (?,?,?,?)");
            foreach( $DoorList2 as $value )
            {
                $sth->execute( array($_SESSION['spider_site'], $ALRNo, $No, $value) );
                $ALRNo = $ALRNo + 1;
            }

            //exec(SPIDER_COMM." send db");
            Log::set_log_message($Name);
            Util::js('load_list();');
            Util::alert( $this->lang->common->save_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_insert);
        }
    }

    // ----------------------------------------------------------------------------------

    function update()
    {
        $No         = Input::post('No');
        $Name       = strip_tags(trim(Input::post('Name')));
        $PrevName   = strip_tags(Input::post('PrevName'));
        $Mean       = strip_tags(Input::post('Mean'));
        $ScheduleNo = Input::post('ScheduleNo');
        $SelectType = Input::post('SelectType');
        $DoorList2  = Input::post('DoorList2', array());
        
        if( empty($Name) )      Util::alert( $this->lang->alevel->error_name_required, TRUE );
        
        $fields = array($_SESSION['spider_site'], $No, $Name);
        $acccount = Util::GetRecordCountSet("AccessLevel", " WHERE Site=? AND No != ? AND Name=?", $fields);
        if ($acccount > 0)	Util::alert( $this->lang->addmsg->exist_access_name, TRUE );

        $sth    = $this->conn->prepare("UPDATE AccessLevel SET Name=?,Mean=?,ScheduleNo=?,SelectType=? WHERE Site=? AND No=?");

        $values = array($Name,$Mean,$ScheduleNo,$SelectType,$_SESSION['spider_site'],$No);
        if( $sth->execute($values) )
        {
            $subdelete = $this->conn->prepare("DELETE FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo=?");
            $subdelete->execute(array($_SESSION['spider_site'], $No));

            $ALRNo = $this->conn->prepare("SELECT MAX(No) from AccessLevelReader WHERE Site=?");
            $ALRNo->execute(array($_SESSION['spider_site']));
            $ALRNo = $ALRNo->fetchColumn() + 1;  
        
            $subinsert = $this->conn->prepare("INSERT INTO AccessLevelReader (Site,No,AccessLevelNo,ReaderNo) VALUES (?,?,?,?)");
            foreach( $DoorList2 as $value )
            {
                $subinsert->execute( array($_SESSION['spider_site'], $ALRNo, $No, $value) );
                $ALRNo = $ALRNo + 1;
            }

            //exec(SPIDER_COMM." send db");
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

        $item   = $this->conn->prepare("SELECT * FROM AccessLevel AS A JOIN GroupElement AS B ON B.ElementNo=A.No AND B.GroupKind=? WHERE A.Site=? AND A.No=?");
        $item->execute(array(ConstTable::GROUP_ACCESS_LEVEL, $_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(PDO::FETCH_ASSOC) )
		{
			Util::js('confirm_dependency()', TRUE);
		}

        $item   = $this->conn->prepare("SELECT * FROM AccessLevel AS A JOIN CardAccessLevel AS B ON B.AccessLevelNo=A.No WHERE A.Site=? AND A.No=?");
        $item->execute(array($_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(PDO::FETCH_ASSOC) )
		{
			Util::js('confirm_dependency()', TRUE);
		}

        $item   = $this->conn->prepare("SELECT * FROM AccessLevel AS A JOIN ElevatorUser AS B ON B.UserNo=A.No AND B.SelectType=? WHERE A.Site=? AND A.No=?");
        $item->execute(array(ConstTable::INDIVIDUAL, $_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(PDO::FETCH_ASSOC) )
		{
			Util::js('confirm_dependency()', TRUE);
		}

		Util::js('del_data_prepass()', TRUE);
    }

    // ----------------------------------------------------------------------------------

    function delete()
    {
        $No         = Input::get('no');
        $Name       = Util::GetRecordName('AccessLevel', $No);
        
        $fields = array($_SESSION['spider_site'],$no);
    	//$DataCount = Util::GetRecordCountSet("AccessLevel", "WHERE Site=? AND ScheduleNo=?", $fields);
    	if ($DataCount == 0)
    	{
            $deleteok = false;
            
	        $alevelreader_del = $this->conn->prepare("DELETE FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo=?");
	        if($alevelreader_del->execute(array($_SESSION['spider_site'], $No)) )
	        {
	            $alevel_del = $this->conn->prepare("DELETE FROM AccessLevel WHERE Site = ? AND No=?");        
	            if( $alevel_del->execute(array($_SESSION['spider_site'], $No)) )
	            {
                    $group_del = $this->conn->prepare("DELETE FROM GroupElement WHERE Site=? AND GroupKind=? AND ElementNo=?");
                    if( $group_del->execute(array($_SESSION['spider_site'], ConstTable::GROUP_ACCESS_LEVEL, $No)) )
                    {
                        $deleteok = true;
                    }
                }
	        }
	        
            if ($deleteok == true)
            {
                Log::set_log_message($Name);
                Util::alert( $this->lang->common->delete_completed );
            }
            else
            {
                Util::alert($this->lang->common->error_delete);
            }
	    }
	    else
	    {
	    	Util::alert($this->lang->addmsg->confirm_data_delete);
	    }
    }

    // ----------------------------------------------------------------------------------
    
    function to_array_schedule()
    {
        $list   = $this->conn->prepare("SELECT * FROM Schedule WHERE Site=?");
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

    function to_array_reader()
    {
        
        if( $this->is_SuperAdmin() == true )
        {
            $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0'");
        }
        else{
            $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                       JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null) 
                                                       WHERE A.Site=? 
                                                       AND   A.Disable='0' "); 
        }
        
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }

        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function to_array_reader_group()
    {
        if( $this->is_SuperAdmin() == true )
        {
            $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=?");
        }
        else {
            $list   = $this->conn->prepare("SELECT A.* FROM GroupTable AS A 
                                                       JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                       JOIN Door AS C ON B.ElementNo = C.No
                                                       JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)
                                                       WHERE A.Site = ? AND A.GroupKind = ? GROUP BY A.No"); 
                       
        }
        
        $list->execute(array($_SESSION['spider_site'], ConstTable::GROUP_DOOR));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }

        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function get_reader_str($No, $SelectType)
    {
        $arr    = array();

        if ($SelectType != '2')
        {
            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Disable='0' AND No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo = ?)");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host AS B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Disable='0' AND A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo = ?)");
            }
            $list->execute(array($_SESSION['spider_site'], $No));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo = ?)");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM GroupTable AS A 
                                                       JOIN GroupElement AS B ON A.No = B.GroupNo 
                                                       JOIN Door AS C ON B.ElementNo = C.No
                                                       JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)
                                                       WHERE A.Site=? AND A.GroupKind=? AND A.No IN (SELECT ReaderNo FROM AccessLevelReader WHERE Site = ? AND AccessLevelNo = ?)  GROUP BY A.No");
                
            }
            $list->execute(array($_SESSION['spider_site'], ConstTable::GROUP_DOOR, $_SESSION['spider_site'], $No));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    function get_element_select_value($No)
    {
        $arr    = array();

        if( $this->is_SuperAdmin() == true )
        {
            $list   = $this->conn->prepare("Select No, Name FROM (SELECT C.No AS No, C.Name AS Name FROM AccessLevel AS A
                                              JOIN AccessLevelReader AS B ON A.Site = B.Site AND A.No = B.AccessLevelNo
                                              JOIN Door AS C ON B.ReaderNo = C.No AND C.Disable='0'
                                             WHERE A.SelectType = 1 AND A.Site = ? AND A.No = ?
                                            UNION
                                            SELECT C.No, C.Name FROM AccessLevel AS A
                                              JOIN AccessLevelReader AS B ON A.Site = B.Site AND A.No = B.AccessLevelNo
                                              JOIN GroupTable AS C ON C.Site = A.Site AND B.ReaderNo = C.No AND C.GroupKind=?
                                             WHERE A.SelectType = 2 AND A.Site = ? AND A.No = ?)");
        }
        else        
        {
            $list   = $this->conn->prepare("Select No, Name FROM (SELECT C.No AS No, C.Name AS Name FROM AccessLevel AS A
                                              JOIN AccessLevelReader AS B ON A.Site = B.Site AND A.No = B.AccessLevelNo
                                              JOIN Door AS C ON B.ReaderNo = C.No AND C.Disable='0'
                                              JOIN Host AS H ON C.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)
                                             WHERE A.SelectType = 1 AND A.Site = ? AND A.No = ?
                                            UNION
                                            SELECT C.No, C.Name FROM AccessLevel AS A
                                              JOIN AccessLevelReader AS B ON A.Site = B.Site AND A.No = B.AccessLevelNo
                                              JOIN GroupTable AS C ON C.Site = A.Site AND B.ReaderNo = C.No AND C.GroupKind=?
                                              JOIN GroupElement AS D ON C.No = D.GroupNo 
                                              JOIN Door AS E ON E.No = D.ElementNo 
                                              JOIN Host AS H ON E.HostNo = H.No AND (H.ByAdminId =  '".$_SESSION['spider_id']."' OR H.ByAdminId = '' OR H.ByAdminId IS null)
                                             WHERE A.SelectType = 2 AND A.Site = ? AND A.No = ?)");
            
        }
        
        $list->execute(array($_SESSION['spider_site'], $No, ConstTable::GROUP_DOOR, $_SESSION['spider_site'], $No));
    
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        return $list;
    }

    // ----------------------------------------------------------------------------------

	function check_max_count()
	{
		$max_access	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][11];

		if ($max_access <= Util::GetRecordCount('AccessLevel'))
    	{
			Util::js('close_new();');
    		Util::alert($this->lang->addmsg->error_limit_over_accesslevel);
    	} else {
			Util::js('open_new();');
    	}
	}

    // ----------------------------------------------------------------------------------
}

?>