<?php
namespace App\Controllers;

class User extends BaseController
{

    // ----------------------------------------------------------------------------------

    function index()
    {
        $vars['list'] = json_encode($this->to_list());
        $this->display($vars, 'user', ['header' => 'header', 'footer' => 'footer']);
    }

    // ----------------------------------------------------------------------------------
    function to_list(){
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');

        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM User WHERE Site=?");
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? ORDER BY No DESC");
            $list->execute(array($_SESSION['spider_site']));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM User WHERE Site=? AND $field LIKE ?");
            $count->execute(array($_SESSION['spider_site'], "%$word%"));
            $count  = $count->fetchColumn();

            $list   = $this->conn->prepare("SELECT * FROM User WHERE Site=? AND $field LIKE ? ORDER BY No DESC");
            $list->execute(array($_SESSION['spider_site'], "%$word%"));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['count']        = $count;
        $result['list']         = $list;

        return $result;
    }
    
    // ----------------------------------------------------------------------------------

    function find()
    {
        $field  = $this->input::get('f');
        $word   = $this->input::get('w');
		$offset = $this->input::get('offset', 0);

        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM User WHERE Site=?");
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $list   = $this->conn->prepare("SELECT No, FirstName, MiddleName, LastName FROM User WHERE Site=? ORDER BY No ASC LIMIT {$offset}, 1000");
            $list->execute(array($_SESSION['spider_site']));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
			/* Condition added for card holder group search by firstname and lastname -Rakesh Shetty */
			if(strpos($field, '|') !== false){
				$search_column = explode("|",$field);
				$search_condition = $search_column[0]." LIKE ? OR ".$search_column[1]." LIKE ?";
				$search_value = "%".$word."%";
			}else{
				$search_condition = $field." LIKE ?";
				$search_value = "";
			}
			
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM User WHERE Site=? AND $search_condition");
            $count->execute(array($_SESSION['spider_site'], "%".$word."%", $search_value));
            $count  = $count->fetchColumn();

            $list   = $this->conn->prepare("SELECT No, FirstName, MiddleName, LastName FROM User WHERE Site=? AND $search_condition ORDER BY No ASC LIMIT {$offset}, 1000");
            $list->execute(array($_SESSION['spider_site'], "%".$word."%", $search_value));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }

        $result['field']        = $field;
        $result['word']         = $word;
        $result['count']        = $count;
        $result['list']         = $list;
        $result['offset']       = $offset;

        echo json_encode($result);
    }

    // ----------------------------------------------------------------------------------

    function view(){
        $no = $this->input::get('no');
        $list   = $this->conn->prepare("SELECT * FROM Card WHERE WHREE Site=? AND UserNo=? ORDER BY No DESC");
        $list->execute(array($_SESSION['spider_site'], $no));
        $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($list);
        
    }

    // ----------------------------------------------------------------------------------

}


?>