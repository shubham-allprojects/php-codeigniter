<?php
namespace App\Libraries;

if( !defined('IS_AJAX') )
    define('IS_AJAX',   (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));

class Util
{
	public function GetSessionCount()
	{
		$dir=opendir("/tmp"); 
		$onSession = 0; 
		while(($read=readdir($dir)) !== false)
		{
			$when_read = explode("_",$read); 
		    $read0 = $when_read[0]; 
		    if ( $read0 == "sess" ) 
		    { 
		    	$fh = fopen('/tmp/'.$read, 'r'); 
	            while (!feof($fh)) 
	            { 
	            	$vContent = fread($fh,2098); 
	            } 
	            fclose($fh); 
	            
	            // ������ �Ǹ� spider_key|s:64 �� �����Ѵ�.
	            if (substr($vContent, 0, 15) === "spider_key|s:64") { $onSession++; } 
		    } 
		}
		return $onSession;
	}

	public function GetLimitCount($num1, $num2, $num3)
	{
		if ($_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL)
    		return $num1;
    	if ($_SESSION['spider_model'] == ConstTable::MODEL_ELITE)
    		return $num2;
    	if ($_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE)
    		return $num3;
//ADD CJMOON 2017.04.18
		if ($_SESSION['spider_model'] == ConstTable::MODEL_TE_STANDALONE)
    		return $num3;
		if ($_SESSION['spider_model'] == ConstTable::MODEL_TE_SERVER)
    		return $num3;
        
        return 0;
    }
    
	static public function GetRecordCount($table, $where="")
	{
		$count  = $this->conn->prepare("SELECT COUNT(*) FROM ".$table." ".$where);
        $count->execute();
        $count  = $count->fetchColumn(); 
        
        return $count;
    }
    
    public function GetRecordCountSet($table, $where, $fields)
	{
		$count  = $this->conn->prepare("SELECT COUNT(*) FROM ".$table." ".$where);
        $count->execute($fields);
        $count  = $count->fetchColumn(); 
        
        return $count;
    }
    
	public function GetRecordNo($table, $where="")
	{
		$count  = $this->conn->prepare("SELECT No FROM ".$table." ".$where);
        $count->execute();
        $count  = $count->fetchColumn(); 
        
        return $count;
    }    

	public function GetRecordName($table, $no)
	{
		$name  = $this->conn->prepare("SELECT Name FROM ".$table." WHERE Site = ? AND No = ? ");
        $name->execute(array($_SESSION['spider_site'], $no));
        $name  = $name->fetchColumn(); 
        
        return $name;
    }    

	public function GetCardHolderName($no)
	{
		$cardholder  = $this->conn->prepare("SELECT FirstName, LastName FROM User WHERE Site = ? AND No = ? ");
        $cardholder->execute(array($_SESSION['spider_site'], $no));
        if( $cardholder = $cardholder->fetch(PDO::FETCH_ASSOC) ) {
            return $cardholder['FirstName'] .' '. $cardholder['LastName'];
        }
        
        return '';
    }    

	public function GetCardName($no)
	{
		$card  = $this->conn->prepare("SELECT UserNo, CardNo, CardFormatNo FROM Card WHERE Site = ? AND No = ? ");
        $card->execute(array($_SESSION['spider_site'], $no));
        if( $card = $card->fetch(PDO::FETCH_ASSOC) ) {
            $cardholder_name = Util::GetCardHolderName($card['UserNo']);
            $card_name       = Util::GetCardFormatName($card['CardFormatNo']);
            return $cardholder_name .','. $card_name .','. $card['CardNo'];
        }
        
        return '';
    }    

	public function GetCardFacilityCode($no)
	{
		$cardformat  = $this->conn->prepare("SELECT FacilityCode FROM CardFormat WHERE No = ? ");
        $cardformat->execute(array($no));
        if( $cardformat = $cardformat->fetch(PDO::FETCH_ASSOC) ) {
            $facilityCode = $cardformat['FacilityCode'];
            return $facilityCode;
        }
        
        return '';
    }    
	
	public function GetEventCodeName($no)
	{
		$name  = $this->conn->prepare("SELECT Name FROM EventCode WHERE No = ? ");
        $name->execute(array($no));
        $name  = $name->fetchColumn(); 
        
        return $name;
    }    

