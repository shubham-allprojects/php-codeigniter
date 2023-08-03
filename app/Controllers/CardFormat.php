<?php
namespace App\Controllers;

class CardFormat extends BaseController
{
	// ----------------------------------------------------------------------------------
	var $max_format = 0;

	public function index()
	{
		$list = $this->conn->prepare("SELECT * FROM CardFormat");
		$list->execute();
		$list = $list->fetchAll(\PDO::FETCH_ASSOC);

		$arr_card_format_default = array();
		foreach($list as $key=>$val)
		{
			$arr_card_format_default[$val['No']] = $val['Name'];
		}

		$vars['card_format_default'] = $list;
		$vars['arr_card_format_default'] = $arr_card_format_default;
		$vars['array_door'] = $this->to_array_door_scan();
		$vars['baseController'] = $this;
		

		if($this->input::get('wizard') == '1' )	
			$this->display($vars, 'wizard/card_format', ['header' => 'css', 'footer' => '']);
		else							
		$this->display($vars, 'wizard/card_format', ['header' => 'header', 'footer' => 'footer']);
	}

	// ----------------------------------------------------------------------------------

	public function select()
	{
		$field	= $this->input::get('f');
		$word	= $this->input::get('w');
		$page	= $this->input::get('p', 1);
		$view	= $this->input::get('v');

		$page_config	= PAGE_CONFIG;
		$pagination		= $this->pagination;

		if( empty($field) || empty($word) )
		{
			$count	= $this->conn->prepare("SELECT COUNT(*) FROM CardFormat");
			$count->execute();
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->conn->prepare("SELECT * FROM CardFormat ORDER BY No DESC LIMIT ?, ?");
			$list->execute(array($pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(\PDO::FETCH_ASSOC);
		}
		else
		{
			$count	= $this->conn->prepare("SELECT COUNT(*) FROM CardFormat WHERE $field LIKE ?");
			$count->execute(array($this->util::parse_search_string($word)));
			$count	= $count->fetchColumn();

			$page_config['current_page']	= $page;
			$page_config['total_row']		= $count;
			$pagination->init($page_config);

			$list	= $this->conn->prepare("SELECT * FROM CardFormat WHERE $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
			$list->execute(array($this->util::parse_search_string($word), $pagination->offset, $pagination->row_size));
			$list	= $list->fetchAll(\PDO::FETCH_ASSOC);
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

	public function exists_name($No)
	{
		$Name			     = $this->input::post('Name');

		$count	= $this->conn->prepare("SELECT COUNT(*) FROM CardFormat WHERE No != ? AND Name=?");
		$count->execute(array($No, $Name));
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	// ----------------------------------------------------------------------------------

	public function exists_data($No)
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

		//$count	= $this->conn->prepare("SELECT COUNT(*) FROM CardFormat WHERE No != ? AND FacilityCode=? AND TotalBitLength=? AND FacilityBitLength=? AND EvenParityBitLength=? AND FacilityStartBit=? AND EvenParityStartBit=? AND CardNumberLength=? AND OddParityBitLength=? AND CardNumberStartBit=? AND OddParityStartBit=?");
		//$count->execute( array($No, $FacilityCode, $TotalBitLength, $FacilityBitLength, $EvenParityBitLength, $FacilityStartBit, $EvenParityStartBit, $CardNumberLength, $OddParityBitLength, $CardNumberStartBit, $OddParityStartBit) );
		$count	= $this->conn->prepare("SELECT COUNT(*) FROM CardFormat WHERE No != ? AND FacilityCode=? AND TotalBitLength=? AND FacilityBitLength=? AND FacilityStartBit=? AND CardNumberLength=? AND CardNumberStartBit=?");
		$count->execute( array($No, $FacilityCode, $TotalBitLength, $FacilityBitLength, $FacilityStartBit, $CardNumberLength, $CardNumberStartBit) );
		$count	= $count->fetchColumn();

		if( $count > 0 )	return TRUE;
		else				return FALSE;
	}

	// ----------------------------------------------------------------------------------

	public function insert()
	{
		/*
		$this->max_format  = $this->util::GetLimitCount(ConstTable::MAX_CARDFORMAT_1, 
		                                         ConstTable::MAX_CARDFORMAT_2, 
        		                                 ConstTable::MAX_CARDFORMAT_3);		
		*/
		$this->max_format	= $this->enumtable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][6];

		if ($this->max_format <= $this->util::GetRecordCount('CardFormat'))
    	{
    		$this->util::alert($this->lang->addmsg->error_limit_over_card_format, TRUE);
    	}

		if( $this->exists_name(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_name, TRUE);
		}

		if( $this->exists_data(-1) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_data, TRUE);
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

		if( empty($Name) )		$this->util::alert( $this->lang->card_format->error_name_required, TRUE );
		
		if( $TotalBitLength == '' )			$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->TotalBitLength, TRUE );
		if( $FacilityCode == '' )			$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityCode, TRUE );
		if( $FacilityBitLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityBitLength, TRUE );
		if( $FacilityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityStartBit, TRUE );
		if( $CardNumberLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->CardNumberLength, TRUE );
		if( $CardNumberStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->CardNumberStartBit, TRUE );
		//if( $EvenParityBitLength == '' )	$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->EvenParityBitLength, TRUE );
		//if( $EvenParityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->EvenParityStartBit, TRUE );
		//if( $OddParityBitLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->OddParityBitLength, TRUE );
		//if( $OddParityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->OddParityStartBit, TRUE );		

		if( !preg_match("/^[0-9]+$/", $TotalBitLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityCode) )			$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityBitLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityStartBit) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberStartBit) )	$this->util::alert( $this->lang->card_format->error_only_number, TRUE );

