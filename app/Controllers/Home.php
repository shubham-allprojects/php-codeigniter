<?php
namespace App\Controllers;

class Home extends BaseController
{
    protected $db;
    public function __construct() {
        $this->db  = db_connect('network');
    }
    public function index()
    {
        //echo APPPATH; exit; 
        $result = $this->db->query("SELECT * FROM sessions")->getResultArray();
        print_r($result); exit;
        return view('welcome_message');
    }
}
