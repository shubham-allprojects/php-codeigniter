<?php

namespace App\Controllers;

class Holiday extends BaseController
{

    // ----------------------------------------------------------------------------------

    public function index()
    {
		$year_list = array();
		$year = date('Y') - 1;
		for( $i=0; $i<11; $i++ ) {
			$year_list[$year] = $year;
			$year++;
		}

		$vars = array();
		$vars['year_list'] = $year_list;
        $vars['sharedMethods'] = $this;

        if($this->input::get('wizard') == '1' )	
			$this->display($vars, 'wizard/holiday', ['header' => 'css', 'footer' => '']);
		else								
		$this->display($vars, 'wizard/holiday', ['header' => 'header', 'footer' => 'footer']);
    }

    // ----------------------------------------------------------------------------------

    public function select()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
        $page   = $this->input::get('p', 1);
        $view   = $this->input::get('v');
		$year   = $this->input::get('year', date('Y'));

		$search_year_start = $this->to_timestamp("01/01/{$year}", 0, 0, 0);
		$search_year_end = $this->to_timestamp("12/31/{$year}", 23, 59, 59);

        $page_config    = PAGE_CONFIG;
        $pagination     = $this->pagination;

        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM Holiday WHERE Site=? AND StartTime BETWEEN ? AND ?");
            $count->execute(array($_SESSION['spider_site'], $search_year_start, $search_year_end));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM Holiday WHERE Site=? AND StartTime BETWEEN ? AND ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $search_year_start, $search_year_end, $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM Holiday WHERE Site=? AND StartTime BETWEEN ? AND ? AND $field LIKE ?");
            $count->execute(array($_SESSION['spider_site'], $search_year_start, $search_year_end, $this->util::parse_search_string($word)));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM Holiday WHERE Site=? AND StartTime BETWEEN ? AND ? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $search_year_start, $search_year_end, $this->util::parse_search_string($word), $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        foreach( $list as $key=>$val )
        {
            $val['StartTimeName'] = (empty($val['StartTime']) ? '' : date("m/d/Y", $val['StartTime']));
            $val['EndTimeName']   = (empty($val['EndTime']) ? '' : date("m/d/Y", $val['EndTime']));
            $val['HolidayName1']  = $this->enumtable::$attrYesNo[$val['Holiday1']];
            $val['HolidayName2']  = $this->enumtable::$attrYesNo[$val['Holiday2']];
            $val['HolidayName3']  = $this->enumtable::$attrYesNo[$val['Holiday3']];
            $val['HolidayName4']  = $this->enumtable::$attrYesNo[$val['Holiday4']];

			$tmp_holiday = array();
			if( $val['Holiday1'] == '1' )	$tmp_holiday[] = '1';
			if( $val['Holiday2'] == '1' )	$tmp_holiday[] = '2';
			if( $val['Holiday3'] == '1' )	$tmp_holiday[] = '3';
			if( $val['Holiday4'] == '1' )	$tmp_holiday[] = '4';
			if( count($tmp_holiday) > 0 )
	            $val['HolidayGroupStr']  = 'Holiday Group '.implode(', ', $tmp_holiday);
			else
				$val['HolidayGroupStr']  = '';

            $list[$key] = $val;
        }

        $result['field']    = $field;
        $result['word']     = $word;
        $result['page']     = $page;
        $result['view']     = $view;
        $result['pages']    = $pagination->get_pages();
        $result['count']    = $count;
        $result['list']     = $list;