		// TotalBitLength 범위 확인
		if( $TotalBitLength > 64 )			$this->util::alert( $this->lang->card_format->error_totalbit_less_then_equal_64, TRUE );

		// FacilityStartBit 범위 확인
		if( $FacilityStartBit > $TotalBitLength )			$this->util::alert( sprintf($this->lang->card_format->error_facility_start_range, $TotalBitLength), TRUE );

		// FacilityBitLength 범위 확인
		if( $FacilityBitLength > ($TotalBitLength - $FacilityStartBit) )			$this->util::alert( sprintf($this->lang->card_format->error_facility_length_range, ($TotalBitLength - $FacilityStartBit)), TRUE );

		// CardNumber 가 Facility 보다 앞에 있을 경우
		if( $CardNumberStartBit < $FacilityStartBit ) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($FacilityStartBit - $CardNumberStartBit))
				$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_length_range, ($FacilityStartBit - $CardNumberStartBit)), TRUE );

		// CardNumber 가 Facility 보다 뒤에 있을 경우
		} else if($CardNumberStartBit >= ($FacilityStartBit + $FacilityBitLength)) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($TotalBitLength - $CardNumberStartBit + 1))
				$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_length_range, ($TotalBitLength - $CardNumberStartBit)), TRUE );
		} else {
			$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_start_duplicate, $FacilityStartBit, ($FacilityStartBit + $FacilityBitLength)), TRUE );
		}

		$max_facility_code	= pow(2, $FacilityBitLength) - 1;

		if((int)$FacilityCode < 0 || (int)$FacilityCode > $max_facility_code) {
			$this->util::alert( $this->lang->card_format->error_facility_code_out_of_bounds . " [0-{$max_facility_code}]", TRUE );
		}

		//$sth	= $this->conn->prepare("INSERT INTO CardFormat (Name,Mean,FacilityCode,TotalBitLength,FacilityBitLength,EvenParityBitLength,FacilityStartBit,EvenParityStartBit,CardNumberLength,OddParityBitLength,CardNumberStartBit,OddParityStartBit) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		//$values	= array($Name,$Mean,$FacilityCode,$TotalBitLength,$FacilityBitLength,$EvenParityBitLength,$FacilityStartBit,$EvenParityStartBit,$CardNumberLength,$OddParityBitLength,$CardNumberStartBit,$OddParityStartBit);
		$sth	= $this->conn->prepare("INSERT INTO CardFormat (Name,Mean,FacilityCode,TotalBitLength,FacilityBitLength,FacilityStartBit,CardNumberLength,CardNumberStartBit) VALUES (?,?,?,?,?,?,?,?)");
		$values	= array($Name,$Mean,$FacilityCode,$TotalBitLength,$FacilityBitLength,$FacilityStartBit,$CardNumberLength,$CardNumberStartBit);
		if( $sth->execute( $values ) )
		{
			$this->log::set_log_message($Name);
			//exec(SPIDER_COMM." send db");
			
//			exec(SPIDER_COMM." clntdb sync");
			$this->util::js('load_list();');
            $this->util::alert( $this->lang->common->save_completed );
		}
		else
		{
			$this->util::alert($this->lang->common->error_insert);
		}
	}

	// ----------------------------------------------------------------------------------

	public function update()
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

		if( empty($Name) )		$this->util::alert( $this->lang->card_format->error_name_required, TRUE );

		if( $this->exists_name($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_card_format_name, TRUE);
		}

		if( $this->exists_data($No) )
		{
			$this->util::alert($this->lang->addmsg->error_exist_card_format, TRUE);
		}

		if( $TotalBitLength == '' )			$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->TotalBitLength, TRUE );
		if( $FacilityCode == '' )			$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityCode, TRUE );
		if( $FacilityBitLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityBitLength, TRUE );
		if( $FacilityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->FacilityStartBit, TRUE );
		if( $CardNumberLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->CardNumberLength, TRUE );
		if( $CardNumberStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->CardNumberStartBit, TRUE );
		//if( $EvenParityBitLength == '' )	$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->EvenParityBitLength, TRUE );
		//if( $EvenParityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->EvenParityStartBit, TRUE );
		//if( $OddParityBitLength == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->OddParityBitLength, TRUE );
		//if( $OddParityStartBit == '' )		$this->util::alert( $this->lang->addmsg->empty_required_item." : ".$this->lang->card_format->OddParityStartBit, TRUE );		

		if( !preg_match("/^[0-9]+$/", $TotalBitLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityCode) )			$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityBitLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $FacilityStartBit) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberLength) )		$this->util::alert( $this->lang->card_format->error_only_number, TRUE );
		if( !preg_match("/^[0-9]+$/", $CardNumberStartBit) )	$this->util::alert( $this->lang->card_format->error_only_number, TRUE );

		// TotalBitLength 범위 확인
		if( $TotalBitLength > 64 )			$this->util::alert( $this->lang->card_format->error_totalbit_less_then_equal_64, TRUE );

		// FacilityStartBit 범위 확인
		if( $FacilityStartBit > $TotalBitLength )			$this->util::alert( sprintf($this->lang->card_format->error_facility_start_range, $TotalBitLength), TRUE );

		// FacilityBitLength 범위 확인
		if( $FacilityBitLength > ($TotalBitLength - $FacilityStartBit) )			$this->util::alert( sprintf($this->lang->card_format->error_facility_length_range, ($TotalBitLength - $FacilityStartBit)), TRUE );

		// CardNumber 가 Facility 보다 앞에 있을 경우
		if( $CardNumberStartBit < $FacilityStartBit ) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($FacilityStartBit - $CardNumberStartBit))
				$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_length_range, ($FacilityStartBit - $CardNumberStartBit)), TRUE );

		// CardNumber 가 Facility 보다 뒤에 있을 경우
		} else if($CardNumberStartBit >= ($FacilityStartBit + $FacilityBitLength)) {
			// CardNumberLength 범위 확인
			if($CardNumberLength > ($TotalBitLength - $CardNumberStartBit + 1))
				$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_length_range, ($TotalBitLength - $CardNumberStartBit)), TRUE );
		} else {
			$this->util::alert( sprintf($this->lang->card_format->error_cardnumber_start_duplicate, $FacilityStartBit, ($FacilityStartBit + $FacilityBitLength)), TRUE );
		}

		$max_facility_code	= pow(2, $FacilityBitLength) - 1;

		if((int)$FacilityCode < 0 || (int)$FacilityCode > $max_facility_code) {
			$this->util::alert( $this->lang->card_format->error_facility_code_out_of_bounds . " [0-{$max_facility_code}]", TRUE );
		}

		$sth	= $this->conn->prepare("UPDATE CardFormat SET Name=?,Mean=?,FacilityCode=?,TotalBitLength=?,FacilityBitLength=?,FacilityStartBit=?,CardNumberLength=?,CardNumberStartBit=? WHERE No=?");

		$values	= array($Name,$Mean,$FacilityCode,$TotalBitLength,$FacilityBitLength,$FacilityStartBit,$CardNumberLength,$CardNumberStartBit,$No);
		if( $sth->execute($values) )
		{
			//exec(SPIDER_COMM." send db");
			$this->log::set_log_message($Name);
			$this->util::js('update_list("'.$No.'");');
            $this->util::alert( $this->lang->common->save_completed );
		}
		else
		{
			$this->util::alert($this->lang->common->error_update);
		}
	}

    // ----------------------------------------------------------------------------------

	public function update_default()
	{
		$no		= $this->input::get('no');

		$sth	= $this->conn->prepare("UPDATE CardFormat SET IsDefault=0");
		$sth->execute();

		$sth	= $this->conn->prepare("UPDATE CardFormat SET IsDefault=1 WHERE No=?");
		$sth->execute(array($no));
	}

    // ----------------------------------------------------------------------------------

    public function check_dependency()
    {
        $no     = $this->input::get('no');

        $item   = $this->conn->prepare("SELECT * FROM Card WHERE Site=? AND CardFormatNo=?");
        $item->execute(array($_SESSION['spider_site'], $no));
        if( $item = $item->fetchAll(\PDO::FETCH_ASSOC) )
		{
			$this->util::js('confirm_dependency()', TRUE);
		}

		$this->util::js('del_data_prepass()', TRUE);
    }

	// ----------------------------------------------------------------------------------

	public function delete()
	{
		$No		= $this->input::get('no');
        $Name   = $this->util::GetCardFormatName($No);
		
		//$fields = array($_SESSION['spider_site'],$No);
    	//$DataCount = $this->util::GetRecordCountSet("Card", "WHERE Site=? AND CardFormatNo=?", $fields);
    	//if ($DataCount == 0)
    	//{
			$sth	= $this->conn->prepare("DELETE FROM CardFormat WHERE No=?");
			if( $sth->execute(array($No)) )
			{
				//exec(SPIDER_COMM." send db");
				$this->log::set_log_message($Name);
                $this->util::alert( $this->lang->common->delete_completed );
			}
			else
			{
				$this->util::alert($this->lang->common->error_delete);
			}
		//}
		//else
		//{
		//	$this->util::alert($this->lang->addmsg->confirm_data_delete);
		//}
	}

    // ----------------------------------------------------------------------------------

    public function to_array_door()
    {
        $door = $this->conn->prepare("SELECT * FROM Door WHERE Site=?");
        $door->execute(array($_SESSION['spider_site']));
        $door = $door->fetchAll(\PDO::FETCH_ASSOC);

        $arr_door  = array();
        foreach( $door as $key=>$val)
        {
            $arr_door[$val['No']]  = $val['Name'];
        }

        return $arr_door;
    }

    // ----------------------------------------------------------------------------------

    public function to_array_door_scan()
    {
        //$door = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND HostNo=1");
        $door = $this->conn->prepare("SELECT * FROM Door WHERE Site=?");	// 모든 Door
        $door->execute(array($_SESSION['spider_site']));
        $door = $door->fetchAll(\PDO::FETCH_ASSOC);

        $arr_door  = array();
        foreach( $door as $key=>$val)
        {
            $arr_door[$val['No']]  = $val['Name'];
        }

        return $arr_door;
    }

    // ----------------------------------------------------------------------------------

    public function calculate()
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