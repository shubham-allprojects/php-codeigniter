<?php
namespace App\Controllers;
use App\Libraries\CirciutType1;

class Door extends BaseController
{
    protected $attrStrikeType;
    protected $attrTimeAnti ;
    protected $attrRoomAnti;
    protected $attrZoneAnti ;
    protected $attrAbsenceAnti ;
    protected $attrTwoManRule ;
    protected $attrFirstManRule;
    protected $attrFirstManEveryDay;
    protected $attrManagerIn;
    protected $attrTimeAntiType;
    protected $attrRoomAntiType ;
    protected $attrZoneAntiType;
    protected $attrAbsenceType;

    public function __construct(){
        $this->attrStrikeType         = array('0'=>'CloseSignal1', '1'=>'CloseSignal0');
        $this->attrTimeAnti           = array('0'=>'No', '1'=>'Yes');
        $this->attrRoomAnti           = array('0'=>'No', '1'=>'Yes');
        $this->attrZoneAnti           = array('0'=>'false', '1'=>'true');
        $this->attrAbsenceAnti        = array('0'=>'false', '1'=>'true');
        $this->attrTwoManRule         = array('0'=>'No', '1'=>'Yes');
        $this->attrFirstManRule       = array('0'=>'No', '1'=>'Yes');
        $this->attrFirstManEveryDay   = array('0'=>'No', '1'=>'Yes');
        $this->attrManagerIn          = array('0'=>'No', '1'=>'Yes');
        $this->attrTimeAntiType       = array('0'=>'Deny', '1'=>'Grant');
        $this->attrRoomAntiType       = array('0'=>'Deny', '1'=>'Grant');
        $this->attrZoneAntiType       = array('0'=>'Deny', '1'=>'Grant');
        $this->attrAbsenceType        = array('0'=>'Deny', '1'=>'Grant');
    }

    // ----------------------------------------------------------------------------------

    public function index()
    {
        $vars['attrFloor']      = $this->to_array_floor();
        $vars['attrReader']     = $this->to_array_reader();
        $vars['attrThreat']     = $this->to_threat_level();
        $vars['attrCardFormat'] = $this->to_array_card_format();
        $vars['arr_schedule']   = $this->to_array_schedule();
        $vars['attrRegion']     = $this->to_array_region();
        
		// ADD CJMOON 2017.05.17 Ticket #NXG-2489
		$vars['arr_door']   = $this->to_array_door2();
        $vars['baseController']   = $this;

        if($this->input::get('wizard') == '1' )	
			$this->display($vars, 'wizard/door', ['header' => 'css', 'footer' => '']);
		else								
		$this->display($vars, 'wizard/door', ['header' => 'header', 'footer' => 'footer']);
    }

    // ----------------------------------------------------------------------------------

    public function select()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
        $page   = $this->input::get('p', 1);
        $view   = $this->input::get('v');
        
        $page_config    = PAGE_CONFIG;
        $pagination     = $this->pagination;

