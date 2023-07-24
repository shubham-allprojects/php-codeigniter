<?php
namespace App\Libraries;
class Log
{
    public static $fcode = array(
        'alarm_set'=>'01',
        'cam_setup'=>'02',
        'cam_view'=>'02',
        'card_holder'=>'03',
        'card'=>'04',
        'cardformat'=>'05',
        'alevel'=>'06',
        'event'=>'07',
        'atlevel'=>'08',
        'report1'=>'09',
        'schedule'=>'10',
        'holiday'=>'11',
        'door'=>'12',
        'ainput'=>'13',
        'aoutput'=>'14',
        'cfloor'=>'15',
        'ctrl'=>'16',
        'update'=>'17',
        'backup'=>'18',
        'restore'=>'19',
        'reset'=>'20',
        'fdefault'=>'21',
        'dexport'=>'22',
        'dimport'=>'23',
        'webuser'=>'24',
        'logmanagement'=>'25',
        'tlevel'=>'26',
        'ipset'=>'27',
        'ddns'=>'28',
        'ftp'=>'29',
        'updatesev'=>'30',
        'sms'=>'31',
        'smtp'=>'32',
        'client_list'=>'33',
        'door_control'=>'34',
        'aux_output_control'=>'35',
        'alarm_map'=>'36',
        'skinchange'=>'37',
        'unlockschedule'=>'38',
        'elevator'=>'39',
        'userdefine'=>'40',
        'userrole'=>'41',
        'smartreport'=>'42',
        'groupuser'=>'43',
        'groupdoor'=>'44',
        'groupcamera'=>'61',
        'groupfloor'=>'45',
        'groupschedule'=>'46',
        'groupaccess'=>'47',
        'server_client'=>'48',
        'site'=>'49',
        'sitedevice'=>'50',
        'user'=>'51',
        'dvr_setup'=>'52',
        'dvr_view'=>'52',
        'license'=>'53',
        'smartreport_set'=>'54',
        'timesvr'=>'55',
        'lostcard'=>'56',
        'client'=>'57',
        'event_code'=>'58',
        'elevatoraction'=>'59',
        'rmr'=>'60',
        'onetime_unlockschedule'=>'62',
        'region'=>'63',
		'Entrata'=>'64',
    );
    public static $scode = array('insert'=>'01', 'delete'=>'02', 'update'=>'03', 'send'=>'04', '_exec'=>'05', 'ack'=>'06', 'login'=>'07', 'logout'=>'08', 'reset'=>'09', '_execfail'=>'10', 'm_unlock'=>'11', 'unlocked'=>'12', 'locked'=>'13', 'on'=>'14', 'off'=>'15', 'delete_all'=>'16', 'replace'=>'17', 'merge'=>'18', 'clear_data'=>'19');

    public function check_log_full()
    {
        $conn   = $GLOBALS['conn_log'];

/*
        $nMaxLog = Util::GetLimitCount(ConstTable::MAX_TRANSACTION_1, 
	                                   ConstTable::MAX_TRANSACTION_2, 
    	                               ConstTable::MAX_TRANSACTION_3);
*/
		$nMaxLog	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][10];

		$count  = $conn->prepare("SELECT MAX(No) FROM Log");
        $count->execute();
        $count  = $count->fetchColumn(); 
        
        if ($nMaxLog <= $count)
        	return TRUE;

