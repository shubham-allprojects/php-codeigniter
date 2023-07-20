<?php
namespace App\Controllers;

class CardFormat extends BaseController
{
	protected $max_format;
    public function __construct()
    {
		$this->max_format = 0;
    }

	public function index()
	{
		$list = $this->defaultModel->selectAllRows('CardFormat', '*', []);
		
		$arr_card_format_default = array();
		foreach($list as $key=>$val)
		{
			$arr_card_format_default[$val['No']] = $val['Name'];
		}

		$vars['card_format_default'] = $list;
		$vars['arr_card_format_default'] = $arr_card_format_default;
		$vars['array_door'] = $this->to_array_door_scan();
		$vars['baseControllerMethods'] = $this;

		if($this->input::get('wizard') == '1' )	
			$this->display($vars, 'wizard/card_format', ['header' => 'css', 'footer' => '']);
		else								
		$this->display($vars, 'wizard/card_format', ['header' => 'header', 'footer' => 'footer']);
	}

	public function select()
	{
		$field	=$this->input::get('f');
		$word	=$this->input::get('w');
		$page	=$this->input::get('p', 1);
		$view	=$this->input::get('v');

		$page_config	= PAGE_CONFIG;
		$pagination		= $this->pagination;

		if( empty($field) || empty($word) )
		{
			$count	= $this->defaultModel->selectRowsCount('CardFormat', []);
			
			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->defaultModel->rawQuery("SELECT * FROM CardFormat ORDER BY No DESC LIMIT ".$pagination->offset.",  ".$pagination->row_size." ")->getResultArray();
		}
		else
		{
			$count	= $this->defaultModel->rawQuery("SELECT COUNT(*) FROM CardFormat WHERE $field LIKE ".$this->$this->util::parse_search_string($word)." ")->getResultArray();
			

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->defaultModel->rawQuery("SELECT * FROM CardFormat WHERE $field LIKE {$this->$this->util::parse_search_string($word)} ORDER BY No DESC LIMIT {$pagination->offset}, {$pagination->row_size}")->getResultArray();
		}

		$result['field']   = $field;
		$result['word']	   = $word;
		$result['page']	   = $page=="" ? 1 : $page;
		$result['rowsize'] = $pagination->row_size;
		$result['view']	   = $view;
		$result['pages']   = $pagination->get_pages();
		$result['count']   = $count;
		$result['list']	   = $list;

		echo json_encode($result);
	}

	// ----------------------------------------------------------------------------------