        if( empty($field) || empty($word) )
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND Disable='0'");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' ");
            }
        
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0' ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           ORDER BY A.No DESC LIMIT ?, ?");
            }
            
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND Disable='0' AND $field LIKE ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           AND A.$field LIKE ? ");
            }
            
            $count->execute(array($_SESSION['spider_site'], $this->util::parse_search_string($word)));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0' AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           AND A.$field LIKE ? 
                                                           ORDER BY A.No DESC LIMIT ?, ?");
            }
            
            $list->execute(array($_SESSION['spider_site'], $this->util::parse_search_string($word), $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        $attrFloor      = $this->to_array_floor();
        //$attrReader     = $this->to_array_reader();
        $attrThreat     = $this->to_threat_level();
        $attrCardFormat = $this->to_array_card_format();
        $attrRegion     = $this->to_array_region();
                
        $door_contact   = $this->to_door_contact();
        $door_rex       = $this->to_door_rex();

        $arr_schedule       = $this->to_array_schedule();

        foreach( $list as $key=>$val )
        {
            $MasterReader   = $this->get_reader( $val['MasterReader'] );
            $SlaveReader    = $this->get_reader( $val['SlaveReader'] );
            $DoorContact    = $this->get_doorcontact( $val['No'] );
            $DoorRex        = $this->get_doorrex( $val['No'] );
            $DoorLock       = $this->get_doorlock( $val['No'] );

            $val['DoorContactName'] = $DoorContact["Name"];
            $val['DoorRexName']     = $DoorRex["Name"];
            $val['DoorLockName']    = $DoorLock["Name"];

            $val['ManTrapModeStr']    = !empty($val["ManTrapMode"])?$this->enumtable::$attrManTrapMode[$val["ManTrapMode"]]:'';
            if( !empty($val['PairDoorNo']) ) {
                $PairDoor   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND No=?");
                $PairDoor->execute(array($_SESSION['spider_site'], $val['PairDoorNo']));
                $PairDoor   = $PairDoor->fetch(\PDO::FETCH_ASSOC);
                $val['PairDoorName']    = $PairDoor["Name"];
            }
            $val['IsExteriorStr']    = (!empty($val["IsExterior"]) && $val["IsExterior"] == '1') ? '(Exterior)' : '';

            $val['FloorStr']            = $attrFloor[$val['Floor']];            
            $val['HeldOpenTimeStr']     = $val['HeldOpenTime']." (sec)";
            $val['AdaOpenTimeStr']      = $val['AdaOpenTime']." (sec)";
            $val['OpenAlarmTimeStr']    = $val['OpenAlarmTime']." (sec)";
            $val['StartTimeStr']        = $val['StrikeTime']." (sec)";
            $val['LockTimeStr']         = $val['LockTime']." (sec)";
            $val['DisableStr']          = $this->enumtable::$attrOnOff[$val['Disable']];
            $val['AlarmStr']            = $this->enumtable::$attrOnOff[$val['Alarm']];
            $val['ReaderFunctionStr']   = $this->enumtable::$attrReaderFunc[$val['ReaderFunction']];
            $val['MasterReaderName']    = $MasterReader["Name"];
            $val['MasterReaderType']    = $MasterReader["Type"];
            $val['MasterReaderTypeStr'] = $this->enumtable::$attrReaderType[$MasterReader['Type']];
            $val['MasterCardFormat']    = $MasterReader["CardFormatNo"];
            // $val['MasterReaderRegionNo']    = !empty($MasterReader["RegionNo"])?$MasterReader["RegionNo"]:'';
            // $val['MasterReaderRegionStr']   = $attrRegion[$MasterReader["RegionNo"]];
            $val['SlaveReaderName']     = $SlaveReader["Name"];
            $val['SlaveReaderType']     = $SlaveReader["Type"];
            $val['SlaveReaderTypeStr']  = $this->enumtable::$attrReaderType[$SlaveReader['Type']];
            $val['SlaveCardFormat']     = $SlaveReader["CardFormatNo"];
            // $val['SlaveReaderRegionNo']    = $SlaveReader["RegionNo"];
            // $val['SlaveReaderRegionStr']   = $attrRegion[$SlaveReader["RegionNo"]];

            // $val['CircuitTypeStr']      = CirciutType::$attrs[$door_contact[$val['ContactNo']]['Type']];
            $val['CircuitType']         = $door_contact[$val['ContactNo']]['Type'];

            $val['DoorContactEnable']   = $door_contact[$val['ContactNo']]['Disable'] == '1' ? '0' : '1';
            $val['DoorContactEnableStr']    = $this->enumtable::$attrYesNo[$val['DoorContactEnable']];

            $val['StrikeTypeStr']       = $this->attrStrikeType[$val['StrikeType']];
            $val['TimeAntiStr']         = $this->attrTimeAnti[$val['TimeAnti']];
            $val['RoomAntiStr']         = $this->attrRoomAnti[$val['RoomAnti']];
            $val['ZoneAntiStr']         = $this->attrZoneAnti[$val['ZoneAnti']];
            $val['Absence']             = $this->attrAbsenceAnti[$val['Absence']];

            $val['TimeAntiTypeStr']     = $this->attrTimeAntiType[$val['TimeAntiType']];
            $val['RoomAntiTypeStr']     = $this->attrRoomAntiType[$val['RoomAntiType']];
            $val['ZoneAntiTypeStr']     = $this->attrZoneAntiType[$val['ZoneAntiType']];
            $val['AbsenceTypeStr']      = $this->attrAbsenceType[$val['AbsenceType']];
            // $val['ZoneAntiReaderStr']   = $this->attrZoneAnti["infor"][$val['ZoneAntiReader']];
            
            $val['TimeAntiTimeStr']     = $val['TimeAntiTime']." (sec)";
            $val['RoomAntiTimeStr']     = $val['RoomAntiTime']." (sec)";

            $val['StrikeTimeStr']       = $val['StrikeTime']." (sec)";
            if ($val['StrikeTime'] >= 0)
            {
            	$val['ReLockValue']     = 1;
	            $val['ReLockStr']       = $this->lang->addmsg->relock_on_yes;
	        }
	        else
	    	{
	    		$val['ReLockValue']     = 0;
	        	$val['ReLockStr']       = $this->lang->addmsg->relock_on_no;
	        }
	        		        
            $val['ReaderStr']           = ''; //TODO: $arr_reader["infor"][$val['MasterReader']];
            $val['UserList']            = $this->get_user_select($val['No']);
            $val['UserListStr']         = $this->get_user_select_str($val['No']);
            
            $val['DoorContactStateStr'] = $this->enumtable::$attrState[$val['DoorContactState']];
            // $val['RexCircuitTypeStr']   = CirciutType::$attrs[$door_rex[$val['RexNo']]['Type']];
            $val['RexCircuitType']      = $door_rex[$val['RexNo']]['Type'];
            $val['RexAlarmStr']			= $this->enumtable::$attrOnOff[$door_rex[$val['RexNo']]['Alarm']];
            $val['RexAlarm']			= ($door_rex[$val['RexNo']]['Alarm'] == '0' ? '1' : '0');
            $val['LockModeStr']         = $this->enumtable::$attrLockMode[$val['LockMode']];
            $val['ThreatLevelStr']      = $attrThreat[$val['ThreatLevel']];
            // $val['ThreatIgnoreRex']     = ($val['ThreatIgnoreRex'] == '1' ? '1' : '0');
            //$val['ThreatIgnoreRexStr']  = $this->enumtable::$attrYesNo[$val['ThreatIgnoreRex']];

            $val['Client']              = $this->get_client($val['HostNo']);

            // Door Status Alarm Output �߰�
            //$val['ForcedEnableStr']     = $this->enumtable::$attrYesNo[$val['ForcedEnable']];
           // $val['HeldEnableStr']       = $this->enumtable::$attrYesNo[$val['HeldEnable']];
            // $val['ShuntEnableStr']      = $this->enumtable::$attrYesNo[$val['ShuntEnable']];

            // $output = $this->get_output($val['PF_AuxOutputNo']);
            // $val['PF_AuxOutputState']   = $output['State'];
            // $val['PF_AuxOutputName']    = $output['Name'];
            // $val['PF_AuxOutputStateStr']   = $this->enumtable::$attrState[$val['PF_AuxOutputState']];

            // $output = $this->get_output($val['AS_AuxOutputNo']);
            // $val['AS_AuxOutputState']   = $output['State'];
            // $val['AS_AuxOutputName']    = $output['Name'];
            // $val['AS_AuxOutputStateStr']   = $this->enumtable::$attrState[$val['AS_AuxOutputState']];

            $val['FirstManRuleStr']     = $this->attrFirstManRule[$val['FirstManRule']];
			$val['FirstManSelectTypeName']	= $this->enumtable::$attrGroup[$val['FirstManSelectType']];
            $val['FirstManUserList']    = $this->get_f_user_select($val['No'], $val['FirstManSelectType']);
            $val['FirstManUserStr']     = $this->get_f_user_select_str($val['No'], $val['FirstManSelectType']);
            //$val['FirstManEveryDayStr'] = $this->attrFirstManEveryDay[$val['FirstManEveryDay']];
			//$val['FirstManTimeStr']    = $this->get_time_str('0', $val['FirstManStartHour'], $val['FirstManStartMin'], $val['FirstManEndHour'], $val['FirstManEndMin']);

            //$val['GracePeriodStr']		= $val['GracePeriod'] .' '. $this->lang->common->minutes;

			$val['FirstManScheduleNo1']		= $val['FirstManReverse'];
            //$val['FirstManScheduleName1']	= $arr_schedule[$val['FirstManScheduleNo1']];

			// $val['FirstManScheduleNo2']		= $val['FirstManSchedule2'];
            // $val['FirstManScheduleName2']	= $arr_schedule[$val['FirstManScheduleNo2']];

			// $val['FirstManScheduleNo3']		= $val['FirstManSchedule3'];
            // $val['FirstManScheduleName3']	= $arr_schedule[$val['FirstManScheduleNo3']];

            // $val['ManagerInStr']        = $this->attrManagerIn[$val['ManagerInRule']]; 
			// $val['ManagerInTimeStr']    = $this->get_time_str($val['ManagerInReverse'], $val['ManagerInBeginHour'], $val['ManagerInBeginMin'], $val['ManagerInEndHour'], $val['ManagerInEndMin']);
			// $val['ManagerInSelectTypeName']	= $this->enumtable::$attrGroup[$val['ManagerInSelectType']];
            // $val['ManagerList']         = $this->get_manager_select($val['No'], $val['ManagerInSelectType']);
            // $val['ManagerListStr']      = $this->get_manager_select_str($val['No'], $val['ManagerInSelectType']);

			// $val['ManagerInScheduleNo1']	= $val['ManagerInReverse'];
            // $val['ManagerInScheduleName1']	= $arr_schedule[$val['ManagerInScheduleNo1']];

			// $val['ManagerInScheduleNo2']	= $val['ManagerInSchedule2'];
            // $val['ManagerInScheduleName2']	= $arr_schedule[$val['ManagerInScheduleNo2']];

			// $val['ManagerInScheduleNo3']	= $val['ManagerInSchedule3'];
            // $val['ManagerInScheduleName3']	= $arr_schedule[$val['ManagerInScheduleNo3']];

            // $val['TwoManRuleStr']       = $this->attrTwoManRule[$val['TwoManRule']];
            // if ($val['TwoManTime'] == "")
            // 	$val['TwoManTime'] = 3;            
            // $val['TwoManTimeStr']       = $val['TwoManTime']." (sec)";
			// $val['TwoMan1SelectTypeName']	= $this->enumtable::$attrGroup[$val['TwoMan1SelectType']];
            // $val['TwoMan1UserList']     = $this->get_t_user_select($val['No'], $val['TwoMan1SelectType']);
            // $val['TwoMan1UserStr']      = $this->get_t_user_select_str($val['No'], $val['TwoMan1SelectType']);
			// $val['TwoMan2SelectTypeName']	= $this->enumtable::$attrGroup[$val['TwoMan2SelectType']];
            // $val['TwoMan2UserList']     = $this->get_t2_user_select($val['No'], $val['TwoMan2SelectType']);
            // $val['TwoMan2UserStr']      = $this->get_t2_user_select_str($val['No'], $val['TwoMan2SelectType']);
			
			$postallock = $this->get_postalLock($val['HostNo'], $val['Port']);
			
			$val['PostalLockEnableName']   = $this->enumtable::$attrPostalLock[$postallock[0]=='1'?'0':'1'];
			// $val['PostalLockScheduleName'] = $postallock[1]==null?"":$arr_schedule[$postallock[1]];

			// $val['PostalLockEnable']   = $postallock[0]=='1'?'0':'1';
			// $val['PostalLockSchedule'] = $postallock[1];

			/* 2017.08.09 CJMOON NXG-2783 */
			// if($val['AS_AuxOutputState'] == "") {
			// 	$val['AS_AuxOutputState'] = "0";  /* Default */
			// 	$val['AS_AuxOutputStateStr'] = "De-Energized";
			// }

			// /* 2017.08.09 CJMOON NXG-2783 */
			// if($val['PF_AuxOutputState'] == "") {
			// 	$val['PF_AuxOutputState'] = "0";  /* Default */
			// 	$val['PF_AuxOutputStateStr'] = "De-Energized";
			// }

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
    
    public function find()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
        
        if( empty($field) || empty($word) )
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND Disable='0'");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' ");
            }
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0' ORDER BY No DESC");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           ORDER BY A.No DESC");
            }
            
            $list->execute(array($_SESSION['spider_site']));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND Disable='0' AND $field LIKE ?");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           AND A.$field LIKE ? ");
            }
            
            $count->execute(array($_SESSION['spider_site'], "%".$word."%"));
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0' AND $field LIKE ? ORDER BY No DESC");
            }
            else{
                $list   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? AND A.Disable='0' 
                                                           AND A.$field LIKE ? 
                                                           ORDER BY A.No DESC");
            }
            $list->execute(array($_SESSION['spider_site'], "%".$word."%"));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }
    
    public function find2()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
        
        if( empty($field) || empty($word) )
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Disable='0'");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Disable='0' ");
            }
            
            $count->execute();
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE Disable='0' ORDER BY No DESC");
            }
            else{
                $list  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Disable='0' 
                                                           ORDER BY A.No DESC ");
            }
            
            $list->execute();
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            if( $this->is_SuperAdmin() == true )
            {
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE $field LIKE ? AND Disable='0'");
            }
            else{
                $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Disable='0' 
                                                           AND A.$field LIKE ? ");
            }
            
            $count->execute(array("%".$word."%"));
            $count  = $count->fetchColumn();

            if( $this->is_SuperAdmin() == true )
            {
                $list   = $this->conn->prepare("SELECT * FROM Door WHERE $field LIKE ? AND Disable='0' ORDER BY No DESC");
            }
            else{
                $count  = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Disable='0' 
                                                           AND A.$field LIKE ? 
                                                           ORDER BY A.No DESC)");
            }
            $list->execute(array("%".$word."%"));
            $list   = $list->fetchAll(\PDO::FETCH_ASSOC);
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['count']        = $count;
        $result['list']         = $list;

        echo json_encode($result);
    }

    public function get_time_str($Reverse, $StartHour, $StartMin, $EndHour, $EndMin)
    {
        $retStr = "";
        
        if ($Reverse > 0)
        {
            $retStr = sprintf("~%02d:%02d, %02d:%02d~", $StartHour, $StartMin, $EndHour, $EndMin);
        }
        else
        {
            $retStr = sprintf("%02d:%02d ~ %02d:%02d", $StartHour, $StartMin, $EndHour, $EndMin);
        }

        return $retStr;
    }
    
    // ----------------------------------------------------------------------------------

    public function insert()
    {
    }

    // ----------------------------------------------------------------------------------

    public function update()
    {
        $No                 = $this->input::post('No');        
        $Disable            = $this->input::post('Ddisable', 0);
        $Alarm              = $this->input::post('Alarm', 0);
        $Name               = strip_tags(trim($this->input::post('Name')));
        $Name               = str_replace("'", " ", $Name);
        $Mean               = strip_tags($this->input::post('Mean'));
        $Floor              = $this->input::post('Floor', 0);
        $ReaderFunction     = $this->input::post('ReaderFunction', 0);
        $MasterReader       = $this->input::post('MasterReader');
        $MasterReaderName   = strip_tags($this->input::post('MasterReaderName'));
        $MasterReaderType   = $this->input::post('MasterReaderType', 0);
        $SlaveReader        = $this->input::post('SlaveReader');
        $SlaveReaderName    = strip_tags($this->input::post('SlaveReaderName'));
        $SlaveReaderType    = $this->input::post('SlaveReaderType', 0);
        $MasterReaderRegionNo   = $this->input::post('MasterReaderRegionNo', 0);
        $SlaveReaderRegionNo    = $this->input::post('SlaveReaderRegionNo', 0);
        $MasterCardFormat   = $this->input::post('MasterCardFormat', 0);
        $SlaveCardFormat    = $this->input::post('SlaveCardFormat', 0);
        $DoorContact        = $this->input::post('DoorContact', 0);
        $DoorContactName    = strip_tags($this->input::post('DoorContactName'));
		
		// ADD CJMOON 2017.03.28
		$PostalLockEnable   = $this->input::post('PostalLockEnable', 0);
		if(!empty($PostalLockEnable)) $PostalLockSchedule = $this->input::post('PostalLockSchedule', 0);

        $DoorLock           = $this->input::post('DoorLock', 0);
        $DoorLockName       = strip_tags($this->input::post('DoorLockName'));
        $DoorRex            = $this->input::post('DoorRex', 0);
        $DoorRexName        = strip_tags($this->input::post('DoorRexName'));
        $ThreatLevel        = $this->input::post('ThreatLevel', 0);
        $DoorContactEnable  = $this->input::post('DoorContactEnable', 0);
        $HeldOpenTime       = $this->input::post('HeldOpenTime', 0);
        $AdaOpenTime        = $this->input::post('AdaOpenTime', 0);
        $OpenAlarmTime      = $this->input::post('OpenAlarmTime', 0);
        $LockMode           = $this->input::post('LockMode', 0);
        $StrikeType         = $this->input::post('StrikeType', 0);
        $StrikeTime         = $this->input::post('StrikeTime', 0);
        $ReLockValue        = $this->input::post('ReLockValue', 0);
        if ($ReLockValue == 0)
        	$StrikeTime = -1;
        $LockTime           = $this->input::post('LockTime', 0);
        $TimeAnti           = $this->input::post('TimeAnti', 0);
        $TimeAntiType       = $this->input::post('TimeAntiType', 0);
        $TimeAntiTime       = $this->input::post('TimeAntiTime', 0);
        $RoomAnti           = $this->input::post('RoomAnti', 0);
        $RoomAntiType       = $this->input::post('RoomAntiType', 0);
        $RoomAntiTime       = $this->input::post('RoomAntiTime', 0);
        $ZoneAnti           = $this->input::post('ZoneAnti', 0);
        $ZoneAntiType       = $this->input::post('ZoneAntiType', 0);
        $ZoneAntiReader     = $this->input::post('ZoneAntiReader', 0);
        $ZoneAntiTime       = $this->input::post('ZoneAntiTime', 0);
        $Absence            = $this->input::post('Absence', 0);
        $AbsenceType        = $this->input::post('AbsenceType', 0);
        $DoorContactState   = $this->input::post('DoorContactState');
        $Position           = $this->input::post('Position');
        
        $RexCircuitType     = $this->input::post('RexCircuitType');
		$RexAlarm			= $this->input::post('RexAlarm', '1');
        $CiruitType         = $this->input::post('CircuitType');
        $ThreatLevel        = $this->input::post('ThreatLevel');
        $ThreatIgnoreRex    = $this->input::post('ThreatIgnoreRex', 0);

        $TwoManRule         = $this->input::post('TwoManRule', 0);
        $TwoManTime         = $this->input::post('TwoManTime', 6);
		$TwoMan1SelectType  = $this->input::post('TwoMan1SelectType', 1);
        $TwoMan1UserList    = $this->input::post('TwoMan1UserList', array());
		$TwoMan2SelectType  = $this->input::post('TwoMan2SelectType', 1);
        $TwoMan2UserList    = $this->input::post('TwoMan2UserList', array());

        $FirstManRule       = $this->input::post('FirstManRule', 0);
        $GracePeriod        = $this->input::post('GracePeriod', 0);
        $FirstManScheduleNo1    = $this->input::post('FirstManScheduleNo1', 0);
        $FirstManScheduleNo2    = $this->input::post('FirstManScheduleNo2', 0);
        $FirstManScheduleNo3    = $this->input::post('FirstManScheduleNo3', 0);
        //$FirstManEveryDay   = $this->input::post('FirstManEveryDay', 0);
        //$FirstManDate       = $this->input::post('FirstManDate');
        //$FirstManStartHour  = $this->input::post('FirstManStartHour', 0);
        //$FirstManStartMin   = $this->input::post('FirstManStartMin', 0);
		//$FirstManStart		= sprintf("%02d%02d", $FirstManStartHour, $FirstManStartMin);
        //$FirstManEndHour    = $this->input::post('FirstManEndHour', 0);
        //$FirstManEndMin     = $this->input::post('FirstManEndMin', 0);
		//$FirstManEnd		= sprintf("%02d%02d", $FirstManEndHour, $FirstManEndMin);
		$FirstManSelectType = $this->input::post('FirstManSelectType', 1);
        $FirstManUserList   = $this->input::post('FirstManUserList', array());
        
        $ManagerInRule      = $this->input::post('ManagerInRule', 0);
        $ManagerInScheduleNo1    = $this->input::post('ManagerInScheduleNo1', 0);
        $ManagerInScheduleNo2    = $this->input::post('ManagerInScheduleNo2', 0);
        $ManagerInScheduleNo3    = $this->input::post('ManagerInScheduleNo3', 0);
        //$ManagerInReverse   = $this->input::post('ManagerInReverse', 0);
        //$ManagerInBeginHour = sprintf("%02d", $this->input::post('ManagerInBeginHour', 0));
        //$ManagerInBeginMin  = sprintf("%02d", $this->input::post('ManagerInBeginMin', 0));
		//$ManagerInBegin		= sprintf("%02d%02d", $ManagerInBeginHour, $ManagerInBeginMin);
        //$ManagerInEndHour   = sprintf("%02d", $this->input::post('ManagerInEndHour', 0));
        //$ManagerInEndMin    = sprintf("%02d", $this->input::post('ManagerInEndMin', 0));
		//$ManagerInEnd		= sprintf("%02d%02d", $ManagerInEndHour, $ManagerInEndMin);
        //$ManagerInEndHour   = $this->input::post('ManagerInEndHour', 0);
        //$ManagerInEndMin    = $this->input::post('ManagerInEndMin', 0);
		$ManagerInSelectType = $this->input::post('ManagerInSelectType', 1);
        $ManagerList        = $this->input::post('ManagerList', array());
        
        $UserList           = $this->input::post('UserList', array());

        // Door Status Alarm Output �߰�
        $ForcedEnable       = $this->input::post('ForcedEnable', 0);
        $HeldEnable         = $this->input::post('HeldEnable', 0);
        $ShuntEnable        = $this->input::post('ShuntEnable', 0);
        $PF_AuxOutputState  = $this->input::post('PF_AuxOutputState', 0);
        $AS_AuxOutputState  = $this->input::post('AS_AuxOutputState', 0);
        $PF_AuxOutputNo     = $this->input::post('PF_AuxOutputNo', 0);
        $AS_AuxOutputNo     = $this->input::post('AS_AuxOutputNo', 0);


        if( empty($Name) )                      $this->util::alert( $this->lang->menu->error_name_required, TRUE );
//      if( empty($Floor) )                     $this->util::alert( $this->lang->door->error_floor_required, TRUE );
//      if( empty($DoorContactState) )          $this->util::alert( $this->lang->door->error_D_C_state_required, TRUE );
        if( $HeldOpenTime <= $OpenAlarmTime )   $this->util::alert( $this->lang->door->error_H_open_time, TRUE );

        
        // #1945 Muster Region�� �� �ٸ� ��Ģ�� ����
        $MasterIsMuster = '0';
        if( $MasterReaderRegionNo != '0')
        {
            $MasterMuster = $this->conn->prepare("SELECT OnlyMuster FROM Region WHERE No = '".$MasterReaderRegionNo."'");
            $MasterMuster->execute();
            $MasterIsMuster = $MasterMuster->fetchColumn();
        }
        
        $SlaveIsMuster = '0';
        if( $SlaveReaderRegionNo != '0' )
        {
            $SlaveMuster = $this->conn->prepare("SELECT OnlyMuster FROM Region WHERE No = '".$SlaveReaderRegionNo."'");
            $SlaveMuster->execute();
            $SlaveIsMuster = $SlaveMuster->fetchColumn();
        }
        
        // #1933 InReader�� Uncontrolled Space�� �ƴ� �����̸� OutReader�� Uncontrolled Space�� �� ����
        // Region No�� In Out�� ���� �ʵ��� ����
        if( $MasterIsMuster == '0' && $SlaveIsMuster == '0' ) //�Ѵ� MusterRegion�� �ƴ϶�� ������� 
        {
            if( $MasterReaderRegionNo != '0' || $SlaveReaderRegionNo != '0')
            {
                if( $MasterReaderRegionNo == $SlaveReaderRegionNo )
                {
                    $this->util::alert( $this->lang->door->error_region_duplicte, TRUE );
                }
            }
            
            if( $MasterReaderRegionNo != '0' && $SlaveReaderRegionNo == '0')
            {
                $this->util::alert( $this->lang->door->error_region_not_allowed, TRUE );
            }
        }
        else // �ϳ��� Muster Region�̶�� ���ο� ��Ģ�� ����
        {
            if( $MasterReaderRegionNo != $SlaveReaderRegionNo )
            {
                $this->util::alert( $this->lang->door->error_region_not_same_muster, TRUE );
            }
        }
        // #1945 �� 
        
        if( $ForcedEnable == '1' || $HeldEnable == '1' ) {
            if($this->is_used_output($No, $PF_AuxOutputNo))    $this->util::alert( $this->lang->door->error_alarm_output_used, TRUE );
        }

        if( $ShuntEnable == '1' ) {
            if($this->is_used_output($No, $AS_AuxOutputNo))    $this->util::alert( $this->lang->door->error_alarm_output_used, TRUE );
        }

        if( ($ForcedEnable == '1' || $HeldEnable == '1') && $ShuntEnable == '1' ) {
            if( $PF_AuxOutputNo != '0'  && $PF_AuxOutputNo == $AS_AuxOutputNo )   $this->util::alert( $this->lang->door->error_alarm_output_duplicate, TRUE );
        }
        
        $RuleCount = 0;
        if( !empty($TimeAnti) )                 $RuleCount++;
        if( !empty($RoomAnti) )                 $RuleCount++;
        if( $RuleCount > 1 )                    $this->util::alert( $this->lang->door->error_too_many_rule, TRUE );
        //$RuleCount = 0;
        //if( !empty($TwoManRule) )               $RuleCount++;
        //if( !empty($FirstManRule) )             $RuleCount++;
        //if( !empty($ManagerInRule) )            $RuleCount++;
        //if( $RuleCount > 1 )                    $this->util::alert( $this->lang->door->error_too_many_rule, TRUE );

		if( !empty($ManagerInRule) && !empty($TwoManRule) )									$this->util::alert( $this->lang->door->error_too_many_rule, TRUE );
		if( !empty($TwoManRule) && (!empty($ManagerInRule) || !empty($FirstManRule)) )		$this->util::alert( $this->lang->door->error_too_many_rule, TRUE );

		foreach( $TwoMan1UserList as $user ) {
			if( in_array($user, $TwoMan2UserList) ) {
				$this->util::alert( $this->lang->door->error_too_many_rule_same, TRUE );
			}
		}

		if( !empty($FirstManRule) ) {
			//if( empty($FirstManEveryDay) && empty($FirstManDate) )	$this->util::alert( $this->lang->door->error_required_firstman_rule_date, TRUE );
			//if( $FirstManStart == $FirstManEnd )					$this->util::alert( $this->lang->door->error_time_empty, TRUE );
			//if( $FirstManStart > $FirstManEnd )						$this->util::alert( $this->lang->door->error_time_value, TRUE );
			if( empty($FirstManScheduleNo1) && empty($FirstManScheduleNo2) && empty($FirstManScheduleNo3) )
                $this->util::alert( $this->lang->door->error_required_firstman_rule_schedule, TRUE );
			if( !empty($FirstManScheduleNo1) && ($FirstManScheduleNo1 == $FirstManScheduleNo2 || $FirstManScheduleNo1 == $FirstManScheduleNo3) )
                $this->util::alert( $this->lang->door->error_duplicate_firstman_rule_schedule, TRUE );
			if( !empty($FirstManScheduleNo2) && $FirstManScheduleNo2 == $FirstManScheduleNo3 )
                $this->util::alert( $this->lang->door->error_duplicate_firstman_rule_schedule, TRUE );
			if( count($FirstManUserList) < 1 )						$this->util::alert( $this->lang->door->error_required_firstman_rule_user, TRUE );
		}

		if( !empty($ManagerInRule) ) {
			//if( $ManagerInBegin == $ManagerInEnd )		$this->util::alert( $this->lang->door->error_time_empty, TRUE );
			//if( $ManagerInBegin > $ManagerInEnd )		$this->util::alert( $this->lang->door->error_time_value, TRUE );
			if( empty($ManagerInScheduleNo1) && empty($ManagerInScheduleNo2) && empty($ManagerInScheduleNo3) )
                $this->util::alert( $this->lang->door->error_required_manager_rule_schedule, TRUE );
			if( !empty($ManagerInScheduleNo1) && ($ManagerInScheduleNo1 == $ManagerInScheduleNo2 || $ManagerInScheduleNo1 == $ManagerInScheduleNo3) )
                $this->util::alert( $this->lang->door->error_duplicate_manager_rule_schedule, TRUE );
			if( !empty($ManagerInScheduleNo2) && $ManagerInScheduleNo2 == $ManagerInScheduleNo3 )
                $this->util::alert( $this->lang->door->error_duplicate_manager_rule_schedule, TRUE );
			if( count($ManagerList) < 1 )				$this->util::alert( $this->lang->door->error_required_manager_rule_user, TRUE );
		}

		if( !empty($TwoManRule) ) {
			if( count($TwoMan1UserList) < 1 )		$this->util::alert( $this->lang->door->error_required_twoman_rule_user, TRUE );
			if( count($TwoMan2UserList) < 1 )		$this->util::alert( $this->lang->door->error_required_twoman_rule_user, TRUE );
		}


        // ���� Door ����
        $tmpDoor   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND No = ?");
        $tmpDoor->execute(array($_SESSION['spider_site'], $No));
        $tmpDoor   = $tmpDoor->fetch(\PDO::FETCH_ASSOC);

        $LockMode    = $this->input::post('LockMode', $tmpDoor['LockMode']);
        $PairDoorNo  = $this->input::post('PairDoorNo', $tmpDoor['PairDoorNo']);
        $ManTrapMode = $this->input::post('ManTrapMode', $tmpDoor['ManTrapMode']);
    
		// By SUN07 - 2012.08.29 : Rule �� �ٲ�� �� ���� Temp ������ ������.
		if($FirstManRule != '1')
		{
            //$TempClear  = $this->net_work->prepare("UPDATE FirstManTemp SET Date=0 WHERE DoorNo=?");
            //$TempClear->execute(array($No));
		}
		if($TwoManRule != '1')
		{
            $TempClear  = $this->net_work->prepare("UPDATE TwoManTemp SET Date=0 WHERE DoorNo=?");
            $TempClear->execute(array($No));
		}
		if($ManagerIn != '1')
		{
            //$TempClear  = $this->net_work->prepare("UPDATE ManagerIn SET TagDate=0 WHERE DoorNo=?");
            //$TempClear->execute(array($No));
		}

        if($ZoneAnti == '1')
        {
            $ZoneReader = $this->conn->prepare("SELECT ZoneAntiReader FROM Door WHERE No = '".$No."'");
            $ZoneReader->execute();
            $ZoneReader = $ZoneReader->fetchColumn();
            
            if($ZoneReader !== $Z_anti_reader)
            {
                $z_reader = $this->conn->prepare("UPDATE Reader SET Anti= '' WHERE No = '".$ZoneReader."'");
                $z_reader->execute();
                
                $z_in_reader = $this->conn->prepare("UPDATE Reader SET Anti= '1' WHERE No = '".$ZoneAntiReader."'");
                $z_in_reader->execute();
            }
        }
        else
        {
            $ZoneReader = $this->conn->prepare("SELECT ZoneAntiReader FROM Door WHERE No = '".$No."'");
            $ZoneReader->execute();
            $ZoneReader = $ZoneReader->fetchColumn();
            
            if($ZoneReader)
            {
                $z_reader   = $this->conn->prepare("UPDATE Reader SET Anti= '' WHERE No = '".$ZoneReader."'");
                $z_reader->execute();
            }
        }
        if($DoorContactState)
        {
            $updte_lock  = $this->conn->prepare("UPDATE DoorLock SET Name=?, State=? WHERE No=?");
            $updte_lock->execute(array($DoorLockName, $DoorContactState, $No));
        }
        else
        {
            $updte_lock  = $this->conn->prepare("UPDATE DoorLock SET Name=? WHERE No=?");
            $updte_lock->execute(array($DoorLockName, $No));
        }
        if($RexCircuitType)
        {
            $updte_rex  = $this->conn->prepare("UPDATE DoorRex SET Name=?, Type=?, Alarm=? WHERE No=?");
            $updte_rex->execute(array($DoorRexName, $RexCircuitType, $RexAlarm, $No));
        }
        else
        {
            $updte_rex  = $this->conn->prepare("UPDATE DoorRex SET Name=? WHERE No=?");
            $updte_rex->execute(array($DoorRexName, $No));
        }
        
        // #2088 Door Lock Mode �� Man Trap �� ��쿡�� Door Contact �� ������ Enable True �� ������ (Pair Door����)
        $DoorContactDisable = (($DoorContactEnable == '1' || $LockMode == '4')? '0' : '1');
        $updte_contact  = $this->conn->prepare("UPDATE DoorContact SET Name=?, Disable=?, Type=? WHERE No=?");
        $updte_contact->execute(array($DoorContactName,$DoorContactDisable,$CiruitType,$No));

        $master_reader   = $this->conn->prepare("UPDATE Reader SET Name='".$MasterReaderName."', Type='".$MasterReaderType."', CardFormatNo='".$MasterCardFormat."', RegionNo='".$MasterReaderRegionNo."', OppRegionNo='".$SlaveReaderRegionNo."' WHERE No = '".$MasterReader."'");
        $master_reader->execute();

        $slave_reader   = $this->conn->prepare("UPDATE Reader SET Name='".$SlaveReaderName."', Type='".$SlaveReaderType."', CardFormatNo='".$SlaveCardFormat."', RegionNo='".$SlaveReaderRegionNo."', OppRegionNo='".$MasterReaderRegionNo."' WHERE No = '".$SlaveReader."'");
        $slave_reader->execute();
        
/*
        $sth    = $this->conn->prepare("UPDATE Door 
                                           SET Disable=?,Alarm=?,Name=?,Mean=?,Floor=?,ReaderFunction=?,ThreatLevel=?,
                                               HeldOpenTime=?,AdaOpenTime=?,OpenAlarmTime=?,StrikeType=?,LockTime=?,
                                               TimeAnti=?,TimeAntiType=?,TimeAntiTime=?,
                                               RoomAnti=?,RoomAntiType=?,RoomAntiTime=?,
                                               ZoneAnti=?,ZoneAntiType=?,ZoneAntiReader=?,ZoneAntiTime=?,
                                               Absence=?,AbsenceType=?,
                                               StrikeTime=?,DoorContactState=?,LockMode=?,
                                               TwoManRule=?,TwoManTime=?,FirstManRule=?,FirstManEveryDay=?,FirstManDate=?,
                                               FirstManStartHour=?,FirstManStartMin=?,FirstManEndHour=?,FirstManEndMin=?,
                                               ManagerInRule=?,ManagerInReverse=?,ManagerInBeginHour=?,ManagerInBeginMin=?,ManagerInEndHour=?,ManagerInEndMin=? WHERE No=?");

        $values = array($Disable,$Alarm,$Name,$Mean,$Floor,$ReaderFunction,$ThreatLevel,
                        $HeldOpenTime,$AdaOpenTime,$OpenAlarmTime,$StrikeType,$LockTime,
                        $TimeAnti,$TimeAntiType,$TimeAntiTime,
                        $RoomAnti,$RoomAntiType,$RoomAntiTime,
                        $ZoneAnti,$ZoonAntiType,$ZoneAntiReader,$ZoneAntiTime,
                        $Absence,$AbsenceType,
                        $StrikeTime,$DoorContactState,$LockMode,
                        $TwoManRule,$TwoManTime,$FirstManRule,$FirstManEveryDay,$FirstManDate,
                        $FirstManStartHour,$FirstManStartMin,$FirstManEndHour,$FirstManEndMin,
                        $ManagerInRule,$ManagerInReverse,$ManagerInBeginHour,$ManagerInBeginMin,$ManagerInEndHour,$ManagerInEndMin,$No);
*/
        $sth    = $this->conn->prepare("UPDATE Door 
                                           SET Disable=?,Alarm=?,Name=?,Mean=?,Floor=?,ReaderFunction=?,ThreatLevel=?,ThreatIgnoreRex=?,
                                               HeldOpenTime=?,AdaOpenTime=?,OpenAlarmTime=?,StrikeType=?,LockTime=?,
                                               TimeAnti=?,TimeAntiType=?,TimeAntiTime=?,
                                               RoomAnti=?,RoomAntiType=?,RoomAntiTime=?,
                                               ZoneAnti=?,ZoneAntiType=?,ZoneAntiReader=?,ZoneAntiTime=?,
                                               Absence=?,AbsenceType=?,
                                               StrikeTime=?,DoorContactState=?,LockMode=?,
                                               TwoManRule=?,TwoManTime=?,FirstManRule=?,GracePeriod=?,FirstManReverse=?,FirstManSchedule2=?,FirstManSchedule3=?,
                                               ManagerInRule=?,ManagerInReverse=?,ManagerInSchedule2=?,ManagerInSchedule3=?,
											   FirstManSelectType=?,ManagerInSelectType=?,TwoMan1SelectType=?,TwoMan2SelectType=?,
                                               ForcedEnable=?,HeldEnable=?,ShuntEnable=?,PF_AuxOutputNo=?,AS_AuxOutputNo=?,
                                               IsExterior=0, ManTrapMode=0, PairDoorNo=0
											   WHERE No=?");

        $values = array($Disable,$Alarm,$Name,$Mean,$Floor,$ReaderFunction,$ThreatLevel,$ThreatIgnoreRex,
                        $HeldOpenTime,$AdaOpenTime,$OpenAlarmTime,$StrikeType,$LockTime,
                        $TimeAnti,$TimeAntiType,$TimeAntiTime,
                        $RoomAnti,$RoomAntiType,$RoomAntiTime,
                        $ZoneAnti,$ZoonAntiType,$ZoneAntiReader,$ZoneAntiTime,
                        $Absence,$AbsenceType,
                        $StrikeTime,$DoorContactState,$LockMode,
                        $TwoManRule,$TwoManTime,$FirstManRule,$GracePeriod,$FirstManScheduleNo1,$FirstManScheduleNo2,$FirstManScheduleNo3,
                        $ManagerInRule,$ManagerInScheduleNo1,$ManagerInScheduleNo2,$ManagerInScheduleNo3,
						$FirstManSelectType,$ManagerInSelectType,$TwoMan1SelectType,$TwoMan2SelectType,
                        $ForcedEnable,$HeldEnable,$ShuntEnable,$PF_AuxOutputNo,$AS_AuxOutputNo,
						$No);

        if( $sth->execute($values) )
        {
            $Port           = $this->input::post('Port', 0);
            $HostNo         = $this->input::post('HostNo', 0);
            $IsExterior     = $this->input::post('IsExterior', $tmpDoor['IsExterior']);
            $PairDoorNo     = $this->input::post('PairDoorNo', $tmpDoor['PairDoorNo']);
            $ManTrapMode    = $this->input::post('ManTrapMode', $tmpDoor['ManTrapMode']);

            if( $Port == '1' ) {
                $mantrap  = $this->conn->prepare("UPDATE Door SET LockMode=0, IsExterior=0, ManTrapMode=0, PairDoorNo=0 WHERE Site=? AND HostNo=? AND LockMode=4");
                $mantrap->execute(array($_SESSION['spider_site'],$HostNo));

                if( $LockMode == '4' ) {
                    $mantrap  = $this->conn->prepare("UPDATE Door SET LockMode=?, IsExterior=?, ManTrapMode=?, PairDoorNo=? WHERE Site=? AND No=?");
                    $mantrap->execute(array($LockMode,$IsExterior,$ManTrapMode,$PairDoorNo,$_SESSION['spider_site'],$No));

                    $PairExterior   = $IsExterior == '1' ? '0' : '1';

                    $mantrap  = $this->conn->prepare("UPDATE Door SET LockMode=?, IsExterior=?, ManTrapMode=?, PairDoorNo=? WHERE Site=? AND No=?");
                    $mantrap->execute(array($LockMode,$PairExterior,$ManTrapMode,$No,$_SESSION['spider_site'],$PairDoorNo));
                    
                    // #2088 Lock Mode �� Man Trap �� ��� Pair Door �� Door Contact �� Enable True �� �Ѵ�.
			        $pair_contact  = $this->conn->prepare("UPDATE DoorContact SET Disable=0 WHERE Site=? AND No=?");
			        $pair_contact->execute(array($_SESSION['spider_site'],$PairDoorNo));
                }
            }

            $auxoutput  = $this->conn->prepare("UPDATE AuxOutput SET State=? WHERE No=?");
            $auxoutput->execute(array($PF_AuxOutputState,$PF_AuxOutputNo));

            $auxoutput  = $this->conn->prepare("UPDATE AuxOutput SET State=? WHERE No=?");
            $auxoutput->execute(array($AS_AuxOutputState,$AS_AuxOutputNo));

            $sth    = $this->conn->prepare("DELETE FROM AbsenceAnti WHERE DoorNo=?");
            $sth->execute( array($No) );

            $sth    = $this->conn->prepare("INSERT INTO AbsenceAnti (DoorNo,UserNo) VALUES (?,?)");
            foreach( $UserList as $value )
            {
                $sth->execute( array($No, $value) );
            }

            $sth    = $this->conn->prepare("DELETE FROM TwoManRule WHERE DoorNo=?");
            $sth->execute( array($No) );

            $sth    = $this->conn->prepare("INSERT INTO TwoManRule (DoorNo,UserNo1) VALUES (?,?)");
            foreach( $TwoMan1UserList as $value )
            {
                $sth->execute( array($No, $value) );
            }

            $sth    = $this->conn->prepare("INSERT INTO TwoManRule (DoorNo,UserNo2) VALUES (?,?)");
            foreach( $TwoMan2UserList as $value )
            {
                $sth->execute( array($No, $value) );
            }

            $sth    = $this->conn->prepare("DELETE FROM FirstManIn WHERE DoorNo=?");
            $sth->execute( array($No) );

            $sth    = $this->conn->prepare("INSERT INTO FirstManIn (DoorNo,FirstManUserNo) VALUES (?,?)");
            foreach( $FirstManUserList as $value )
            {
                $sth->execute( array($No, $value) );
            }

            $TempClear  = $this->net_work->prepare("UPDATE FirstManTemp SET Date=0 WHERE DoorNo=? AND FirstManUserNo NOT IN (".implode(',', $FirstManUserList).")");
            $TempClear->execute(array($No));

            $sth    = $this->conn->prepare("DELETE FROM ManagerIn WHERE DoorNo=?");
            $sth->execute( array($No) );

            $sth    = $this->conn->prepare("INSERT INTO ManagerIn (DoorNo,UserNo) VALUES (?,?)");
            foreach( $ManagerList as $value )
            {
                $sth->execute( array($No, $value) );
            }
            
            $TempClear  = $this->net_work->prepare("UPDATE ManagerIn SET TagDate=0 WHERE DoorNo=? AND UserNo NOT IN (".implode(',', $ManagerList).")");
            $TempClear->execute(array($No));

            //if ($No > 4)
	            //exec(SPIDER_COMM." send db");

			// ADD CJMOON 2017.03.28
			 if( $Port == '1' ) {					
				if($PostalLockEnable == 1) {
					 $postlockdb = $this->get_postalLockCounter($HostNo);

					 if($postlockdb[0] > 0) {  // ���� ����Ÿ ����
						 $postlockdbUpdate = $this->conn->prepare("UPDATE PostalLock SET Disable=?, ScheduleNo=? WHERE No=?");
						 $postlockdbUpdate->execute(array(0/*$PostalLockEnable*/, $PostalLockSchedule, $postlockdb[1]));
					 } else { // ���� ����Ÿ ����.	 					 

						 $postlockdbUpdate = $this->conn->prepare("INSERT INTO PostalLock (No, Site, Disable, HostNo, Port, ScheduleNo) VALUES((select ifnull(max(No)+1, 1) from PostalLock where site=?),?,?,?,?,? )");
						 $postlockdbUpdate->execute(array($_SESSION['spider_site'],$_SESSION['spider_site'], 0 /*$PostalLockEnable*/, $HostNo, $Port,  $PostalLockSchedule));
					 }			
				} else {
					 $postlockdb = $this->get_postalLockCounter($HostNo);
					 if($postlockdb[0] > 0) {  // ���� ����Ÿ ����
						 $postlockdbUpdate = $this->conn->prepare("UPDATE PostalLock SET Disable=?, ScheduleNo=? WHERE No=?");
						 $postlockdbUpdate->execute(array(1/*$PostalLockEnable*/, $PostalLockSchedule, $postlockdb[1]));				
					 }
				}
			 }
			 

			$this->log::set_log_message($Name);	            
	        $this->util::js('update_list("'.$No.'"); init_view();');
            
            $this->util::alert( $this->lang->common->save_completed );
	            
            exec(SPIDER_COMM." adc door $No");
            if( $LockMode == 4 ) {  // Man Trap �϶�
                exec(SPIDER_COMM." door $No ".strtolower( str_replace(' ', '', $this->enumtable::$attrLockMode[$LockMode]) )." ".$ManTrapMode );
                exec(SPIDER_COMM." door $PairDoorNo ".strtolower( str_replace(' ', '', $this->enumtable::$attrLockMode[$LockMode]) )." ".$ManTrapMode );
                if( $tmpDoor['PairDoorNo'] != '0' && $tmpDoor['PairDoorNo'] != $PairDoorNo ) {
                    exec(SPIDER_COMM." door {$tmpDoor['PairDoorNo']} ".strtolower( str_replace(' ', '', $this->enumtable::$attrLockMode[0]) ) );
                }
            } else {
                exec(SPIDER_COMM." door $No ".strtolower( str_replace(' ', '', $this->enumtable::$attrLockMode[$LockMode]) ) );
                if( $tmpDoor['PairDoorNo'] != '0' ) {
                    exec(SPIDER_COMM." door {$tmpDoor['PairDoorNo']} ".strtolower( str_replace(' ', '', $this->enumtable::$attrLockMode[$LockMode]) ) );
                }
            }
            exec(SPIDER_COMM." checkinfirstman $No");
        }
        else
        {
            $this->util::alert($this->lang->common->error_update);
        }
    }

    // ----------------------------------------------------------------------------------

    public function delete()
    {
    }

    // ----------------------------------------------------------------------------------

    public function is_used_output($DoorNo, $AuxOutputNo)
    {
        $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND No != ? AND (ForcedEnable = 1 OR HeldEnable = 1) AND PF_AuxOutputNo = ?");
        $count->execute(array($_SESSION['spider_site'], $DoorNo, $AuxOutputNo));
        $count  = $count->fetchColumn();
        if($count > 0) return TRUE;

        $count  = $this->conn->prepare("SELECT COUNT(*) FROM Door WHERE Site=? AND No != ? AND ShuntEnable = 1 AND AS_AuxOutputNo = ?");
        $count->execute(array($_SESSION['spider_site'], $DoorNo, $AuxOutputNo));
        $count  = $count->fetchColumn();
        if($count > 0) return TRUE;

        return FALSE;
    }

    // ----------------------------------------------------------------------------------

    public function get_output($No)
    {
        $output  = $this->conn->prepare("SELECT * FROM AuxOutput WHERE Site=? AND No=?");
        $output->execute(array($_SESSION['spider_site'], $No));
        $output  = $output->fetch(\PDO::FETCH_ASSOC);

        return $output;
    }

    // ----------------------------------------------------------------------------------

    public function to_array_floor()
    {
        $cfloor = $this->conn->prepare("SELECT * FROM Floor WHERE Site=?");
        $cfloor->execute(array($_SESSION['spider_site']));
        $cfloor = $cfloor->fetchAll(\PDO::FETCH_ASSOC);

        $arr_floor  = array();
        foreach( $cfloor as $key=>$val)
        {
            $arr_floor[$val['No']]  = $val['Name'];
        }

        return $arr_floor;
    }

    // ----------------------------------------------------------------------------------

    public function to_array_reader()
    {
        if( $this->is_SuperAdmin() == true )
        {
            $list   = $this->conn->prepare("SELECT * FROM Reader WHERE Site=?");
        }
        else{
            $list   = $this->conn->prepare("SELECT A.* FROM Reader AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? ");
        }
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

        $arr  = array();
        $arr1 = array();
        $arr2 = array();
        $arr3 = array();

        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]    = $val['Name'];
            $arr1[$val['No']]   = $val['Type'];
            $arr2[$val['No']]   = $this->enumtable::$attrReaderType[$val['Type']];
            $arr3[$val['No']]   = $val['CardFormatNo'];
        }

        $Aarr = array("infor"=> $arr, "Type" => $arr1, "TypeStr" => $arr2, "CardFormatNo" => $arr3);

        return $Aarr;
    }

    public function to_door_contact()
    {
        if( $this->is_SuperAdmin() == true )
        {
            $contactlist = $this->conn->prepare("SELECT * FROM DoorContact WHERE Site=?");
        }
        else{
            $contactlist = $this->conn->prepare("SELECT A.* FROM DoorContact AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? ");
        }
        $contactlist->execute(array($_SESSION['spider_site']));
        $contactlist = $contactlist->fetchAll(\PDO::FETCH_ASSOC);

		$arr = array();
        foreach( $contactlist as $key=>$val)
        {
            $arr[$val['No']]  = $val;
        }
        
        return $arr;
    }
    
    public function to_door_rex()
    {
        if( $this->is_SuperAdmin() == true )
        {
            $rexlist = $this->conn->prepare("SELECT * FROM DoorRex WHERE Site=?");
        }
        else{
            $rexlist = $this->conn->prepare("SELECT A.* FROM DoorRex AS A 
                                                           JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                           WHERE A.Site=? ");
        }
        $rexlist->execute(array($_SESSION['spider_site']));
        $rexlist = $rexlist->fetchAll(\PDO::FETCH_ASSOC);

		$arr = array();
        foreach( $rexlist as $key=>$val)
        {
            $arr[$val['No']]  = $val;
        }
        
        return $arr;
    }   
    
    public function to_threat_level()
    {
        $threatlist = $this->conn->prepare("SELECT * FROM ThreatLevel WHERE Site=?");
        $threatlist->execute(array($_SESSION['spider_site']));
        $threatlist = $threatlist->fetchAll(\PDO::FETCH_ASSOC);
        
        $arr_threat = array();
        foreach( $threatlist as $key=>$val)
        {
            $arr_threat[$val['Level']]  = $val['ThreatName'];
        }

        return $arr_threat;
    }
    
    // ----------------------------------------------------------------------------------

    public function get_user_select($No)
    {
        $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND No IN (SELECT UserNo FROM AbsenceAnti WHERE DoorNo = ?)");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

        return $list;
    }

    // ----------------------------------------------------------------------------------

    public function get_user_select_str($No)
    {
        $arr    = array();
        $list   = $this->get_user_select($No);
        foreach( $list as $val )
        {
            $arr[]  = $val['FirstName'] .' '. $val['MiddleName'] .' '. $val['LastName'];
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    public function get_user_to_options()
    {
		$options = array();
/*
        $list   = $this->conn->prepare("SELECT No, FirstName, MiddleName, LastName FROM User WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

		foreach( $list as $item ) {
			$options[$item['No']] = $item['FirstName'] .' '. $item['MiddleName'] .' '. $item['LastName'];
		}
*/
        return $options;
    }

	// --- ADD CJMOON 2017.03.28 -------------------------------------------------------
	public function get_postalLockCounter($No) 
	{
		 $postlockdb = $this->conn->prepare("SELECT count(No) cnt, No FROM PostalLock WHERE Site=? AND HostNo=?");
		 $postlockdb->execute(array($_SESSION['spider_site'], $No));
		 $postlockdb = $postlockdb->fetchAll(\PDO::FETCH_ASSOC);
		 
		 $liststr = array();
		 foreach( $postlockdb as $key=>$val)
		 {
		    $liststr[] = $val['cnt'];
			$liststr[] = $val['No'];
		 }
				     
	  return $liststr;

	}

	public function get_postalLock($hostNo, $portno) 
	{
	  
	  if($portno != 1) return array('1','0');

      $postaldb = $this->conn->prepare("SELECT Disable, ScheduleNo FROM PostalLock WHERE Site=? And HostNo = ?");
	  $postaldb->execute(array($_SESSION['spider_site'], $hostNo));
	  $postaldb = $postaldb->fetchAll(\PDO::FETCH_ASSOC);

	  $liststr = array();
      foreach( $postaldb as $key=>$val)
      {
         $liststr[] = $val['Disable'];
	     $liststr[] = $val['ScheduleNo'];
      }

	  if(count($liststr) == 0) $liststr = array('1','');

	  return $liststr;
	}

    // ----------------------------------------------------------------------------------
	
    public function get_f_user_select($No, $SelectType)
    {
		$list = array();

		if($SelectType == INDIVIDUAL) {
	        $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND No IN (SELECT FirstManUserNo FROM FirstManIn WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		} else if($SelectType == GROUP) {
	        $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN (SELECT FirstManUserNo FROM FirstManIn WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], GROUP_USER, $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		}

        return $list;
    }

    public function get_f_user_select_str($No, $SelectType)
    {
        $arr    = array();
        $list   = $this->get_f_user_select($No, $SelectType);
        foreach( $list as $val )
        {
			if($SelectType == INDIVIDUAL) {
	            $arr[]  = $val['FirstName'] .' '. $val['MiddleName'] .' '. $val['LastName'];
			} else if($SelectType == GROUP) {
				$arr[]  = $val['Name'];
			}
        }

        return implode(',', $arr);
    }
    
    // ----------------------------------------------------------------------------------

    public function get_manager_select($No, $SelectType)
    {
		$list = array();

		if($SelectType == INDIVIDUAL) {
	        $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND No IN (SELECT UserNo FROM ManagerIn WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		} else if($SelectType == GROUP) {
	        $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN (SELECT UserNo FROM ManagerIn WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], GROUP_USER, $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		}

        return $list;
    }

    // ----------------------------------------------------------------------------------

    public function get_manager_select_str($No, $SelectType)
    {
        $arr    = array();
        $list   = $this->get_manager_select($No, $SelectType);
        foreach( $list as $val )
        {
			if($SelectType == INDIVIDUAL) {
	            $arr[]  = $val['FirstName'] .' '. $val['MiddleName'] .' '. $val['LastName'];
			} else if($SelectType == GROUP) {
				$arr[]  = $val['Name'];
			}
        }

        return implode(',', $arr);
    }    

    // ----------------------------------------------------------------------------------

    public function get_t_user_select($No, $SelectType)
    {
		$list = array();

		if($SelectType == INDIVIDUAL) {
	        $list   = $this->conn->prepare("SELECT No, IFNULL(FirstName,'') AS FirstName, IFNULL(MiddleName,'') AS MiddleName, IFNULL(LastName,'') AS LastName FROM User WHERE Site=? AND No IN (SELECT UserNo1 FROM TwoManRule WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		} else if($SelectType == GROUP) {
	        $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN (SELECT UserNo1 FROM TwoManRule WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], GROUP_USER, $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		}

        return $list;
    }

    // ----------------------------------------------------------------------------------

    public function get_t_user_select_str($No, $SelectType)
    {
        $arr    = array();
        $list   = $this->get_t_user_select($No, $SelectType);
        foreach( $list as $val )
        {
			if($SelectType == INDIVIDUAL) {
	            $arr[]  = $val['FirstName'] .' '. $val['MiddleName'] .' '. $val['LastName'];
			} else if($SelectType == GROUP) {
				$arr[]  = $val['Name'];
			}
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    public function get_t2_user_select($No, $SelectType)
    {
		$list = array();

		if($SelectType == INDIVIDUAL) {        
	        $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND No IN (SELECT UserNo2 FROM TwoManRule WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		} else if($SelectType == GROUP) {
	        $list   = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN (SELECT UserNo2 FROM TwoManRule WHERE DoorNo = ?)");
		    $list->execute(array($_SESSION['spider_site'], GROUP_USER, $No));
			$list   = $list->fetchAll(\PDO::FETCH_ASSOC);
		}

        return $list;
    }

    // ----------------------------------------------------------------------------------

    public function get_t2_user_select_str($No, $SelectType)
    {
        $arr    = array();
        $list   = $this->get_t2_user_select($No, $SelectType);
        foreach( $list as $val )
        {
			if($SelectType == INDIVIDUAL) {
	            $arr[]  = $val['FirstName'] .' '. $val['MiddleName'] .' '. $val['LastName'];
			} else if($SelectType == GROUP) {
				$arr[]  = $val['Name'];
			}
        }

        return implode(',', $arr);
    }

    // ----------------------------------------------------------------------------------

    public function get_client($HostNo)
    {
        $list   = $this->conn->prepare("SELECT Name FROM Controller WHERE No = ?");
        $list->execute(array($HostNo));
        $name   = $list->fetchColumn();

        return $name;
    }

    // ----------------------------------------------------------------------------------

    public function to_array_card_format()
    {
        $list   = $this->conn->prepare("SELECT * FROM CardFormat");
        $list->execute();
        $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']]    = $val['Name'];
        }

        return $arr;
    }

    // ----------------------------------------------------------------------------------
    
    public function to_array_schedule()
    {
        $list   = $this->conn->prepare("SELECT * FROM Schedule WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

        $arr    = array(''=>'');
        foreach ($list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }

        return $arr;
    }

    // ----------------------------------------------------------------------------------
    
    public function to_array_region()
    {
        // TODO
        // $list   = $this->conn->prepare("SELECT * FROM `Region` WHERE Site=?");
        // $list->execute(array($_SESSION['spider_site']));
        // $list   = $list->fetchAll(\PDO::FETCH_ASSOC);

        // $arr    = array('0'=>$this->lang->door->uncontrolled_space);
        // foreach ($list as $key=>$val)
        // {
        //     $arr[$val['No']] = $val['Name'];
        // }

        return [];
    }

    // ----------------------------------------------------------------------------------
    
    public function get_reader($no)
    {
        $list   = $this->conn->prepare("SELECT * FROM Reader WHERE Site=? AND No=?");
        $list->execute(array($_SESSION['spider_site'], $no));
        $row   = $list->fetch(\PDO::FETCH_ASSOC);

        return $row;
    }

    // ----------------------------------------------------------------------------------

    public function get_doorcontact($no)
    {
        $list   = $this->conn->prepare("SELECT * FROM DoorContact WHERE Site=? AND No=?");
        $list->execute(array($_SESSION['spider_site'], $no));
        $row   = $list->fetch(\PDO::FETCH_ASSOC);

        return $row;
    }

    // ----------------------------------------------------------------------------------

    public function get_doorrex($no)
    {
        $list   = $this->conn->prepare("SELECT * FROM DoorRex WHERE Site=? AND No=?");
        $list->execute(array($_SESSION['spider_site'], $no));
        $row   = $list->fetch(\PDO::FETCH_ASSOC);

        return $row;
    }

    // ----------------------------------------------------------------------------------

    public function get_doorlock($no)
    {
        $list   = $this->conn->prepare("SELECT * FROM DoorLock WHERE Site=? AND No=?");
        $list->execute(array($_SESSION['spider_site'], $no));
        $row   = $list->fetch(\PDO::FETCH_ASSOC);

        return $row;
    }

    // ----------------------------------------------------------------------------------
	// ADD CJMOON 2017.05.17 #ticket NXG-2489

    public function door_control()
    {
		if($this->is_auth(41, 3) != TRUE){
    		$this->util::alert($this->lang->user->error_not_permission, TRUE);
    	}
        $sel_door   = $this->input::post('sel_door');
		$lock_mode  = $this->input::post('lock_mode');

        $door   = $this->conn->prepare("SELECT Name, LockMode, Port FROM Door WHERE Site=? AND No = ? AND Disable=0");
        $door->execute( array($_SESSION['spider_site'], $sel_door) );
        $door   = $door->fetch(\PDO::FETCH_ASSOC);

        if( $door['LockMode'] > '0' && $door['LockMode'] < '4' )
        {
            $this->util::alert( $this->lang->menu->error_door_controll );
        }
        else if($door['LockMode'] == '4')
        {
            $this->util::alert( $this->lang->menu->error_door_controll_Mantrap );
        }
        else
        {
			$this->log::set_log_device('door', $lock_mode, '0', $door['Port'], $door['Name']);
            exec(SPIDER_COMM." door $sel_door $lock_mode forced");
        }
    }
    // ----------------------------------------------------------------------------------
	// ADD CJMOON 2017.05.17 #ticket NXG-2489
    public function to_array_door2()
    {
        if( $this->is_SuperAdmin() == true )
        {
            $data   = $this->conn->prepare("SELECT * FROM Door WHERE Site=? AND Disable='0'");
        }
        else{
            $data   = $this->conn->prepare("SELECT A.* FROM Door AS A 
                                                       JOIN Host B ON A.HostNo = B.No AND (B.ByAdminId =  '".$_SESSION['spider_id']."' OR B.ByAdminId = '' OR B.ByAdminId IS null)
                                                       WHERE A.Site=? AND A.Disable='0' ");
        }
        
        $data->execute(array($_SESSION['spider_site']));
        $data   = $data->fetchAll(\PDO::FETCH_ASSOC);
        
        $arr    = array();
        
        foreach( $data as $key=>$val)
        {
            $arr[$val['No']]    = $val['Name'];
        }
        

        return $arr;
    }
    // ----------------------------------------------------------------------------------
}


?>