	public function GetCardFormatName($no)
	{
		$name  = $this->conn->prepare("SELECT Name FROM CardFormat WHERE No = ? ");
        $name->execute(array($no));
        $name  = $name->fetchColumn(); 
        
        return $name;
    }    

    public function redirect($url, $wrap=NULL)
    {
        if( IS_AJAX || headers_sent() )     Util::js('window.location.href="'.$url.'";', TRUE, $wrap);
        else                                header("Refresh:0;url=".$url);
        exit;
    }

    static public function reload($wrap=NULL)
    {
        Util::js('window.location.reload();', TRUE, $wrap);
        exit;
    }
    
    public function topreload($wrap=NULL)
    {
        Util::js('top.location.reload();', TRUE, $wrap);
        exit;
    }
    
    public function back($wrap=NULL)
    {
        Util::js('window.history.back();', TRUE, $wrap);
        exit;
    } 

    static public function alert($message, $exit=FALSE, $wrap=NULL)
    {
        if( is_array($message) )    $message = implode('\n', $message);
		if( $wrap === NULL ) {
			if( IS_AJAX ) {
				if( $_SERVER['HTTP_ACCEPT'] == '*/*' ) {
					$wrap = FALSE;
				} elseif( strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') > -1 ) {
					$wrap = FALSE;
					if( strpos($_SERVER['HTTP_ACCEPT'], 'application/json') > -1 ) {
						//$wrap = FALSE;
						$result = array();
						$result['errors'][0] = $message;
						echo json_encode($result);
						exit;
					}
				} else {
					$wrap = TRUE;
				}
			} else {
				$wrap = TRUE;
			}
		}

        Util::js('alert("'.$message.'");', $exit, $wrap);
    }
    
    public function DebugOutput($message)
    {
        $fHandle = fopen("/spider/web/debug.txt", "a");
        fwrite($fHandle, $message);
        fclose($fHandle);       
    }
    
    public function DebugOutputLine($message)
    {
        Util::DebugOutput($message."\n");
    }
    
    static public function js($cmd, $exit=FALSE, $wrap=NULL)
    {
        //if( $wrap === NULL )    $wrap = IS_AJAX ? FALSE : TRUE;
		if( $wrap === NULL ) {
			if( IS_AJAX ) {
				if( $_SERVER['HTTP_ACCEPT'] == '*/*' ) {
					$wrap = FALSE;
				} elseif( strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') > -1 ) {
					$wrap = FALSE;
					if( strpos($_SERVER['HTTP_ACCEPT'], 'application/json') > -1 ) {
						$wrap = FALSE;
					}
				} else {
					$wrap = TRUE;
				}
			} else {
				$wrap = TRUE;
			}
		}
        
        if( $wrap )     
            echo '<script type="text/javascript">'.$cmd.'</script>';
        else            
            echo $cmd;
        
        if( $exit )     exit;
    }

	public function get_diskspace()
	{
		exec(SPIDER_COMM . " df", $result);

		$space = array(
			'total' => 0,
			'available' => 0,
			'database' => 0,
			'image' => 0,
			'system' => 0
		);

		foreach( $result as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$arr_space = explode(',', $action[1]);
				$space = array(
					'total' => (int)trim($arr_space[0]),
					'available' => (int)trim($arr_space[1]),
					'database' => (int)trim($arr_space[2]),
					'image' => (int)trim($arr_space[3])
				);
				$space['system'] = ($space['total'] - $space['available'] - $space['database'] - $space['image']);

				return $space;
			}
		}

