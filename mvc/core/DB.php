<?php
namespace mvc\core;

use PDO;
use mvc\config\DBConfig;
use PDOException;

class DB
{
    private $db;
    const SLOW_TIME = 50;
    public $table;

    // Connect DB

    public function __construct($type = 'read')
    {
        $configDB = new DBConfig;
        if($this->db === null){
            if($type == 'read'){
                $config = $configDB->read();
            }else{
                $config = $configDB->write();
            }
            $host = $config['host'];
            $dbname = $config['dbname'];
            $username = $config['username'];
            $password = $config['password'];
            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
                $this->db = new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    /**
     * Get all records in table
     * @param array $where 
     * @param int $offset
     * @param int $limit
     */

    public function table($table){
        $this->table = $table;
        return $this;
    }
    
    public function fetchAll($select = ['*'],$where = [], $order = ['id', 'desc'], $limit = [0,10])
    {
        return $this->select($select)->where($where, $order, $limit)->get();
    }

    /**
     * Get record by id
     * @param int $id
     */

    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = $id";
        $statements = $this->executeQuery($sql);
        if($statements){
            $result = $statements->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    /**
     * Get record by id
     * @param array $data['field]
     */

    public function insert($data = [], &$newId)
    {
        if($data == null){
            return false;
        }
        $into = "INSERT INTO $this->table(";
        $values = "VALUES (";
        foreach($data as $field => $value){
            $into .= "`$field`, ";
            $values .= "'$value', ";
        }
        $into = trim($into, ', ').")";
        $values = trim($values, ', ').")";
        $sql = "$into $values";
        $statements = $this->executeQuery($sql);
        $newId = $this->db->lastInsertId();
        return $statements;
    }

    /**
     * Get record by id
     * @param int $id
     * @param array $data['field]
     */

    public function update($id, $data = [])
    {
        if(!$id){
            return false;
        }
        $sql = "UPDATE $this->table SET ";
        foreach($data as $field => $value){
            $sql .= "`$field` = '$value', ";
        }
        $sql = trim($sql, ', ');
        $sql .= " WHERE id = $id";
        return $this->executeQuery($sql);

    }

    /**
     * Get record by id
     * @param int $id
     */
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = $id";
        return $this->executeQuery($sql);
    }

    public function select($select = ['*'])
    {
        if(is_array($select)){
            $sql = "SELECT ";
            foreach($select as $field){
                $sql .= "$field, ";
            }
            $sql = trim($sql, ', ')." FROM $this->table ";
            $this->sql = $sql;
        }
        return $this;
    }

    public function where($where = [], $order = ['id','desc'], $limit = [0,10])
    {
        if(!isset($this->sql)){
            $this->sql = "SELECT * FROM $this->table ";
        }
        if(is_array($where) && !empty($where)){
            $result = 'WHERE ';
            if(is_string($where[0])){
                $result .= $this->parseWhere($where);
            }else{
                foreach($where as $value){
                    if(is_array($value)){
                        $result .= $this->parseWhere($value).' AND ';;
                    }
                }
            }
            $where = rtrim($result, ' AND ');
            $this->sql .= $where;
        }
        if($order){
           $this->sql .= " ORDER BY ". implode(' ', $order);
        }
        if($limit){
            $limit = implode(', ', $limit);
            $this->sql .= " LIMIT $limit";
        }
        return $this;
    }

    public function parseWhere($where)
    {
        if(count($where) == 3){
            $operator = $where[1];
        }else if(count($where) == 2){
            $operator = ' = ';
        }
        $first = array_shift($where);
        $last = array_pop($where);
        if(is_int($last)){
            return "$first $operator $last";
        }
        return "$first $operator '$last'";
    }

    public function query($sql = null)
    {
        if($sql != null){
            $statements = $this->executeQuery($sql);
            return $statements->FetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function get()
    { 
        if($this->sql){
            $statements = $this->executeQuery($this->sql);
            return $statements->FetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function executeQuery($sql)
    {
        $statements = $this->db->prepare($sql);
        $start = microtime(true) * 1000;
        $check = $statements->execute();
        $end = microtime(true) * 1000;
        $executeTime = number_format($end - $start,2);
//        $this->writeLog($executeTime, $sql);
        if($check){
            return $statements;
        }else{
            return false;
        }

    }

//    public function writeLog($executeTime, $sql){
//        $logs = [
//            'logMessage' => 'SLOW SQL',
//            'logDateTime' => date('Y-m-d H:i:s'),
//            'data' => ['sql' => $sql, 'time' => "$executeTime ms"]
//        ];
//        $logs = json_encode($logs);
//        $query = 'query_';
//        if($executeTime > 50){
//            $query = 'slow_';
//        }
//        if(!file_exists(dirname(__DIR__).'/logs/mysql')){
//            mkdir(dirname(__DIR__).'/logs/mysql', 0777);
//        }
//        $path = dirname(__DIR__).'/logs/mysql/'.$query.date('Ymd_His').'.log';
//        $path = str_replace('/', '\\', $path);
//        file_put_contents($path, $logs);
//    }
}