<?
class MySessionHandler implements SessionHandlerInterface
{
	private $session_db;
    public function __construct() {}

	public function close(): bool {
		// Nothing needs to be done in this function
		// since we used persistent connection.
		return true;
	}

public function open($save_path ,$session_name):bool{
	//error_log($session_name . " ". session_id());
	$session_db = new PDO('sqlite:'.DATABASE_DIR.'/Network.db');
	if($session_db){
		$this->session_db = $session_db;
		//error_log($this->session_db . " ". session_id());
		//print_r($this->session_db);
		return true;
	}else{
		return false;
	}
	
}



public function read($key):bool {
	//global $session_db;
	
	$list  = $this->session_db->prepare("SELECT session_data FROM sessions WHERE session_id = ? AND session_expiration > ?");
	$list->execute(array($key, time()));
	if( $row = $list->fetch(PDO::FETCH_ASSOC) )
	{
		error_log("READ Session IF:: ". json_encode($row['session_data']));
		return $row['session_data'];
	}
	else
	{
		error_log("READ Session ELSE:: ");	
		return false;
	}
}
public function write($key, $val):bool {
	
	$expire = time() + (60 * 60 * 24);
	$list  = $this->session_db->prepare("SELECT * FROM sessions WHERE session_id = ?");
	$list->execute(array($key));

	if( $row = $list->fetch(PDO::FETCH_ASSOC) )
	{
		error_log("WRITE Session IF");	
		return $this->session_db->exec("UPDATE sessions SET session_data='{$val}', session_expiration='{$expire}', user_id='{$_SESSION['spider_id']}', user_ip='{$_SERVER['REMOTE_ADDR']}', status=1 WHERE session_id='{$key}'");
	}
	else
	{
		error_log("WRITE Session ELSE");
		return $this->session_db->exec("INSERT INTO sessions VALUES('{$key}', '{$val}', '{$expire}', '{$_SESSION['spider_id']}', '{$_SERVER['REMOTE_ADDR']}', 1)");
	}

	if( $_SESSION['spider_id'] != '' ){
		error_log("WRITE Session last IF");
		return $this->session_db->exec("UPDATE sessions SET status=-1, session_data=session_data||'spider_status|s:2:\"-1\";' WHERE session_id != '{$key}' AND user_id='{$_SESSION['spider_id']}'");
	}
}

public function destroy($key):bool {
	$result = $this->session_db->exec("DELETE FROM sessions WHERE session_id='{$key}'");
	if($result){
		return true;
	}else{
		return false;
	}
}

public function gc($max_lifetime):bool 
{
	$expire = time();
	$result =  $this->session_db->exec("DELETE FROM sessions WHERE session_expiration < '{$expire}'");
	if($result){
		return true;
	}else{
		return false;
	}
}

}

$handler = new MySessionHandler();
session_set_save_handler($handler, true);
// Set the save handlers
// session_set_save_handler("on_session_start",   "on_session_end",
// 		"on_session_read",    "on_session_write",
// 		"on_session_destroy", "on_session_gc");

// the following prevents unexpected effects when using objects as save handlers
//register_shutdown_function('session_write_close');

session_start();
