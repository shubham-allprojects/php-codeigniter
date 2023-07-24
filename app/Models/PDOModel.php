<?php

namespace App\Models;

use CodeIgniter\Model;

class PDOModel extends \PDOStatement
{
    protected $db;
    public function __construct($dbName){
        switch ($dbName) {
            case 'network':
                $this->db  = new \PDO('sqlite:'.WRITEPATH.'database/Network.db');
                break;
            case 'spiderlog':
                $this->db  = new \PDO('sqlite:'.WRITEPATH.'database/SpiderLog.db');
                break;
            case 'api':
                $this->db  = new \PDO('sqlite:'.WRITEPATH.'database/api.db');
                break;
            default:
                $this->db  = new \PDO('sqlite:'.WRITEPATH.'database/Spider.db');
                break;
        }
    }
}
