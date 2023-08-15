<?php 
class dexport extends controller
{

    const EXPORT_FORMAT_VERSION = 1;

    function index()
    {
        $this->display($vars);
    }

    function export()
    {
		set_time_limit(0);

        $sel    = Input::post('sel');
        
        if( $sel == 'csv' )
        {
            exec(SPIDER_COMM." databack");

            $arr_threat         = $this->to_array_threat();

	    $conn = Util::get_connect_backup_db();
            $list   = $conn->prepare("SELECT User.No, User.FirstName, User.MiddleName, User.LastName, User.Phone, User.PhoneExt, User.CellPhone, User.Email, User.ThreatLevel,
                                                   IFNULL(Card.CardNo, 0) AS CardNo, IFNULL(Card.No, 0) AS CNo, IFNULL(CardFormat.Name, '') As FormatName, Card.SelectType,
							UserDefine1,UserDefine2,UserDefine3,UserDefine4,UserDefine5,UserDefine6,UserDefine7,UserDefine8,UserDefine9,UserDefine10,
							UserDefine11,UserDefine12,UserDefine13,UserDefine14,UserDefine15,UserDefine16,UserDefine17,UserDefine18,UserDefine19,
							UserDefine20, Card.ActDate, Card.ExpDate, Card.NeverExpire,
							Card.CardType, Card.CardStatus, Card.KeyNumber,
                                                        User.ResidentType, User.MainResident,
							User.Ada, User.WEBLevel, User.Exempt, User.Disturb, User.VacationMode, User.VacationStart, User.VacationEnd, User.VacationPhone,
							User.DirectoryListed, User.DirectoryCode, EntryCode.CodeNo, EntryCode.SelectType as stype, EntryCode.no AS EntryCodeNo
                                              FROM User 
                                              LEFT OUTER JOIN Card ON User.No = Card.UserNo AND Card.Site=? 
                                              LEFT OUTER JOIN CardFormat ON Card.CardFormatNo = CardFormat.No
									          LEFT OUTER JOIN EntryCode ON EntryCode.Site = User.Site AND EntryCode.UserNo = User.No
                                             WHERE User.Site=? ORDER BY User.No");
            $list->execute(array($_SESSION['spider_site'], $_SESSION['spider_site']));
            //$list   = $list->fetchAll(PDO::FETCH_ASSOC);

            $filename   = "export_" . date("YmdHis") .".csv";

            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$filename."\"");
            header("Content-Transfer-Encoding: Binary");
            header("Pragma: no-cache; public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");

            echo "#EXPORT_FORMAT_VERSION=" . self::EXPORT_FORMAT_VERSION . "\n";
            //$data = "ID,First Name,Middle Name,Last Name,Card Number,Card Format,Select Type,Access Level,Phone Number,Phone(ext),Cell Phone,E-mail,Threat Level\n";
			echo "Id,First Name,Middle Name,Last Name,Card Number,Card Format,Select Type,Access Level,Phone Number,Phone(ext),Cell Phone,E-mail,Threat Level,"
				."UserDefine1,UserDefine2,UserDefine3,UserDefine4,UserDefine5,UserDefine6,UserDefine7,UserDefine8,UserDefine9,UserDefine10,"
				."UserDefine11,UserDefine12,UserDefine13,UserDefine14,UserDefine15,UserDefine16,UserDefine17,UserDefine18,UserDefine19,UserDefine20,"
				."Activation Date,Expiration Date,Never Expired,Card Type,Card Status,Key Number,"
                                ." Resident Type, CallRollThrough, MainResident ID,"
                                ."Ada,WEBLevel,Exempt,Disturb,VacationMode,VacationStart,VacationEnd,VacationPhone,"
                                ." Directory Listed, Directory Code, Entry Code, Entry Code Type, EntryCode access level\n";

			$LineNo = 1;
            //foreach( $list as $row )
			while($row = $list->fetch(PDO::FETCH_ASSOC))
            {
				$phone_number = $this->create_phone_number_exension($row['Phone']);
				$vacation_phone_number = $this->create_phone_number_exension($row['VacationPhone']);
				
                $data = $row['No'] . ","
                              . '"'.str_replace('"', '""', $row['FirstName']) . '",'
                              . '"'.str_replace('"', '""', $row['MiddleName']) . '",'
                              . '"'.str_replace('"', '""', $row['LastName']) . '",'
                              . '"'.$row['CardNo'] . '",'
                              . '"'.$row['FormatName'] . '",'
                              //. '"'.$this->get_A_level_str($row['CNo']) . '",'
			      . '"'.($row['SelectType'] == '2' ? 'G' : 'I') . '",'
			      . '"'.($row['SelectType'] == '2' ? $this->get_groupaccess_str($row['CNo'], $conn) : $this->get_access_str($row['CNo'], $conn)) . '",'
                              . '"'.$phone_number. '",'
                              . '"'.$row['PhoneExt'] . '",'
                              . '"'.$row['CellPhone'] . '",'
                              . '"'.$row['Email'] . '",'
                              . '"'.$arr_threat[$row['ThreatLevel']] . '",'
                              . '"'.$row['UserDefine1'] . '",'
                              . '"'.$row['UserDefine2'] . '",'
                              . '"'.$row['UserDefine3'] . '",'
                              . '"'.$row['UserDefine4'] . '",'
                              . '"'.$row['UserDefine5'] . '",'
                              . '"'.$row['UserDefine6'] . '",'
                              . '"'.$row['UserDefine7'] . '",'
                              . '"'.$row['UserDefine8'] . '",'
                              . '"'.$row['UserDefine9'] . '",'
                              . '"'.$row['UserDefine10'] . '",'
                              . '"'.$row['UserDefine11'] . '",'
                              . '"'.$row['UserDefine12'] . '",'
                              . '"'.$row['UserDefine13'] . '",'
                              . '"'.$row['UserDefine14'] . '",'
                              . '"'.$row['UserDefine15'] . '",'
                              . '"'.$row['UserDefine16'] . '",'
                              . '"'.$row['UserDefine17'] . '",'
                              . '"'.$row['UserDefine18'] . '",'
                              . '"'.$row['UserDefine19'] . '",'
                              . '"'.$row['UserDefine20'] . '",'
                              . '"'. ($row['NeverExpire'] == 0 ? @date('Y-m-d', $row['ActDate']) : '') . '",'
                              . '"'. ($row['NeverExpire'] == 0 ? @date('Y-m-d', $row['ExpDate']) : '') . '",'
                              . '"'.$row['NeverExpire'] . '",'
                              . '"'.$row['CardType'] . '",'
                              . '"'.$row['CardStatus'] . '",'
                              . '"'.$row['KeyNumber'] . '",'
			      . '"'.($row['ResidentType'] == '0' ? 'M':'C').'",' 
			      . '"'.($row['ResidentType'] == '0' ? $this->get_callrollthrough($row['No'], $conn) : '') . '",'
			      . '"'.$row['MainResident'] . '",' 
			      . '"'.$row['Ada'] . '",' 
			      . '"'.$row['WEBLevel'] . '",' 
			      . '"'.$row['Exempt'] . '",' 
			      . '"'.$row['Disturb'] . '",' 
			      . '"'.$row['VacationMode'] . '",' 
			      . '"'.$row['VacationStart'] . '",' 
			      . '"'.$row['VacationEnd'] . '",' 
			      . '"'.$vacation_phone_number. '",' 
			      . '"'.$row['DirectoryListed'] . '",' 
			      . '"'.$row['DirectoryCode'] . '",' 
			      . '"'.$row['CodeNo'] . '",' 
			      . '"'.($row['stype'] == '2' ? 'G':'I') . '",' 
			      . '"'.($row['stype'] == '2' ? $this->get_entryCode_groupaccess_str($row['EntryCodeNo'], $conn):$this->get_entryCode_access_str($row['EntryCodeNo'], $conn)) . '"' . "\n";


				$LineNo = $LineNo + 1;

				echo $data;
				usleep(1000);
				//flush();
            }
            
            //echo $data;
        }
        else
        {
            exit;
        }
        Log::set_log(NULL, '_exec');
    }
	