		return FALSE;
    }
    
    static public function set_log($f=NULL, $s=NULL, $Message='')
    {

        $Site       = $_SESSION['spider_site'];
        $DeviceName = $_SERVER['REMOTE_ADDR'];
		$Port       = '';
        $EventCode  = Log::get_eventcode($f, $s);
		$CardNo     = '';
        $UserNo     = $_SESSION['spider_userno'];
        $UserName   = $_SESSION['spider_id'];;
		$Ack        = '0';
		$Type		= '0';
		$Message    = '';
       
		exec(SPIDER_COMM." log '{$Site}' '{$DeviceName}' '{$Port}' '{$EventCode}' '{$CardNo}' '{$UserNo}' '{$UserName}' '{$Ack}' '{$Type}' '{$Message}'");
    }

    static public function set_log_message($message)
    {
        Log::set_log(NULL, NULL, $message);
    }
    
    static public function set_log_id($f=NULL, $s=NULL, $id=NULL)
    {
        //$conn   = $GLOBALS['conn_log'];

/*
        $nMaxLog = Util::GetLimitCount(ConstTable::MAX_TRANSACTION_1, 
	                                   ConstTable::MAX_TRANSACTION_2, 
    	                               ConstTable::MAX_TRANSACTION_3);
*/
/*
		$nMaxLog	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][10];

		$count  = $conn->prepare("SELECT MAX(No) FROM Log");
        $count->execute();
        $count  = $count->fetchColumn(); 
        
        if ($nMaxLog < $count)
        	return;
*/      
        //$DeviceName = $_SERVER['REMOTE_ADDR'];
        //$EventCode  = Log::get_eventcode($f, $s);
        //$User       = $id;
        //$User_no    = $_SESSION['spider_userno'];
        //$UserImg    = $_SESSION['spider_userimg'];
        //$Date       = time();

        //$sth = $conn->prepare("INSERT INTO Log (DeviceName,EventCode,UserName,UserNo,UserImage,Date,ClientDate,Site) VALUES (?,?,?,?,?,?,?,?)");
        //$sth->execute(array($DeviceName,$EventCode,$User,$User_no,$UserImg,$Date,$Date,	$_SESSION['spider_site']));

        $Site       = session()->get('spider_site');
        $DeviceName = $_SERVER['REMOTE_ADDR'];
		$Port       = '';
        $EventCode  = Log::get_eventcode($f, $s);
		$CardNo     = '';
        $UserNo     = session()->get('spider_userno');
        $UserName   = $id;
		$Ack        = '0';
		$Type		= '0';
		$Message    = '';

		exec(SPIDER_COMM." log '{$Site}' '{$DeviceName}' '{$Port}' '{$EventCode}' '{$CardNo}' '{$UserNo}' '{$UserName}' '{$Ack}' '{$Type}' '{$Message}'");
    }
    
    public function set_log_device($f=NULL, $s=NULL, $type='', $_port='', $device_name=NULL, $Message='')
    {
        $conn   = $GLOBALS['conn_log'];

		if(empty($device_name)) {
			$device_name = $_SERVER['REMOTE_ADDR'];
		}

/*
        $nMaxLog = Util::GetLimitCount(ConstTable::MAX_TRANSACTION_1, 
	                                   ConstTable::MAX_TRANSACTION_2, 
    	                               ConstTable::MAX_TRANSACTION_3);
*/
/*
		$nMaxLog	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][10];

		$count  = $conn->prepare("SELECT MAX(No) FROM Log");
        $count->execute();
        $count  = $count->fetchColumn(); 
        
        if ($nMaxLog <= $count)
        	return;
*/
        //$DeviceName = $_SERVER['REMOTE_ADDR'];
        //$EventCode  = Log::get_eventcode($f, $s);
        //$User       = $_SESSION['spider_id'];
        //$User_no    = $_SESSION['spider_userno'];
        //$UserImg    = $_SESSION['spider_userimg'];
        //$Date       = time();

        //$sth = $conn->prepare("INSERT INTO Log (DeviceName,EventCode,UserName,UserNo,UserImage,Date,ClientDate,Site,Type,Port) VALUES (?,?,?,?,?,?,?,?,?,?)");
        //$sth->execute(array($DeviceName,$EventCode,$User,$User_no,$UserImg,$Date,$Date,	$_SESSION['spider_site'],$type,$port));

        $Site       = $_SESSION['spider_site'];
        $DeviceName = $device_name;
		$Port       = $_port;
        $EventCode  = Log::get_eventcode($f, $s);
		$CardNo     = '';
        $UserNo     = $_SESSION['spider_userno'];
        $UserName   = $_SESSION['spider_id'];
		$Ack        = '0';
		$Type		= $type;
		//$Message    = '';

		exec(SPIDER_COMM." log '{$Site}' '{$DeviceName}' '{$Port}' '{$EventCode}' '{$CardNo}' '{$UserNo}' '{$UserName}' '{$Ack}' '{$Type}' '{$Message}'");
    }

    static public function get_eventcode($f=NULL, $s=NULL)
    {
        $router = service('router');
        if( $f == NULL )    $controller = strtolower($router->controllerName());
        if( $s == NULL )    $method = strtolower($router->methodName());
        
        $pieces = explode("\\", $controller);
        $f = array_pop($pieces);
        $s = $method;

        $fcode = self::$fcode[$f];
        $scode = self::$scode[$s];

        if( empty($fcode) ) $fcode = '99';
        if( empty($scode) ) $scode = '99';

        return "1".$fcode.$scode;
    }

    public function get_ack_by_eventcode($eventcode)
    {
        $conn   = $GLOBALS['conn'];

		$ack  = $conn->prepare("SELECT Ack FROM EventCode WHERE EventCode=? LIMIT 1");
        $ack->execute(array($eventcode));
        $ack  = $ack->fetchColumn();

		return $ack == '1' ? '1' : '0';
    }