	function exists_name($No)
	{
		$Name =$this->input::post('Name');

		$count	= $this->defaultModel->rawQuery("SELECT COUNT(*) FROM CardFormat WHERE No != {$No} AND Name={$Name}")->getRowArray();
		
		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	function exists_data($No)
	{
		$TotalBitLength		 = $this->input::post('TotalBitLength');
		$FacilityCode		 = $this->input::post('FacilityCode');
		$FacilityBitLength	 = $this->input::post('FacilityBitLength');
		$FacilityStartBit	 = $this->input::post('FacilityStartBit');
		$CardNumberLength	 = $this->input::post('CardNumberLength');		
		$CardNumberStartBit	 = $this->input::post('CardNumberStartBit');
		//$EvenParityBitLength = $this->input::post('EvenParityBitLength');
		//$EvenParityStartBit	 = $this->input::post('EvenParityStartBit');
		//$OddParityBitLength	 = $this->input::post('OddParityBitLength');
		//$OddParityStartBit	 = $this->input::post('OddParityStartBit');

		//$count	= $this->defaultModel->rawQuery("SELECT COUNT(*) FROM CardFormat WHERE No != ? AND FacilityCode=? AND TotalBitLength=? AND FacilityBitLength=? AND EvenParityBitLength=? AND FacilityStartBit=? AND EvenParityStartBit=? AND CardNumberLength=? AND OddParityBitLength=? AND CardNumberStartBit=? AND OddParityStartBit=?");
		//$count->execute( array($No, $FacilityCode, $TotalBitLength, $FacilityBitLength, $EvenParityBitLength, $FacilityStartBit, $EvenParityStartBit, $CardNumberLength, $OddParityBitLength, $CardNumberStartBit, $OddParityStartBit) );
		$count	= $this->defaultModel->rawQuery("SELECT COUNT(*) FROM CardFormat WHERE No != {$No} AND FacilityCode={$FacilityCode} AND TotalBitLength={$TotalBitLength} AND FacilityBitLength={$FacilityBitLength} AND FacilityStartBit={$FacilityStartBit} AND CardNumberLength={$CardNumberLength} AND CardNumberStartBit={$CardNumberStartBit}")->getRowArray();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	// ----------------------------------------------------------------------------------

	function insert()
	{
		/*
		$this->max_format  = $this->util::GetLimitCount(ConstTable::MAX_CARDFORMAT_1, 
		                                         ConstTable::MAX_CARDFORMAT_2, 
        		                                 ConstTable::MAX_CARDFORMAT_3);		
		*/
		$this->max_format	= $this->enumtable::$attrModelSpec[$this->session->get('spider_model')][$this->session->get('spider_kind')][6];

		if ($this->max_format <= $this->util::GetRecordCount('CardFormat'))
    	{
    		$this->util::alert(lang('Message.addmsg.error_limit_over_card_format'), TRUE);
    	}

		if( $this->exists_name(-1) )
		{
			$this->util::alert(lang('Message.addmsg.error_exist_name'), TRUE);
		}

		if( $this->exists_data(-1) )
		{
			$this->util::alert(lang('Message.addmsg.error_exist_data'), TRUE);
		}

		$Name			     = strip_tags(trim($this->input::post('Name')));
		$Mean			     = strip_tags($this->input::post('Mean'));
		$TotalBitLength		 = $this->input::post('TotalBitLength');
		$FacilityCode		 = $this->input::post('FacilityCode');
		$FacilityBitLength	 = $this->input::post('FacilityBitLength');
		$FacilityStartBit	 = $this->input::post('FacilityStartBit');
		$CardNumberLength	 = $this->input::post('CardNumberLength');		
		$CardNumberStartBit	 = $this->input::post('CardNumberStartBit');
		//$EvenParityBitLength = $this->input::post('EvenParityBitLength');
		//$EvenParityStartBit	 = $this->input::post('EvenParityStartBit');
		//$OddParityBitLength	 = $this->input::post('OddParityBitLength');
		//$OddParityStartBit	 = $this->input::post('OddParityStartBit');

		if( empty($Name) )		$this->util::alert( lang('Message.card_format.error_name_required'), TRUE );
		
		if( $TotalBitLength == '' )			$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.TotalBitLength'), TRUE );
		if( $FacilityCode == '' )			$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityCode'), TRUE );
		if( $FacilityBitLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityBitLength'), TRUE );
		if( $FacilityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityStartBit'), TRUE );
		if( $CardNumberLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.CardNumberLength'), TRUE );
		if( $CardNumberStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.CardNumberStartBit'), TRUE );
		//if( $EvenParityBitLength == '' )	$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.EvenParityBitLength, TRUE );
		//if( $EvenParityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.EvenParityStartBit, TRUE );
		//if( $OddParityBitLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.OddParityBitLength, TRUE );
		//if( $OddParityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.OddParityStartBit, TRUE );		

		if( !preg_match("/^[0-9]+$/", $TotalBitLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityCode) )			$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityBitLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityStartBit) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberStartBit) )	$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );

		// TotalBitLength 범위 확인
		if( $TotalBitLength > 64 )			$this->util::alert( lang('Message.card_format.error_totalbit_less_then_equal_64'), TRUE );

		// FacilityStartBit 범위 확인
		if( $FacilityStartBit > $TotalBitLength )			$this->util::alert( sprintf( lang('Message.card_format.error_facility_start_range') , $TotalBitLength), TRUE );

		// FacilityBitLength 범위 확인
		if( $FacilityBitLength > ($TotalBitLength - $FacilityStartBit) )			$this->util::alert( sprintf( lang('Message.card_format.error_facility_length_range'), ($TotalBitLength - $FacilityStartBit)), TRUE );

		// CardNumber 가 Facility 보다 앞에 있을 경우
		if( $CardNumberStartBit < $FacilityStartBit ) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($FacilityStartBit - $CardNumberStartBit))
				$this->util::alert( sprintf( lang('Message.card_format.error_cardnumber_length_range'), ($FacilityStartBit - $CardNumberStartBit)), TRUE );

		// CardNumber 가 Facility 보다 뒤에 있을 경우
		} else if($CardNumberStartBit >= ($FacilityStartBit + $FacilityBitLength)) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($TotalBitLength - $CardNumberStartBit + 1))
				$this->util::alert( sprintf( lang('Message.card_format.error_cardnumber_length_range'), ($TotalBitLength - $CardNumberStartBit)), TRUE );
		} else {
			$this->util::alert( sprintf( lang('Message.card_format.error_cardnumber_start_duplicate'), $FacilityStartBit, ($FacilityStartBit + $FacilityBitLength)), TRUE );
		}

		$max_facility_code	= pow(2, $FacilityBitLength) - 1;

		if((int)$FacilityCode < 0 || (int)$FacilityCode > $max_facility_code) {
			$this->util::alert( lang('Message.card_format.error_facility_code_out_of_bounds'). " [0-{$max_facility_code}]", TRUE );
		}

		//$sth	= $this->defaultModel->rawQuery("INSERT INTO CardFormat (Name,Mean,FacilityCode,TotalBitLength,FacilityBitLength,EvenParityBitLength,FacilityStartBit,EvenParityStartBit,CardNumberLength,OddParityBitLength,CardNumberStartBit,OddParityStartBit) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		//$values	= array($Name,$Mean,$FacilityCode,$TotalBitLength,$FacilityBitLength,$EvenParityBitLength,$FacilityStartBit,$EvenParityStartBit,$CardNumberLength,$OddParityBitLength,$CardNumberStartBit,$OddParityStartBit);
		$sth	= [
			"Name" => $Name,
			"Mean"  => $Mean,
			"FacilityCode"  => $FacilityCode,
			"TotalBitLength"  => $TotalBitLength,
			"FacilityBitLength"  => $FacilityBitLength,
			"FacilityStartBit"  => $FacilityStartBit,
			"CardNumberLength"  => $CardNumberLength,
			"CardNumberStartBit"  => $CardNumberStartBit
		];
		$res = $this->defaultModel->insertRow("CardFormat", $sth);
		
		if( $res )
		{
			$this->log::set_log_message($Name);
			
			$this->util::js('load_list();');
            $this->util::alert( lang('Message.common.save_completed') );
		}
		else
		{
			$this->util::alert( lang('Message.common.error_insert') );
		}
	}

	function update()
	{
		$No				     = $this->input::post('No');
		$Name			     = strip_tags(trim($this->input::post('Name')));
		$Mean			     = strip_tags($this->input::post('Mean'));
		$TotalBitLength		 = $this->input::post('TotalBitLength');
		$FacilityCode		 = $this->input::post('FacilityCode');
		$FacilityBitLength	 = $this->input::post('FacilityBitLength');
		$FacilityStartBit	 = $this->input::post('FacilityStartBit');
		$CardNumberLength	 = $this->input::post('CardNumberLength');
		$CardNumberStartBit	 = $this->input::post('CardNumberStartBit');
		//$EvenParityBitLength = $this->input::post('EvenParityBitLength');
        //$EvenParityStartBit	 = $this->input::post('EvenParityStartBit');
		//$OddParityBitLength	 = $this->input::post('OddParityBitLength');
		//$OddParityStartBit	 = $this->input::post('OddParityStartBit');

		if( empty($Name) )		$this->util::alert( lang('Message.card_format.error_name_required'), TRUE );

		if( $this->exists_name($No) )
		{
			$this->util::alert( lang('Message.card_format.error_exist_card_format_name'), TRUE);
		}

		if( $this->exists_data($No) )
		{
			$this->util::alert( lang('Message.card_format.error_exist_card_format'), TRUE);
		}

		if( $TotalBitLength == '' )			$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.TotalBitLength'), TRUE );
		if( $FacilityCode == '' )			$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityCode'), TRUE );
		if( $FacilityBitLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityBitLength'), TRUE );
		if( $FacilityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.FacilityStartBit'), TRUE );
		if( $CardNumberLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.CardNumberLength'), TRUE );
		if( $CardNumberStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.CardNumberStartBit'), TRUE );
		//if( $EvenParityBitLength == '' )	$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.EvenParityBitLength, TRUE );
		//if( $EvenParityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.EvenParityStartBit, TRUE );
		//if( $OddParityBitLength == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.OddParityBitLength, TRUE );
		//if( $OddParityStartBit == '' )		$this->util::alert( lang('Message.addmsg.empty_required_item')." : ".lang('Message.card_format.OddParityStartBit, TRUE );		

		if( !preg_match("/^[0-9]+$/", $TotalBitLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityCode) )			$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityBitLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityStartBit) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberLength) )		$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberStartBit) )	$this->util::alert( lang('Message.card_format.error_only_number'), TRUE );

		// TotalBitLength 범위 확인
		if( $TotalBitLength > 64 )			$this->util::alert( lang('Message.card_format.error_totalbit_less_then_equal_64'), TRUE );

		// FacilityStartBit 범위 확인
		if( $FacilityStartBit > $TotalBitLength )			$this->util::alert( sprintf(lang('Message.card_format.error_facility_start_range'), $TotalBitLength), TRUE );

		// FacilityBitLength 범위 확인
		if( $FacilityBitLength > ($TotalBitLength - $FacilityStartBit) )			$this->util::alert( sprintf(lang('Message.card_format.error_facility_length_range'), ($TotalBitLength - $FacilityStartBit)), TRUE );

		// CardNumber 가 Facility 보다 앞에 있을 경우
		if( $CardNumberStartBit < $FacilityStartBit ) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($FacilityStartBit - $CardNumberStartBit))
				$this->util::alert( sprintf(lang('Message.card_format.error_cardnumber_length_range'), ($FacilityStartBit - $CardNumberStartBit)), TRUE );

		// CardNumber 가 Facility 보다 뒤에 있을 경우
		} else if($CardNumberStartBit >= ($FacilityStartBit + $FacilityBitLength)) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($TotalBitLength - $CardNumberStartBit + 1))
				$this->util::alert( sprintf(lang('Message.card_format.error_cardnumber_length_range'), ($TotalBitLength - $CardNumberStartBit)), TRUE );
		} else {
			$this->util::alert( sprintf(lang('Message.card_format.error_cardnumber_start_duplicate'), $FacilityStartBit, ($FacilityStartBit + $FacilityBitLength)), TRUE );
		}

		$max_facility_code	= pow(2, $FacilityBitLength) - 1;

		if((int)$FacilityCode < 0 || (int)$FacilityCode > $max_facility_code) {
			$this->util::alert( lang('Message.card_format.error_facility_code_out_of_bounds') . " [0-{$max_facility_code}]", TRUE );
		}

		$sth	= [
			"Name" => $Name,
			"Mean"  => $Mean,
			"FacilityCode"  => $FacilityCode,
			"TotalBitLength"  => $TotalBitLength,
			"FacilityBitLength"  => $FacilityBitLength,
			"FacilityStartBit"  => $FacilityStartBit,
			"CardNumberLength"  => $CardNumberLength,
			"CardNumberStartBit"  => $CardNumberStartBit
		];
		$res = $this->defaultModel->updateRow("CardFormat", ['No' => $No ], $sth);

		if( $res )
		{
			//exec(SPIDER_COMM." send db");
			$this->log::set_log_message($Name);
			$this->util::js('update_list("'.$No.'");');
            $this->util::alert( lang('Message.common.save_completed') );
		}
		else
		{
			$this->util::alert( lang('Message.common.error_update') );
		}
	}

    // ----------------------------------------------------------------------------------

	function update_default()
	{
		$no		= $this->input::get('no');

		$sth	= $this->defaultModel->updateRow("CardFormat", [], ['IsDefault' => 0]);

		$sth	= $this->defaultModel->updateRow("CardFormat", ['No' => $no], ['IsDefault' => 1]);
		
	}

    // ----------------------------------------------------------------------------------

    function check_dependency()
    {
        $no     = $this->input::get('no');

        $item   = $this->defaultModel->rawQuery("SELECT * FROM Card WHERE Site=".$this->session->get('spider_site')." AND CardFormatNo=".$no." ")->getResultArray();
        
        if( $item )
		{
			$this->util::js('confirm_dependency()', TRUE);
		}

		$this->util::js('del_data_prepass()', TRUE);
    }

	function delete()
	{
		$No		= $this->input::get('no');
        $Name   = $this->util::GetCardFormatName($No);
		
		//$fields = array($_SESSION['spider_site'],$No);
    	//$DataCount = $this->util::GetRecordCountSet("Card", "WHERE Site=? AND CardFormatNo=?", $fields);
    	//if ($DataCount == 0)
    	//{
			$sth	= $this->defaultModel->deleteRow("CardFormat",["No"=> $No]);
			if( $sth )
			{
				//exec(SPIDER_COMM." send db");
				$this->log::set_log_message($Name);
                $this->util::alert( lang('Message.common.delete_completed') );
			}
			else
			{
				$this->util::alert( lang('Message.common.error_delete') );
			}
		//}
		//else
		//{
		//	$this->util::alert($this->lang->addmsg->confirm_data_delete);
		//}
	}

    // ----------------------------------------------------------------------------------

    function to_array_door()
    {
        $door = $this->defaultModel->selectAllRows("Door","*",["Site"=> $this->session->get('spider_site')]);
    
        $arr_door  = array();
        foreach( $door as $key=>$val)
        {
            $arr_door[$val['No']]  = $val['Name'];
        }

        return $arr_door;
    }

    // ----------------------------------------------------------------------------------

    function to_array_door_scan()
    {
        //$door = $this->defaultModel->rawQuery("SELECT * FROM Door WHERE Site=? AND HostNo=1");
        $door = $door = $this->defaultModel->selectAllRows("Door","*",["Site"=> session()->get('spider_site')]);

        $arr_door  = array();
        foreach( $door as $key=>$val)
        {
            $arr_door[$val['No']]  = $val['Name'];
        }

        return $arr_door;
    }

    // ----------------------------------------------------------------------------------

    function calculate()
    {
/*
		$result = array(
			'status' => 'success',
			'facility' => '27',
			'card_number' => '2727'
		);
		echo json_encode($result);
		exit;
*/
		$params[]		= $this->input::get('BitValue');
		$params[]		= $this->input::get('TotalBitLength');
		$params[]		= $this->input::get('FacilityStartBit');
		$params[]		= $this->input::get('FacilityBitLength');
		$params[]		= $this->input::get('CardNumberStartBit');
		$params[]		= $this->input::get('CardNumberLength');
		//$params[]		= $this->input::get('EvenParityStartBit');
		//$params[]		= $this->input::get('EvenParityBitLength');
		//$params[]		= $this->input::get('OddParityStartBit');
		//$params[]		= $this->input::get('OddParityBitLength');

        exec(SPIDER_COMM." calformat ".implode(' ', $params), $output);

		$result = array();

		foreach( $output as $key=>$line )
		{
			$line = trim($line);
			$line = strtolower($line);

			if ($line == 'success')
			{
				$result['status'] = 'success';
				continue;
			}
			else if ($line == 'faileure')
			{
				$result['status'] = 'faileure';
				continue;
			}

			$temp = explode(':', $line, 2);
			if ($temp[0] == 'facility')
			{
				$result['facility'] = $temp[1];
			}
			else if ($temp[0] == 'cardnumber')
			{
				$result['card_number'] = $temp[1];
			}
		}

        echo json_encode($result);
    }

	// ----------------------------------------------------------------------------------

}


?>