	function create_phone_number_exension($phone_number){
			
		if( strpos($phone_number, ",") !== false ) {
			//Comma found in phone number
			
			$extension = explode(",",$phone_number);
			$phone_number = $extension[0];
			$pause = 0;
			
			for($i=1;$i<count($extension);$i++){
				$pause++;
			}
			$extension = end($extension);
			$phone_number = $phone_number."-".$pause."-".$extension;				
		}
		return $phone_number;			
	}

    function to_array_threat()
    {
        $list = $this->conn->prepare("SELECT * FROM ThreatLevel WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['Level']] = $val['ThreatName'];
        }
        return $arr;
    }

    function get_A_level_str($No)
    {
        $arr    = array();
        $list   = $this->conn->prepare("SELECT * FROM AccessLevel WHERE No IN (SELECT AccessLevelNo FROM CardAccessLevel WHERE CardNo = ?) AND Site=?");
        $list->execute(array($No, $_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode('|', $arr);
    }
	
	// ----------------------------------------------------------------------------------
	/**
	  @param $no	EntryCode
	  @param $conn
	*/
	function get_entryCode_groupaccess_str($No, $conn)
	{
        $arr    = array();

        $list   = $conn->prepare("SELECT * FROM EntryCodeAccessLevel WHERE Site=? AND CodeNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site'],ConstTable::GROUP_ACCESS_LEVEL));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode('|', $arr);
	}

    // ----------------------------------------------------------------------------------

    function get_groupaccess_str($No, $conn)
    {
        $arr    = array();

        $list   = $conn->prepare("SELECT * FROM CardAccessLevel WHERE Site=? AND CardNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site'],ConstTable::GROUP_ACCESS_LEVEL));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode('|', $arr);
    }

    // ----------------------------------------------------------------------------------
	/**
	  @param $No EntryCode CodeNo
	*/
	function get_entryCode_access_str($No, $conn)
	{
        $arr    = array();

        $list   = $conn->prepare("SELECT * FROM EntryCodeAccessLevel WHERE Site=? AND CodeNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode('|', $arr);
	}
    // ----------------------------------------------------------------------------------
    function get_access_str($No, $conn)
    {
        $arr    = array();

        $list   = $conn->prepare("SELECT * FROM CardAccessLevel WHERE Site=? AND CardNo = ?");
        $list->execute(array($_SESSION['spider_site'], $No));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['AccessLevelNo'];
        }
        $arr_str    = implode(',', $arr);

        $arr    = array();
        $list   = $conn->prepare("SELECT * FROM AccessLevel WHERE Site=? AND No IN ($arr_str)");
        $list->execute(array($_SESSION['spider_site']));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val )
        {
            $arr[]  = $val['Name'];
        }

        return implode('|', $arr);
    }

	// ----------------------------------------------------------------------------------
	function get_callrollthrough($no, $conn)
	{
		$arr    = array();

        $list   = $conn->prepare("SELECT * FROM CallRollThrough WHERE Site=? AND No = ?");
        $list->execute(array($_SESSION['spider_site'], $no));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);

        foreach( $list as $val ) {
            $arr[]  = $val['CoResidentNo'];
        }

        return implode('|', $arr);
	}

}

