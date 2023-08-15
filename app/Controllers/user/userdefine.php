<?php 


class userdefine extends controller
{
    // ----------------------------------------------------------------------------------
    var $max_field = 0;

    function index()
    {
    	$this->max_field = Util::GetLimitCount(ConstTable::MAX_USERDEFINE_1, 
    	                                       ConstTable::MAX_USERDEFINE_2, 
    	                                       ConstTable::MAX_USERDEFINE_3);
    		
        $this->display();
    }

    function select()
    {
        $field  = Input::get('f');
        $word   = Input::get('w');
        $page   = Input::get('p', 1);
        $view   = Input::get('v');

        $page_config    = $GLOBALS['page_config'];
        $pagination     = new Pagination();
        
        $count  = $this->conn->prepare("SELECT COUNT(*) FROM UserDefined WHERE Site=?");
        $count->execute(array($_SESSION['spider_site']));
        $count  = $count->fetchColumn(); 
        
        if ($count < 1)
        {
	        $sth = $this->conn->prepare("INSERT INTO UserDefined (Site,No,User1,User2,User3,User4,User5,User6,User7,User8,User9,User10,User11,User12,User13,User14,User15,User16,User17,User18,User19,User20) VALUES ( ?,1,'','','','','','','','','','','','','','','','','','','','')");
	        $values = array($_SESSION['spider_site']);
	        $sth->execute($values);
        }
        
        if( empty($field) || empty($word) )
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM UserDefined WHERE Site=?");
            $count->execute(array($_SESSION['spider_site']));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM UserDefined WHERE Site=? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $count  = $this->conn->prepare("SELECT COUNT(*) FROM UserDefined WHERE Site=? AND $field LIKE ?");
            $count->execute(array($_SESSION['spider_site'], "%$word%"));
            $count  = $count->fetchColumn();

            $page_config['current_page']    = $page;
            $page_config['total_row']       = $count;
            $pagination->init($page_config);

            $list   = $this->conn->prepare("SELECT * FROM UserDefined WHERE Site=? AND $field LIKE ? ORDER BY No DESC LIMIT ?, ?");
            $list->execute(array($_SESSION['spider_site'], "%$word%", $pagination->offset, $pagination->row_size));
            $list   = $list->fetchAll(PDO::FETCH_ASSOC);
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

    
    function update()
    {
        $No         = Input::post('No');
        $User1      = strip_tags(Input::post('User1'));
        $User2      = strip_tags(Input::post('User2'));
        $User3      = strip_tags(Input::post('User3'));
        $User4      = strip_tags(Input::post('User4'));
        $User5      = strip_tags(Input::post('User5'));
        $User6      = strip_tags(Input::post('User6'));
        $User7      = strip_tags(Input::post('User7'));
        $User8      = strip_tags(Input::post('User8'));
        $User9      = strip_tags(Input::post('User9'));
        $User10     = strip_tags(Input::post('User10'));
        $User11     = strip_tags(Input::post('User11'));
        $User12     = strip_tags(Input::post('User12'));
        $User13     = strip_tags(Input::post('User13'));
        $User14     = strip_tags(Input::post('User14'));
        $User15     = strip_tags(Input::post('User15'));
        $User16     = strip_tags(Input::post('User16'));
        $User17     = strip_tags(Input::post('User17'));
        $User18     = strip_tags(Input::post('User18'));
        $User19     = strip_tags(Input::post('User19'));
        $User20     = strip_tags(Input::post('User20'));
        
        if (empty($No))
        	$No = 1;

        $sth    = $this->conn->prepare("UPDATE UserDefined SET User1=?,User2=?,User3=?,User4=?,User5=?,User6=?,User7=?,User8=?,User9=?,User10=?,User11=?,User12=?,User13=?,User14=?,User15=?,User16=?,User17=?,User18=?,User19=?,User20=? WHERE Site=? AND No=?");
        $values = array($User1,$User2,$User3,$User4,$User5,$User6,$User7,$User8,$User9,$User10,$User11,$User12,$User13,$User14,$User15,$User16,$User17,$User18,$User19,$User20,$_SESSION['spider_site'],$No);
        if( $sth->execute($values) )
        {
            Log::set_log();
            Util::js('update_list("'.$No.'");');
            Util::alert( $this->lang->common->save_completed );
        }
        else
        {
            Util::alert($this->lang->common->error_update);
        }
    }
}