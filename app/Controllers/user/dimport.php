<?php


class dimport extends controller
{
	var $arr_exists    = array('0'=>'Skip', '1'=>'Overwrite');

    // ----------------------------------------------------------------------------------

    function index()
    {
        $this->display($vars);
    }

    function confirm_pw()
	{
		Util::js("show_loading(); $('#form_edit').submit();");
	}

    function import()
    {
		set_time_limit(0);

        $sel    = Input::post('sel');
        $Exists = Input::post('ExistData', '0');

		$max_user	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][2];
		$max_card	= EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][4];

        if( $sel == 'csv' )
        {
            $arr_threat         = $this->to_array_threat();
            $arr_accesslevel    = $this->to_array_accesslevel();
            $arr_accesslevel_group    = $this->to_array_accesslevel_group();
            $arr_cardformat     = $this->to_array_cardformat();

            if( ! is_uploaded_file($_FILES['upload_file']['tmp_name']) )
            {
                Util::alert($lang->menu->error_upload_file);
                Util::redirect("/?c=dimport");
                return FALSE;
            }

            if( substr(strtolower($_FILES['upload_file']['name']), -4) != '.csv' )
            {
                Util::alert($this->lang->addmsg->error_invalid_filetype);
                Util::redirect("/?c=dimport");
                return FALSE;
            }

			/* Import Data Validation -disabled - njani NXG-3742
			if(!$this->import_validation($_FILES['upload_file']['tmp_name'])) {
			   return false;
			}
			*/
			$returnResultFileName="/var/local/returnResult.txt";
			$returnFile = fopen($returnResultFileName,"w");
			fwrite($returnFile,"");//empty returnResult file
			fclose($returnFile);

            //$fileName = $_FILES['upload_file']['name'];
            $fileName = $_FILES['upload_file']['tmp_name'];
			$this->systemLogger(IMPORT_CSV." csvimportuser ".$Exists." ".$fileName." ".$returnResultFileName." ".$max_user." ".$max_card);
			exec( IMPORT_CSV." csvimportuser ".$Exists." ".$fileName." ".$returnResultFileName." ".$max_user." ".$max_card);

            $file = fopen($returnResultFileName, "r");
            $members = array();

            while (!feof($file)) {
            $members[] = fgets($file);
            }

            fclose($file);

            $index=0;
            $errorNo=0;
            $errorContent="";
            foreach ($members as $line){

                    if ($index==0)
                    {
                        $result = $line;
                    }
                    elseif($index==1)
                    {
                        $errorNo = $line;
                    }else
                    {
                        $line = trim(preg_replace('/\s\s+/', ' ', $line));
                        $errorContent.= $line."\\n";
                    }
                    $index++;
            }
		if (!isset($result))
		{
			Util::alert("CSV Import Failed, No results available");
			$this->systemLogger("CSV Import Failed, No results available");
		}
		if (strlen($result) == 0)
		{
			Util::alert("CSV Import Failed, No results captured");
			$this->systemLogger("CSV Import Failed, No results captured");
		}
                elseif ($result == 0){
                        Util::alert("CSV Import Successful");
                }else{
			if ($errorNo === NULL || $errorNo==="" )
			{
				Util::alert("CSV Import Failed, No Error Captured");
			}
			else
			{
				Util::alert("CSV Import Failed");
			}

                        /* PLEASE DO NOT USE UtiL:ALERT IT DOES NOT WORK IN THIS CASE -njani*/
                        echo '<script language="javascript">';
                        echo "alert('".$errorContent."');</script>";
                        /* PLEASE DO NOT USE UtiL:ALERT IT DOES NOT WORK IN THIS CASE-njani*/
                        $this->systemLogger("importCSV ".$errorContent[1]);
                }
                Util::redirect("/?c=dimport");
        }


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

    function to_array_accesslevel()
    {
        $list = $this->conn->prepare("SELECT * FROM AccessLevel WHERE Site=?");
        $list->execute(array($_SESSION['spider_site']));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }
        return $arr;
    }

    function to_array_accesslevel_group()
    {
        $list = $this->conn->prepare("SELECT * FROM GroupTable WHERE Site=? AND GroupKind=?");
        $list->execute(array($_SESSION['spider_site'],ConstTable::GROUP_ACCESS_LEVEL));
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }
        return $arr;
    }

    function to_array_cardformat()
    {
        $list = $this->conn->prepare("SELECT * FROM CardFormat");
        $list->execute();
        $list = $list->fetchAll(PDO::FETCH_ASSOC);

        $arr    = array();
        foreach( $list as $key=>$val)
        {
            $arr[$val['No']] = $val['Name'];
        }
        return $arr;
    }

    // ----------------------------------------------------------------------------------

    function to_timestamp($date="", $hour=0, $min=0, $sec=0)
    {
        if ($date != "")
        {
        	$timeval = explode('-', trim($date));

        	if (count($timeval) < 3)
        	{
        		return "";
        	}

            $month  = $timeval[1];
            $day    = $timeval[2];
            $year   = $timeval[0];
            return mktime($hour, $min, $sec, $month, $day, $year);
        }
        return $date;
    }


	// -----------------------------------------------------------------------------------
	// Import ��� ����Ÿ�� ���� �� ����Ÿ�� ��ȿ���� ���� �Ѵ�.
	// -----------------------------------------------------------------------------------
	function import_validation($csvFileName)
	{
        $handle = @fopen($csvFileName, 'r');
		if(!$handle) {
			Util::alert($lang->menu->error_upload_file);
			Util::redirect("/?c=dimport");
			return false;
		}

            // Also skip the export file version line
		if(($row = fgetcsv($handle)) == false) return true;
	    /* 1��° Line�� header Name ���� �̹Ƿ� Skip �Ѵ� */
		if(($row = fgetcsv($handle)) == false) return true;

		while(($row = fgetcsv($handle)) !== false) {
		   /* Max name Length 21 Byte */
           //if(strlen($row[1].$row[2].$row[3]) > 21) {
			 //Util::alert($this->lang->addmsg->error_username_length." : ".$row[1].$row[2].$row[3]);
			 //Util::redirect("/?c=dimport");
			 //return false;
		   //}

		   /* Phone Number Format Check */
		   if(!empty($row[8])) {
			   if(!$this->is_check_callnumber($row[8])) {
				 Util::alert($this->lang->addmsg->error_phone_format." : ".$row[8]);
				 Util::redirect("/?c=dimport");
				 return false;
			   }
		   }

		   /* EntryCode Format, Length Check */
		   if(!empty($row[41])) {
			   if(!$this->is_check_number($row[41]) || strlen($row[41]) > 8) {
				 Util::alert($this->lang->addmsg->error_entrycode_not_number." : ".$row[41]);
				 Util::redirect("/?c=dimport");
				 return false;
			   }
		   }

		   /* Directory Code Format, Length Check */
		   if(!empty($row[40])) {
			   if(!$this->is_check_number($row[40]) || strlen($row[40]) > 5) {
				 Util::alert($this->lang->addmsg->error_directorycode_not_number." : ".$row[40]);
				 Util::redirect("/?c=dimport");
				 return false;
			   }
		   }

		   /* Email Format, Length Check */
		   if(!empty($row[11])) {
			   if (!filter_var($row[11], FILTER_VALIDATE_EMAIL) || strlen($row[11]) > 256) {
				 Util::alert($this->lang->addmsg->error_email_address." : ".$row[11]);
				 Util::redirect("/?c=dimport");
				 return false;
			   }
		   }
		}

		@fclose($handle);
		return true;
    }


/*
   Description : value���� ���ڷθ� �Ǿ� �ִ��� �˻�
*/

function is_check_number($value)
{
  if(ereg("[^0-9]",$value)) return (false);
    else return (true);
}

/*
   Description : value���� ��ȭ��ȣ �� ������ȣ �������� ����
       example :  800-111-2222 ���� 5555 => 8001112222,5555
*/
function is_check_callnumber($value)
{
  if(ereg("[^0-9,0-9]",$value)) return (false);
    else return (true);
}

}