/*
    public function insert($message=NULL)
    {
        $conn   = $GLOBALS['conn_log'];

        $Event          = '9';
        $Event_Detail   = '1';  
        $Action         = '';
        $Card_no        = '';
        $User_no        = $_SESSION['spider_name'];
        $content        = Log::get_message($message);
        $date           = time();
        $Ack            = '';
        $chk            = '';
        $Device         = 'WEB';

        $sth    = $conn->prepare("INSERT INTO Log (Type,Port,DeviceName,EventCode,CardNo,UserNo,UserName,Date,Ack,Site) VALUES (?,?,?,?,?,?,?,?,?,?)");
        
        $sth->execute(array($Event,$Action,$Device,$content,$Card_no,$User_no,$User_no,$date,$Ack,$_SESSION['spider_site']));
        $sth->execute($values);
    }

    public function update($message=NULL)
    {
        $conn   = $GLOBALS['conn_log'];

        $Event          = '9';
        $Event_Detail   = '2';
        $Action         = '';
        $Card_no        = '';
        $User_no        = $_SESSION['spider_name'];
        $content        = Log::get_message($message);
        $date           = time();
        $Ack            = '';
        $chk            = '';
        $Device         = 'WEB';

        $sth    = $conn->prepare("INSERT INTO Log (Type,DeviceName,Port,EventCode,CardNo,UserNo,UserName,Date,Ack,Site) VALUES (?,?,?,?,?,?,?,?,?,?)");

        $values = array($Event,$Device,$Action,$content,$Card_no,$User_no,$User_no,$date,$Ack,$_SESSION['spider_site']);
        $sth->execute($values);
    }

    public function delete($message=NULL)
    {
        $conn   = $GLOBALS['conn_log'];

        $Event          = '9';
        $Event_Detail   = '3';
        $Action         = '';
        $Card_no        = '';
        $User_no        = $_SESSION['spider_name'];
        $content        = Log::get_message($message);
        $date           = time();
        $Ack            = '';
        $chk            = '';
        $Device         = 'WEB';

        $sth    = $conn->prepare("INSERT INTO Log (Type,`Port`,DeviceName,EventCode,CardNo,UserNo,UserName,Date,Ack,Site) VALUES (?,?,?,?,?,?,?,?,?,?)");

        $values = array($Event,$Action,$Device,$content,$Card_no,$User_no,$User_no,$date,$Ack,$_SESSION['spider_site']);
        $sth->execute($values);
    }
*/
    private function get_message($message=NULL)
    {
        if( $message == NULL )
        {
            $message    = 'CLASS:'. $GLOBALS['class'] .', METHOD:'. $GLOBALS['method'];
        }

        return $message;
    }

	public static function is_error_log_table()
	{
		exec(SPIDER_COMM." checklogdb", $output);

		foreach( $output as $line )
		{
			$line = trim($line);
			$name_split = explode(':', $line);

			if( count($name_split) > 0 && $name_split[0] == 'LOG DB' ) {
				if($name_split[1] == 'SUCCESS') {
					return FALSE;
				} else {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

    public static function query_history($query, $db=1)
    {
        $conn   = $GLOBALS['conn'];

        $sth    = $conn->prepare("INSERT INTO DBQueryInfo (Idseq, QueryData, DbKind) VALUES ((SELECT MAX(Idseq) + 1 FROM DBQueryInfo), ?, ?)");
        $sth->executeSilent(array($query, $db));

        $max  = $conn->prepare("SELECT MAX(Idseq) FROM DBQueryInfo");
        $max->execute();
        $max  = $max->fetchColumn();

        exec(SPIDER_COMM." send query {$max}");
    }
}

// 2023-07-04
// $log    = $conn_log->prepare("SELECT No,UserNo,strftime('%Y-%m-%d <br> %H:%M:%S',datetime(date,'unixepoch')) as dd, Type, EventCode FROM Log order by no desc limit 16");
// $log->execute();
// $log    = $log->fetchAll(PDO::FETCH_ASSOC);

?>