        echo json_encode($result);
    }

	// ----------------------------------------------------------------------------------

	public function exists_name($No)
	{
		$Name	= $this->input::post('Name');
		$year   = $this->input::get('year', date('Y'));

		$search_year_start = $this->to_timestamp("01/01/{$year}", 0, 0, 0);
		$search_year_end = $this->to_timestamp("12/31/{$year}", 23, 59, 59);

		$count	= $this->conn->prepare("SELECT COUNT(*) FROM Holiday WHERE No != ? AND Name=? AND StartTime BETWEEN ? AND ?");
		$count->execute(array($No, $Name, $search_year_start, $search_year_end));
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	// ----------------------------------------------------------------------------------

	public function exists_data($No)
	{
        $StartTime = $this->to_timestamp($this->input::post('StartTimeName', date('m/d/Y')));
        $EndTime   = $this->to_timestamp($this->input::post('EndTimeName', date('m/d/Y'))) + 24*60*60 -1;
        $Holiday1  = $this->input::post('Holiday1', 0);
        $Holiday2  = $this->input::post('Holiday2', 0);
        $Holiday3  = $this->input::post('Holiday3', 0);
        $Holiday4  = $this->input::post('Holiday4', 0);

		$count	= $this->conn->prepare("SELECT COUNT(*) FROM Holiday WHERE No != ? AND StartTime=? AND EndTime=? AND Holiday1=? AND Holiday2=? AND Holiday3=? AND Holiday4=?");
		$count->execute( array($No, $StartTime, $EndTime, $Holiday1, $Holiday2, $Holiday3, $Holiday4) );
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

    // ----------------------------------------------------------------------------------

    public function insert()
    {
    	$max_holiday  = $this->util::GetLimitCount(MAX_HOLIDAY_1,
		                                    MAX_HOLIDAY_2,
        		                            MAX_HOLIDAY_3);

        $StartTime = $this->input::post('StartTimeName');
		if( empty($StartTime) ) {
			$this->util::alert($this->lang->addmsg->error_required_starttime, TRUE);
		} elseif(!preg_match('/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $StartTime)) {
			$this->util::alert($this->lang->addmsg->error_invalid_date_format, TRUE);
		}
        $EndTime = $this->input::post('EndTimeName');
		if( empty($EndTime) ) {
			$this->util::alert($this->lang->addmsg->error_required_endtime, TRUE);
		} elseif(!preg_match('/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $EndTime)) {
			$this->util::alert($this->lang->addmsg->error_invalid_date_format, TRUE);
		}

		$StartTimeSpit = explode('/', $StartTime);
		$year_start = $this->to_timestamp("01/01/{$StartTimeSpit[2]}", 0, 0, 0);
		$year_end = $this->to_timestamp("12/31/{$StartTimeSpit[2]}", 23, 59, 59);

		if ($max_holiday <= $this->util::GetRecordCountSet('Holiday', "WHERE Site=? AND StartTime BETWEEN ? AND ?", array($_SESSION['spider_site'], $year_start, $year_end)))
    	{
    		$this->util::alert($this->lang->addmsg->error_limit_over_holiday, TRUE);
    	}

		if( $this->exists_name(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_name, TRUE);
		}

		if( $this->exists_data(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_data, TRUE);
		}

        $No = $this->conn->prepare("SELECT MAX(No) FROM Holiday WHERE Site=?");
        $No->execute(array($_SESSION['spider_site']));
        $No = $No->fetchColumn() + 1;

        $Name      = strip_tags(trim($this->input::post('Name')));
        $StartTime = $this->to_timestamp($StartTime);
        $EndTime   = $this->to_timestamp($EndTime) + 24*60*60 -1;
        $Holiday1  = $this->input::post('Holiday1', 0);
        $Holiday2  = $this->input::post('Holiday2', 0);
        $Holiday3  = $this->input::post('Holiday3', 0);
        $Holiday4  = $this->input::post('Holiday4', 0);

        if( empty($Name) )          $this->util::alert( $this->lang->menu->error_name_required, TRUE );
        if( empty($StartTime) )     $this->util::alert( $this->lang->holiday->error_startdate_empty, TRUE );
        if( empty($EndTime) )       $this->util::alert( $this->lang->holiday->error_enddate_empty, TRUE );
        if( $StartTime > $EndTime ) $this->util::alert( $this->lang->holiday->error_date_value, TRUE );

        $sth    = $this->conn->prepare("INSERT INTO Holiday (Site,No,Name,StartTime,EndTime,Holiday1,Holiday2,Holiday3,Holiday4) VALUES (?,?,?,?,?,?,?,?,?)");

        $values = array($_SESSION['spider_site'],$No,$Name,$StartTime,$EndTime,$Holiday1,$Holiday2,$Holiday3,$Holiday4);
        if( $sth->execute( $values ) )
        {
            $this->log::set_log_message($Name);
//          exec(SPIDER_COMM." clntdb sync");
            $this->util::js('load_list("'.base_url().'holiday-get'.'");');
            $this->util::alert( $this->lang->common->save_completed );
            //exec(SPIDER_COMM." send db");
        }
        else
        {
            $this->util::alert($this->lang->common->error_insert);
        }
    }

    // ----------------------------------------------------------------------------------

    public function update()
    {
    	$max_holiday  = $this->util::GetLimitCount(MAX_HOLIDAY_1,
		                                    MAX_HOLIDAY_2,
        		                            MAX_HOLIDAY_3);

        $StartTime = $this->input::post('StartTimeName');
		if( empty($StartTime) ) {
			$this->util::alert($this->lang->addmsg->error_required_starttime, TRUE);
		} elseif(!preg_match('([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})', $StartTime)) {
			$this->util::alert($this->lang->addmsg->error_invalid_date_format, TRUE);
		}
        $EndTime = $this->input::post('EndTimeName');
		if( empty($EndTime) ) {
			$this->util::alert($this->lang->addmsg->error_required_endtime, TRUE);
		} elseif(!preg_match('([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})', $EndTime)) {
			$this->util::alert($this->lang->addmsg->error_invalid_date_format, TRUE);
		}

		$StartTimeSpit = explode('/', $StartTime);
		$year_start = $this->to_timestamp("01/01/{$StartTimeSpit[2]}", 0, 0, 0);
		$year_end = $this->to_timestamp("12/31/{$StartTimeSpit[2]}", 23, 59, 59);

		if ($max_holiday <= $this->util::GetRecordCountSet('Holiday', "WHERE Site=? AND StartTime BETWEEN ? AND ?", array($_SESSION['spider_site'], $year_start, $year_end)))
    	{
    		$this->util::alert($this->lang->addmsg->error_limit_over_holiday, TRUE);
    	}

        $No        = $this->input::post('No');
        $Name      = strip_tags(trim($this->input::post('Name')));
        $StartTime = $this->to_timestamp($StartTime);
        $EndTime   = $this->to_timestamp($EndTime) + 24*60*60 -1;
        $Holiday1  = $this->input::post('Holiday1', 0);
        $Holiday2  = $this->input::post('Holiday2', 0);
        $Holiday3  = $this->input::post('Holiday3', 0);
        $Holiday4  = $this->input::post('Holiday4', 0);

		if( $this->exists_name($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_name, TRUE);
		}

		if( $this->exists_data($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_data, TRUE);
		}

        if( empty($Name) )          $this->util::alert( $this->lang->menu->error_name_required, TRUE );
        if( empty($StartTime) )     $this->util::alert( $this->lang->holiday->error_startdate_empty, TRUE );
        if( empty($EndTime) )       $this->util::alert( $this->lang->holiday->error_enddate_empty, TRUE );
        if( $StartTime > $EndTime ) $this->util::alert( $this->lang->holiday->error_date_value, TRUE );

        $sth    = $this->conn->prepare("UPDATE Holiday SET Name=?,StartTime=?,EndTime=?,Holiday1=?,Holiday2=?,Holiday3=?,Holiday4=? WHERE Site=? AND No=?");

        $values = array($Name,$StartTime,$EndTime,$Holiday1,$Holiday2,$Holiday3,$Holiday4,$_SESSION['spider_site'],$No);
        if( $sth->execute($values) )
        {
            $this->log::set_log_message($Name);
//          exec(SPIDER_COMM." clntdb sync");
            $this->util::js('update_list("'.$No.'");');
            $this->util::alert( $this->lang->common->save_completed );

            $query = 'UPDATE holiday SET Name="'.$Name.'",StartTime="'.$StartTime.'",EndTime="'.$EndTime.'",Holiday1="'.$Holiday1.'",Holiday2="'.$Holiday2.'",Holiday3="'.$Holiday3.'",Holiday4="'.$Holiday4.'" WHERE Site=="'.$_SESSION['spider_site'].'" AND No="'.$No.'"';
            //exec(SPIDER_COMM." send db");
        }
        else
        {
            $this->util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    public function delete()
    {
        $No             = $this->input::get('No');
        $Name           = $this->util::GetRecordName('Holiday', $No);

        $holi_del       = $this->conn->prepare("DELETE FROM Holiday WHERE Site=? AND No=?");


        if( $holi_del->execute(array($_SESSION['spider_site'], $No)) )
        {
            $this->log::set_log_message($Name);
            $this->util::alert( $this->lang->common->delete_completed );
//          $this->util::js('load_list()');


            $query = 'Delete FROM Holiday WHERE Site="'.$_SESSION['spider_site'].'" AND No="'.$No.'"';
            //exec(SPIDER_COMM." send db");
        }
        else
        {
            $this->util::alert($this->lang->common->error_delete);
        }
    }

    // ----------------------------------------------------------------------------------

    public function to_timestamp($date="", $hour=0, $min=0, $sec=0)
    {
        if ($date != "")
        {
        	$timeval = explode('/', trim($date));

        	if (count($timeval) < 3)
        	{
        		return "";
        	}

            $month  = $timeval[0];
            $day    = $timeval[1];
            $year   = $timeval[2];
            return mktime($hour, $min, $sec, $month, $day, $year);
        }
        return $date;
    }

    // ----------------------------------------------------------------------------------

}


?>