		return FALSE;
	}

	public function vaild_diskspace()
	{
		$space = Util::get_imgspace();

		if( $space['use'] < (1024 * 1024 * 100) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_imgspace()
	{
		exec(SPIDER_COMM . " dfimg", $result);

		$space = array(
			'use' => 0
		);

		foreach( $result as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$arr_space = explode(',', $action[1]);
				$space = array(
					'use' => (int)trim($arr_space[0]),
				);

				return $space;
			}
		}

		return FALSE;
	}

	public function get_userspace()
	{
		exec(SPIDER_COMM . " dfuserimg", $result);

		$space = array(
			'total' => 0,
			'available' => 0,
			'use' => 0
		);

		foreach( $result as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$arr_space = explode(',', $action[1]);
				$space = array(
					'total' => (int)trim($arr_space[0]),
					'available' => (int)trim($arr_space[1]),
					'image' => (int)trim($arr_space[2])
				);
				$space['used'] = ($space['total'] - $space['available'] - $space['image']);

				return $space;
			}
		}

		return FALSE;
	}

	public function get_sdspace()
	{
		exec(SPIDER_COMM . " dfsd", $result);

		$space = array(
			'total' => 0,
			'available' => 0,
			'use' => 0
		);

		foreach( $result as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$arr_space = explode(',', $action[1]);
				$space = array(
					'total' => (int)trim($arr_space[0]),
					'available' => (int)trim($arr_space[1])
				);
				$space['use'] = ($space['total'] - $space['available']);

				return $space;
			}
		}

		return FALSE;
	}

    public function get_logstatus()
	{
		exec(SPIDER_COMM . " logstatus", $result);

		$space = array(
			'total' => 0,
			'use' => 0,
			'available' => 0,
			'backup' => 0,
			'nobackup' => 0
		);

		foreach( $result as $line )
		{
			$line		= strtolower(trim($line));
			$action		= explode(':', $line);
			$status		= trim(@$action[0]);

			if( strpos($status, 'result') === 0 ) {
				$arr_space = explode(',', $action[1]);
				$space = array(
					'total' => (int)trim($arr_space[0]),
					'use' => (int)trim($arr_space[1]),
					'backup' => (int)trim($arr_space[2])
				);
				$space['use'] = min($space['total'], $space['use']);
				$space['backup'] = min($space['total'], $space['backup']);
				$space['available'] = $space['total'] - $space['use'];
				$space['nobackup'] = $space['use'] - $space['backup'];

				return $space;
			}
		}

		return FALSE;
	}

    
    public function get_sysrpt_mktime()
	{
		$time  = $this->net_work->prepare("SELECT strftime('%m-%d-%Y %H:%M:%S',datetime(mkDate,'unixepoch')) FROM SystemInfo WHERE No = 1");
        $time->execute();
        $time  = $time->fetchColumn(); 
        
        return $time;
    }    
    
	public function parse_search_string($str)
	{
		$str = preg_replace('/^\*/', '%', $str);
		$str = preg_replace('/\*$/', '%', $str);
		return $str;
	}

	public function get_file_backup_db()
	{
		$file = '/spider/database/Spider-3.db';
		if( !file_exists($file) ) {
			$file = '/spider/database/Spider-2.db';
			if( !file_exists($file) ) {
				$file = '/spider/database/Spider-1.db';
				if( !file_exists($file) ) {
					$file = '/spider/database/Spider.db';
				}
			}
		}

		return $file;
	}

	public function get_file_backup_log_db()
	{
		$file = '/spider/database/SpiderLog-3.db';
		if( !file_exists($file) ) {
			$file = '/spider/database/SpiderLog-2.db';
			if( !file_exists($file) ) {
				$file = '/spider/database/SpiderLog-1.db';
				if( !file_exists($file) ) {
					$file = '/spider/database/SpiderLog.db';
				}
			}
		}

		return $file;
	}

	public function get_connect_backup_db()
	{
		$file = Util::get_file_backup_db();
		return new PDO('sqlite:'.$file);
	}

	public function get_connect_backup_log_db()
	{
		$file = Util::get_file_backup_log_db();
		return new PDO('sqlite:'.$file);
	}

    // ----------------------------------------------------------------------------------

    function getFlashData($name)
    {
        $flash_data = unserialize($_SESSION['flash_data']);
        if( !is_array($flash_data) ) {
            $flash_data = array();
        }

        return array_key_exists($name, $flash_data) ? $flash_data[$name] : NULL;
    }

    // ----------------------------------------------------------------------------------

    function setFlashData($name, $value)
    {
        $flash_data = unserialize($_SESSION['flash_data']);
        if( !is_array($flash_data) ) {
            $flash_data = array();
        }

        $flash_data[$name] = $value;
        $_SESSION['flash_data'] = serialize($flash_data);
        session_write_close();
    }

}


/* End of file Util.php */