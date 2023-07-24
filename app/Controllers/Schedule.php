<?php

namespace App\Controllers;

include APPPATH.'/libraries/Reverse.php';

class Schedule extends BaseController
{

    // ----------------------------------------------------------------------------------

    function index()
    {
        $vars['baseController'] = $this;
		if( $this->input::get('wizard') == '1' )	
        $this->display($vars, 'wizard/schedule', ['header' => 'css', 'footer' => '']);
		else								
        $this->display($vars, 'wizard/schedule', ['header' => 'header', 'footer' => 'footer']);
    }

    // ----------------------------------------------------------------------------------

    function select()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
        $page   = $this->input::get('p', 1);
        $view   = $this->input::get('v');

        $page_config    = PAGE_CONFIG;
        $pagination     = $this->pagination;

        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM Schedule WHERE Site=?");
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM Schedule WHERE Site=? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM Schedule WHERE Site=? AND $field LIKE ?");
            $count->execute(array($_SESSION['spider_site'], $this->util::parse_search_string($word)));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM Schedule WHERE Site=? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $this->util::parse_search_string($word), $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        foreach( $list as $key=>$val )
        {
            $val['sun_st']          = $this->human_2359to2400($val['sun_st']);
            $val['sun_ed']          = $this->human_2359to2400($val['sun_ed']);
            $val['mon_st']          = $this->human_2359to2400($val['mon_st']);
            $val['mon_ed']          = $this->human_2359to2400($val['mon_ed']);
            $val['tue_st']          = $this->human_2359to2400($val['tue_st']);
            $val['tue_ed']          = $this->human_2359to2400($val['tue_ed']);
            $val['wed_st']          = $this->human_2359to2400($val['wed_st']);
            $val['wed_ed']          = $this->human_2359to2400($val['wed_ed']);
            $val['thu_st']          = $this->human_2359to2400($val['thu_st']);
            $val['thu_ed']          = $this->human_2359to2400($val['thu_ed']);
            $val['fri_st']          = $this->human_2359to2400($val['fri_st']);
            $val['fri_ed']          = $this->human_2359to2400($val['fri_ed']);
            $val['sat_st']          = $this->human_2359to2400($val['sat_st']);
            $val['sat_ed']          = $this->human_2359to2400($val['sat_ed']);
            $val['hol_st']          = $this->human_2359to2400($val['hol_st']);
            $val['hol_ed']          = $this->human_2359to2400($val['hol_ed']);

            $val['sun_reverse_str'] = $this->enumtable::$attrYesNo[$val['sun_reverse']];
            $val['sun_str']         = $this->get_time_str($val['sun_reverse'], $val['sun_st'], $val['sun_ed']); 
            $val['sun_st_str']      = $this->human_time($val['sun_st']);
            $val['sun_ed_str']      = $this->human_time($val['sun_ed']);
            $val['sun_time']        = $this->to_vlaue_for_slider($val['sun_st'], $val['sun_ed']);

            $val['mon_reverse_str'] = $this->enumtable::$attrYesNo[$val['mon_reverse']];
            $val['mon_str']         = $this->get_time_str($val['mon_reverse'], $val['mon_st'], $val['mon_ed']); 
            $val['mon_st_str']      = $this->human_time($val['mon_st']);
            $val['mon_ed_str']      = $this->human_time($val['mon_ed']);
            $val['mon_time']        = $this->to_vlaue_for_slider($val['mon_st'], $val['mon_ed']);

            $val['tue_reverse_str'] = $this->enumtable::$attrYesNo[$val['tue_reverse']];
            $val['tue_str']         = $this->get_time_str($val['tue_reverse'], $val['tue_st'], $val['tue_ed']); 
            $val['tue_st_str']      = $this->human_time($val['tue_st']);
            $val['tue_ed_str']      = $this->human_time($val['tue_ed']);
            $val['tue_time']        = $this->to_vlaue_for_slider($val['tue_st'], $val['tue_ed']);

            $val['wed_reverse_str'] = $this->enumtable::$attrYesNo[$val['wed_reverse']];
            $val['wed_str']         = $this->get_time_str($val['wed_reverse'], $val['wed_st'], $val['wed_ed']);
            $val['wed_st_str']      = $this->human_time($val['wed_st']);
            $val['wed_ed_str']      = $this->human_time($val['wed_ed']);
            $val['wed_time']        = $this->to_vlaue_for_slider($val['wed_st'], $val['wed_ed']);

            $val['thu_reverse_str'] = $this->enumtable::$attrYesNo[$val['thu_reverse']];
            $val['thu_str']         = $this->get_time_str($val['thu_reverse'], $val['thu_st'], $val['thu_ed']);
            $val['thu_st_str']      = $this->human_time($val['thu_st']);
            $val['thu_ed_str']      = $this->human_time($val['thu_ed']);
            $val['thu_time']        = $this->to_vlaue_for_slider($val['thu_st'], $val['thu_ed']);

            $val['fri_reverse_str'] = $this->enumtable::$attrYesNo[$val['fri_reverse']];
            $val['fri_str']         = $this->get_time_str($val['fri_reverse'], $val['fri_st'], $val['fri_ed']);
            $val['fri_st_str']      = $this->human_time($val['fri_st']);
            $val['fri_ed_str']      = $this->human_time($val['fri_ed']);
            $val['fri_time']        = $this->to_vlaue_for_slider($val['fri_st'], $val['fri_ed']);

            $val['sat_reverse_str'] = $this->enumtable::$attrYesNo[$val['sat_reverse']];
            $val['sat_str']         = $this->get_time_str($val['sat_reverse'], $val['sat_st'], $val['sat_ed']);
            $val['sat_st_str']      = $this->human_time($val['sat_st']);
            $val['sat_ed_str']      = $this->human_time($val['sat_ed']);
            $val['sat_time']        = $this->to_vlaue_for_slider($val['sat_st'], $val['sat_ed']);
            
            $val['hol_reverse_str'] = $this->enumtable::$attrYesNo[$val['hol_reverse']];
            $val['hol_str']         = $this->get_time_str($val['hol_reverse'], $val['hol_st'], $val['hol_ed']);
            $val['hol_st_str']      = $this->human_time($val['hol_st']);
            $val['hol_ed_str']      = $this->human_time($val['hol_ed']);
            $val['hol_time']        = $this->to_vlaue_for_slider($val['hol_st'], $val['hol_ed']);

            $val['HolidayName']     = $this->get_select_holiday_str($val['Holiday1'], $val['Holiday2'], $val['Holiday3'], $val['Holiday4']);

            $list[$key] = $val;
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['page']         = $page;
        $result['view']         = $view;
        $result['pages']        = $pagination->get_pages();
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }

