<?php

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{
    protected $db;
    public function __construct($dbName){
        switch ($dbName) {
            case 'network':
                $this->db  = db_connect('network');
                break;
            case 'spiderlog':
                $this->db  = db_connect('spiderlog');
                break;
            case 'api':
                $this->db  = db_connect('api');
                break;
            default:
                $this->db  = db_connect('default');
                break;
        }
    }
    public function selectSingleRow($tbl, $fields, $condArr){
        $query = $this->db->table($tbl)->select($fields)
                            ->where($condArr)->get();
        $result = $query->getRowArray();
        return $result;
    }
    public function selectAllRows($tbl, $fields, $condArr){
        $query = $this->db->table($tbl)->select($fields)
                            ->where($condArr)->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function selectRowsCount($tbl, $condArr){
        $rowCount = $this->db->table($tbl)
                            ->where($condArr)->countAllResults();
        
        return $rowCount;
    }
    public function updateRow($tbl, $condArr, $data){
        $result = $this->db->table($tbl)
                            ->where($condArr)->update($data);
        
        return $result;
    }
    public function insertRow($tbl, $data){
        $insertId = $this->db->table($tbl)
                            ->insert($data);
        
        return $insertId;
    }
    public function deleteRow($tbl, $condArr){
        $res = $this->db->table($tbl)
                            ->where($condArr)
                            ->delete();
        
        return $res;
    }
    public function rawQuery($qry){
        $qryBuilder = $this->db->query($qry);
        return $qryBuilder;
    }
}