	// ----------------------------------------------------------------------------------

	function exists_name($No)
	{
		$Name			     = $this->input::post('Name');

		$count	= $this->conn->prepare("SELECT COUNT(*) FROM Schedule WHERE No != ? AND Site=? AND Name=?");
		$count->execute(array($No, $_SESSION['spider_site'], $Name));
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	// ----------------------------------------------------------------------------------

	function exists_data($No)
	{
        $sun_reverse            = $this->input::post('sun_reverse', 0);
        list($sun_st, $sun_ed)  = $this->parseInputTime($this->input::post('sun_time', '0~0'));
        $mon_reverse            = $this->input::post('mon_reverse', 0);
        list($mon_st, $mon_ed)  = $this->parseInputTime($this->input::post('mon_time', '0~0'));
        $tue_reverse            = $this->input::post('tue_reverse', 0);
        list($tue_st, $tue_ed)  = $this->parseInputTime($this->input::post('tue_time', '0~0'));
        $wed_reverse            = $this->input::post('wed_reverse', 0);
        list($wed_st, $wed_ed)  = $this->parseInputTime($this->input::post('wed_time', '0~0'));
        $thu_reverse            = $this->input::post('thu_reverse', 0);
        list($thu_st, $thu_ed)  = $this->parseInputTime($this->input::post('thu_time', '0~0'));
        $fri_reverse            = $this->input::post('fri_reverse', 0);
        list($fri_st, $fri_ed)  = $this->parseInputTime($this->input::post('fri_time', '0~0'));
        $sat_reverse            = $this->input::post('sat_reverse', 0);
        list($sat_st, $sat_ed)  = $this->parseInputTime($this->input::post('sat_time', '0~0'));
        $hol_reverse            = $this->input::post('hol_reverse', 0);
        list($hol_st, $hol_ed)  = $this->parseInputTime($this->input::post('hol_time', '0~0'));
        $Holiday1       = $this->input::post('Holiday1', 0);
        $Holiday2       = $this->input::post('Holiday2', 0);
        $Holiday3       = $this->input::post('Holiday3', 0);
        $Holiday4       = $this->input::post('Holiday4', 0);

		$count	= $this->conn->prepare("SELECT COUNT(*) FROM Schedule WHERE No != ? AND Site=? AND sun_reverse=? AND sun_st=? AND sun_ed=? AND mon_reverse=? AND mon_st=? AND mon_ed=? AND tue_reverse=? AND tue_st=? AND tue_ed=? AND wed_reverse=? AND wed_st=? AND wed_ed=? AND thu_reverse=? AND thu_st=? AND thu_ed=? AND fri_reverse=? AND fri_st=? AND fri_ed=? AND sat_reverse=? AND sat_st=? AND sat_ed=? AND hol_reverse=? AND hol_st=? AND hol_ed=? AND Holiday1=? AND Holiday2=? AND Holiday3=? AND Holiday4=?");
		$count->execute( array($No, $_SESSION['spider_site'], $sun_reverse, $sun_st, $sun_ed, $mon_reverse, $mon_st, $mon_ed, $tue_reverse, $tue_st, $tue_ed, $wed_reverse, $wed_st, $wed_ed, $thu_reverse, $thu_st, $thu_ed, $fri_reverse, $fri_st, $fri_ed, $sat_reverse, $sat_st, $sat_ed, $hol_reverse, $hol_st, $hol_ed, $Holiday1, $Holiday2, $Holiday3, $Holiday4) );
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

    // ----------------------------------------------------------------------------------

    function insert()
    {
    	//$max_schedule = $this->util::GetLimitCount(ConstTable::MAX_SCHEDULE_1, 
		//                                    ConstTable::MAX_SCHEDULE_2, 
        //		                            ConstTable::MAX_SCHEDULE_3);
		$max_schedule	= $this->enumtable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][12];
        		           
		if ($max_schedule <= $this->util::GetRecordCount('Schedule'))
    	{
    		$this->util::alert($this->lang->addmsg->error_limit_over_schedule, TRUE);
    	}

		if( $this->exists_name(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_name, TRUE);
		}

		if( $this->exists_data(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_data, TRUE);
		}

        $No = $this->conn->prepare("SELECT MAX(No) FROM Schedule WHERE Site=?");
        $No->execute(array($_SESSION['spider_site']));
        $No = $No->fetchColumn() + 1;
        
        $Name           = strip_tags(trim($this->input::post('Name')));
        $Name           = str_replace("'", "", $Name);
        $Mean           = strip_tags($this->input::post('Mean'));
        $Mean           = str_replace("'", "", $Mean);
        $sun_reverse            = $this->input::post('sun_reverse', 0);
        list($sun_st, $sun_ed)  = $this->parseInputTime($this->input::post('sun_time', '0~0'));
        $mon_reverse            = $this->input::post('mon_reverse', 0);
        list($mon_st, $mon_ed)  = $this->parseInputTime($this->input::post('mon_time', '0~0'));
        $tue_reverse            = $this->input::post('tue_reverse', 0);
        list($tue_st, $tue_ed)  = $this->parseInputTime($this->input::post('tue_time', '0~0'));
        $wed_reverse            = $this->input::post('wed_reverse', 0);
        list($wed_st, $wed_ed)  = $this->parseInputTime($this->input::post('wed_time', '0~0'));
        $thu_reverse            = $this->input::post('thu_reverse', 0);
        list($thu_st, $thu_ed)  = $this->parseInputTime($this->input::post('thu_time', '0~0'));
        $fri_reverse            = $this->input::post('fri_reverse', 0);
        list($fri_st, $fri_ed)  = $this->parseInputTime($this->input::post('fri_time', '0~0'));
        $sat_reverse            = $this->input::post('sat_reverse', 0);
        list($sat_st, $sat_ed)  = $this->parseInputTime($this->input::post('sat_time', '0~0'));
        $hol_reverse            = $this->input::post('hol_reverse', 0);
        list($hol_st, $hol_ed)  = $this->parseInputTime($this->input::post('hol_time', '0~0'));
        $Holiday1       = $this->input::post('Holiday1', 0);
        $Holiday2       = $this->input::post('Holiday2', 0);
        $Holiday3       = $this->input::post('Holiday3', 0);
        $Holiday4       = $this->input::post('Holiday4', 0);
        
        if( empty($Name) )      $this->util::alert( $this->lang->menu->error_name_required, TRUE );
        if( $sun_st > $sun_ed ) $this->util::alert( $this->lang->schedule->sun_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $mon_st > $mon_ed ) $this->util::alert( $this->lang->schedule->mon_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $tue_st > $tue_ed ) $this->util::alert( $this->lang->schedule->tue_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $wed_st > $wed_ed ) $this->util::alert( $this->lang->schedule->wed_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $thu_st > $thu_ed ) $this->util::alert( $this->lang->schedule->thu_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $fri_st > $fri_ed ) $this->util::alert( $this->lang->schedule->fri_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $sat_st > $sat_ed ) $this->util::alert( $this->lang->schedule->sat_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $hol_st > $hol_ed ) $this->util::alert( $this->lang->schedule->hol_long." : ".$this->lang->holiday->error_date_value, TRUE );
        
        if( $this->input::post('sun_st_h') < 0 || $this->input::post('sun_st_h') > 23 )   $this->util::alert( $this->lang->schedule->sun_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('mon_st_h') < 0 || $this->input::post('mon_st_h') > 23 )   $this->util::alert( $this->lang->schedule->mon_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('tue_st_h') < 0 || $this->input::post('tue_st_h') > 23 )   $this->util::alert( $this->lang->schedule->tue_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('wed_st_h') < 0 || $this->input::post('wed_st_h') > 23 )   $this->util::alert( $this->lang->schedule->wed_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('thu_st_h') < 0 || $this->input::post('thu_st_h') > 23 )   $this->util::alert( $this->lang->schedule->thu_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('fri_st_h') < 0 || $this->input::post('fri_st_h') > 23 )   $this->util::alert( $this->lang->schedule->fri_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('sat_st_h') < 0 || $this->input::post('sat_st_h') > 23 )   $this->util::alert( $this->lang->schedule->sat_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('hol_st_h') < 0 || $this->input::post('hol_st_h') > 23 )   $this->util::alert( $this->lang->schedule->hol_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
                                                    
        if( $this->input::post('sun_st_m') < 0 || $this->input::post('sun_st_m') > 59 )   $this->util::alert( $this->lang->schedule->sun_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('mon_st_m') < 0 || $this->input::post('mon_st_m') > 59 )   $this->util::alert( $this->lang->schedule->mon_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('tue_st_m') < 0 || $this->input::post('tue_st_m') > 59 )   $this->util::alert( $this->lang->schedule->tue_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('wed_st_m') < 0 || $this->input::post('wed_st_m') > 59 )   $this->util::alert( $this->lang->schedule->wed_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('thu_st_m') < 0 || $this->input::post('thu_st_m') > 59 )   $this->util::alert( $this->lang->schedule->thu_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('fri_st_m') < 0 || $this->input::post('fri_st_m') > 59 )   $this->util::alert( $this->lang->schedule->fri_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('sat_st_m') < 0 || $this->input::post('sat_st_m') > 59 )   $this->util::alert( $this->lang->schedule->sat_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('hol_st_m') < 0 || $this->input::post('hol_st_m') > 59 )   $this->util::alert( $this->lang->schedule->hol_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );

        if( $this->input::post('sun_ed_h') < 0 || $this->input::post('sun_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->sun_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('mon_ed_h') < 0 || $this->input::post('mon_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->mon_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('tue_ed_h') < 0 || $this->input::post('tue_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->tue_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('wed_ed_h') < 0 || $this->input::post('wed_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->wed_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('thu_ed_h') < 0 || $this->input::post('thu_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->thu_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('fri_ed_h') < 0 || $this->input::post('fri_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->fri_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('sat_ed_h') < 0 || $this->input::post('sat_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->sat_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('hol_ed_h') < 0 || $this->input::post('hol_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->hol_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
                                                    
        if( $this->input::post('sun_ed_m') < 0 || $this->input::post('sun_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->sun_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('mon_ed_m') < 0 || $this->input::post('mon_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->mon_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('tue_ed_m') < 0 || $this->input::post('tue_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->tue_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('wed_ed_m') < 0 || $this->input::post('wed_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->wed_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('thu_ed_m') < 0 || $this->input::post('thu_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->thu_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('fri_ed_m') < 0 || $this->input::post('fri_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->fri_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('sat_ed_m') < 0 || $this->input::post('sat_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->sat_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('hol_ed_m') < 0 || $this->input::post('hol_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->hol_long." : End - ".$this->lang->holiday->error_min_error, TRUE );

        $sth    = $this->conn->prepare("INSERT INTO Schedule (Site,No,Name,Mean,sun_reverse,sun_st,sun_ed,mon_reverse,mon_st,mon_ed,tue_reverse,tue_st,tue_ed,wed_reverse,wed_st,wed_ed,thu_reverse,thu_st,thu_ed,fri_reverse,fri_st,fri_ed,sat_reverse,sat_st,sat_ed,hol_reverse,hol_st,hol_ed,Holiday1,Holiday2,Holiday3,Holiday4) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $values = array($_SESSION['spider_site'],$No,$Name,$Mean,$sun_reverse,$sun_st,$sun_ed,$mon_reverse,$mon_st,$mon_ed,$tue_reverse,$tue_st,$tue_ed,$wed_reverse,$wed_st,$wed_ed,$thu_reverse,$thu_st,$thu_ed,$fri_reverse,$fri_st,$fri_ed,$sat_reverse,$sat_st,$sat_ed,$hol_reverse,$hol_st,$hol_ed,$Holiday1,$Holiday2,$Holiday3,$Holiday4);
        if( $sth->execute($values) )
        {
            $this->log::set_log_message($Name);
            $this->util::js('load_list();');
            $this->util::alert( $this->lang->common->save_completed );
            //exec(SPIDER_COMM." send db");
        }
        else
        {
            $this->util::alert($this->lang->common->error_insert);
        }
    }

    // ----------------------------------------------------------------------------------

    function update()
    {
        $No             = $this->input::post('No');
        
        $Name           = strip_tags(trim($this->input::post('Name')));
        $Name           = str_replace("'", "", $Name);
        $Mean           = strip_tags($this->input::post('Mean'));
        $Mean           = str_replace("'", "", $Mean);
        
        $sun_reverse            = $this->input::post('sun_reverse', 0);
        list($sun_st, $sun_ed)  = $this->parseInputTime($this->input::post('sun_time', '0~0'));
        $mon_reverse            = $this->input::post('mon_reverse', 0);
        list($mon_st, $mon_ed)  = $this->parseInputTime($this->input::post('mon_time', '0~0'));
        $tue_reverse            = $this->input::post('tue_reverse', 0);
        list($tue_st, $tue_ed)  = $this->parseInputTime($this->input::post('tue_time', '0~0'));
        $wed_reverse            = $this->input::post('wed_reverse', 0);
        list($wed_st, $wed_ed)  = $this->parseInputTime($this->input::post('wed_time', '0~0'));
        $thu_reverse            = $this->input::post('thu_reverse', 0);
        list($thu_st, $thu_ed)  = $this->parseInputTime($this->input::post('thu_time', '0~0'));
        $fri_reverse            = $this->input::post('fri_reverse', 0);
        list($fri_st, $fri_ed)  = $this->parseInputTime($this->input::post('fri_time', '0~0'));
        $sat_reverse            = $this->input::post('sat_reverse', 0);
        list($sat_st, $sat_ed)  = $this->parseInputTime($this->input::post('sat_time', '0~0'));
        $hol_reverse            = $this->input::post('hol_reverse', 0);
        list($hol_st, $hol_ed)  = $this->parseInputTime($this->input::post('hol_time', '0~0'));
        $Holiday1       = $this->input::post('Holiday1', 0);
        $Holiday2       = $this->input::post('Holiday2', 0);
        $Holiday3       = $this->input::post('Holiday3', 0);
        $Holiday4       = $this->input::post('Holiday4', 0);

		if( $this->exists_name($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_name, TRUE);
		}

		if( $this->exists_data($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_data, TRUE);
		}

        if( empty($Name) )      $this->util::alert( $this->lang->menu->error_name_required, TRUE );
        if( $sun_st > $sun_ed ) $this->util::alert( $this->lang->schedule->sun_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $mon_st > $mon_ed ) $this->util::alert( $this->lang->schedule->mon_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $tue_st > $tue_ed ) $this->util::alert( $this->lang->schedule->tue_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $wed_st > $wed_ed ) $this->util::alert( $this->lang->schedule->wed_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $thu_st > $thu_ed ) $this->util::alert( $this->lang->schedule->thu_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $fri_st > $fri_ed ) $this->util::alert( $this->lang->schedule->fri_long." : ".$this->lang->holiday->error_date_value, TRUE );
        if( $sat_st > $sat_ed ) $this->util::alert( $this->lang->schedule->sat_long." : ".$this->lang->holiday->error_date_value, TRUE );
		if( $hol_st > $hol_ed ) $this->util::alert( $this->lang->schedule->hol_long." : ".$this->lang->holiday->error_date_value, TRUE );
        
        if( $this->input::post('sun_st_h') < 0 || $this->input::post('sun_st_h') > 23 )   $this->util::alert( $this->lang->schedule->sun_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('mon_st_h') < 0 || $this->input::post('mon_st_h') > 23 )   $this->util::alert( $this->lang->schedule->mon_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('tue_st_h') < 0 || $this->input::post('tue_st_h') > 23 )   $this->util::alert( $this->lang->schedule->tue_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('wed_st_h') < 0 || $this->input::post('wed_st_h') > 23 )   $this->util::alert( $this->lang->schedule->wed_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('thu_st_h') < 0 || $this->input::post('thu_st_h') > 23 )   $this->util::alert( $this->lang->schedule->thu_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('fri_st_h') < 0 || $this->input::post('fri_st_h') > 23 )   $this->util::alert( $this->lang->schedule->fri_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('sat_st_h') < 0 || $this->input::post('sat_st_h') > 23 )   $this->util::alert( $this->lang->schedule->sat_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('hol_st_h') < 0 || $this->input::post('hol_st_h') > 23 )   $this->util::alert( $this->lang->schedule->hol_long." : Start - ".$this->lang->holiday->error_hour_error, TRUE );
                                                    
        if( $this->input::post('sun_st_m') < 0 || $this->input::post('sun_st_m') > 59 )   $this->util::alert( $this->lang->schedule->sun_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('mon_st_m') < 0 || $this->input::post('mon_st_m') > 59 )   $this->util::alert( $this->lang->schedule->mon_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('tue_st_m') < 0 || $this->input::post('tue_st_m') > 59 )   $this->util::alert( $this->lang->schedule->tue_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('wed_st_m') < 0 || $this->input::post('wed_st_m') > 59 )   $this->util::alert( $this->lang->schedule->wed_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('thu_st_m') < 0 || $this->input::post('thu_st_m') > 59 )   $this->util::alert( $this->lang->schedule->thu_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('fri_st_m') < 0 || $this->input::post('fri_st_m') > 59 )   $this->util::alert( $this->lang->schedule->fri_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('sat_st_m') < 0 || $this->input::post('sat_st_m') > 59 )   $this->util::alert( $this->lang->schedule->sat_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('hol_st_m') < 0 || $this->input::post('hol_st_m') > 59 )   $this->util::alert( $this->lang->schedule->hol_long." : Start - ".$this->lang->holiday->error_min_error, TRUE );

        if( $this->input::post('sun_ed_h') < 0 || $this->input::post('sun_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->sun_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('mon_ed_h') < 0 || $this->input::post('mon_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->mon_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('tue_ed_h') < 0 || $this->input::post('tue_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->tue_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('wed_ed_h') < 0 || $this->input::post('wed_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->wed_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('thu_ed_h') < 0 || $this->input::post('thu_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->thu_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('fri_ed_h') < 0 || $this->input::post('fri_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->fri_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('sat_ed_h') < 0 || $this->input::post('sat_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->sat_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
        if( $this->input::post('hol_ed_h') < 0 || $this->input::post('hol_ed_h') > 23 )   $this->util::alert( $this->lang->schedule->hol_long." : End - ".$this->lang->holiday->error_hour_error, TRUE );
                                                    
        if( $this->input::post('sun_ed_m') < 0 || $this->input::post('sun_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->sun_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('mon_ed_m') < 0 || $this->input::post('mon_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->mon_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('tue_ed_m') < 0 || $this->input::post('tue_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->tue_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('wed_ed_m') < 0 || $this->input::post('wed_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->wed_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('thu_ed_m') < 0 || $this->input::post('thu_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->thu_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('fri_ed_m') < 0 || $this->input::post('fri_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->fri_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('sat_ed_m') < 0 || $this->input::post('sat_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->sat_long." : End - ".$this->lang->holiday->error_min_error, TRUE );
        if( $this->input::post('hol_ed_m') < 0 || $this->input::post('hol_ed_m') > 59 )   $this->util::alert( $this->lang->schedule->hol_long." : End - ".$this->lang->holiday->error_min_error, TRUE );

        $sth    = $this->conn->prepare("UPDATE Schedule SET Name=?,mean=?,sun_reverse=?,sun_st=?,sun_ed=?,mon_reverse=?,mon_st=?,mon_ed=?,tue_reverse=?,tue_st=?,tue_ed=?,wed_reverse=?,wed_st=?,wed_ed=?,thu_reverse=?,thu_st=?,thu_ed=?,fri_reverse=?,fri_st=?,fri_ed=?,sat_reverse=?,sat_st=?,sat_ed=?,hol_reverse=?,hol_st=?,hol_ed=?,Holiday1=?,Holiday2=?,Holiday3=?,Holiday4=? WHERE Site=? AND No=?");
        $values = array($Name,$Mean,$sun_reverse,$sun_st,$sun_ed,$mon_reverse,$mon_st,$mon_ed,$tue_reverse,$tue_st,$tue_ed,$wed_reverse,$wed_st,$wed_ed,$thu_reverse,$thu_st,$thu_ed,$fri_reverse,$fri_st,$fri_ed,$sat_reverse,$sat_st,$sat_ed,$hol_reverse,$hol_st,$hol_ed,$Holiday1,$Holiday2,$Holiday3,$Holiday4,$_SESSION['spider_site'],$No);
        if( $sth->execute($values) )
        {
            $this->log::set_log_message($Name);
            $this->util::js('update_list("'.$No.'");');
            $this->util::alert( $this->lang->common->save_completed );
            //exec(SPIDER_COMM." send db");            
        }
        else
        {
            $this->util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    function check_dependency()
    {
        $no     = $this->input::get('no');

        $item   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND ScheduleNo=?");
        $item->execute(array($_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(\PDO::FETCH_ASSOC) )
		{
			$this->util::js('confirm_dependency()', TRUE);
		}

		$this->util::js('del_data_prepass()', TRUE);
    }

    // ----------------------------------------------------------------------------------

	function check_max_count()
	{
		$max_schedule	= $this->enumtable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][12];
        		           
		if ($max_schedule <= $this->util::GetRecordCount('Schedule'))
    	{
            $this->util::js('close_new();');
    		$this->util::alert($this->lang->addmsg->error_limit_over_schedule, TRUE);
    	} else {
            $this->util::js('open_new(); init_new();');
        }
	}

    // ----------------------------------------------------------------------------------

    function delete()
    {
    	$no             = $this->input::get('no');
        $Name           = $this->util::GetRecordName('Schedule', $no);
    	
    	$fields = array($_SESSION['spider_site'],$no);
    	$DataCount = $this->util::GetRecordCountSet("AccessLevel", "WHERE Site=? AND ScheduleNo=?", $fields);
    	if ($DataCount == 0)
    	{
	        $sch_del        = $this->conn->prepare("DELETE FROM Schedule WHERE Site=? AND No=?");
	        
	        if( $sch_del->execute(array($_SESSION['spider_site'],$no)) )
	        {
	            $this->log::set_log_message($Name);
                $this->util::alert( $this->lang->common->delete_completed );
	//          $this->util::js('load_list()');
	
	            $query = 'DELETE FROM Schedule WHERE Site="'.$_SESSION['spider_site'].'" AND No="'.$no.'"';
	            //exec(SPIDER_COMM." send db");        
	        }
	        else
	        {
	            $this->util::alert($this->lang->addmsg->error_delete);
	        }
	    }
	    else
		{
			$this->util::alert($this->lang->addmsg->confirm_data_delete);
		}
    }

    // ----------------------------------------------------------------------------------

    function human_time($value)
    {
        if( strlen($value) == 4 )
        {
            $h  = substr($value, 0, 2);
            $m  = substr($value, 2, 2);
            $value  = "$h:$m";
        }

        return $value;
    }

    // ----------------------------------------------------------------------------------

    function human_h($value)
    {
        if( strlen($value) == 4 )
            return substr($value, 0, 2);
        else
            return $value;
    }

    // ----------------------------------------------------------------------------------

    function human_m($value)
    {
        if( strlen($value) == 4 )
            return substr($value, 2, 2);
        else
            return $value;
    }

    // ----------------------------------------------------------------------------------
// 2017.07.07 CJMOON Bugfix NXG-2438
    function human_2359to2400($value)
    {
        //return $value == '2359' ? '2400' : $value;
		return $value;
    }

    // ----------------------------------------------------------------------------------

    function human_2400to2359($value)
    {
        return $value == '2400' ? '2359' : $value;
    }

    // ----------------------------------------------------------------------------------

    function to_vlaue_for_slider($start, $end)
    {
        $startValue = ($this->human_h($start) * 60) + $this->human_m($start);
        $endValue   = ($this->human_h($end) * 60) + $this->human_m($end);

        return "{$startValue}~{$endValue}";
    }

    // ----------------------------------------------------------------------------------

    function parseInputTime($time)
    {
        $parts = explode('~', $time);

        $start = sprintf("%02d", ($parts[0] / 60)) . sprintf("%02d", ($parts[0] % 60));
        $end   = sprintf("%02d", ($parts[1] / 60)) . sprintf("%02d", ($parts[1] % 60));

        $start = $this->human_2400to2359($start);
        $end   = $this->human_2400to2359($end);

        return array($start, $end);
    }

    // ----------------------------------------------------------------------------------

    function get_time_str($Reverse, $StartTime, $EndTime)
    {
        $retStr = "";
        
        if ($Reverse > 0)
        {
            $retStr = sprintf("~%s,%s~", $this->human_time($StartTime), $this->human_time($EndTime));
        }
        else
        {
            $retStr = sprintf("%s~%s", $this->human_time($StartTime), $this->human_time($EndTime));
        }

        return $retStr;
    }
    
	// ----------------------------------------------------------------------------------
	
    function get_select_holiday_str($Holiday1, $Holiday2, $Holiday3, $Holiday4)
    {
        $retStr = "";
        $nCount = 0;
        if ($Holiday1 > 0)
        {
            $retStr = $this->lang->schedule->hol_long."1";
            $nCount++;
        }
        if ($Holiday2 > 0)
        {
            if ($nCount > 0)
            {
                $retStr = $retStr.", 2";
            }
            else
            {
                $retStr = $this->lang->schedule->hol_long."2";
            }
            $nCount++;
        }
        if ($Holiday3 > 0)
        {
            if ($nCount > 0)
            {
                $retStr = $retStr.", 3";
            }
            else
            {
                $retStr = $this->lang->schedule->hol_long."3";
            }
            $nCount++;
        }
        if ($Holiday4 > 0)
        {
            if ($nCount > 0)
            {
                $retStr = $retStr.", 4";
            }
            else
            {
                $retStr = $this->lang->schedule->hol_long."4";
            }
            $nCount++;
        }
        
        if ($nCount < 1)
        	$retStr = $this->lang->schedule->none;
        return $retStr;
    }

}


?>
