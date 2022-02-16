<?php
namespace mvc\models;
use mvc\core\DB;

abstract class BaseModel
{

    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = new DB();
        if(empty($this->table)){
            $this->setTableName();
        }
        $this->db->table = $this->table;
    }
    public function setTableName()
    {
        $class = get_class($this);
        $class = preg_replace('/([A-Z])/', ' $1',$class);
        $table = strtolower(substr($class, strrpos($class, '\\') + 1));
        $table = str_replace(' ','-',trim($table)).'s';
        $this->table = $table;
    }

    public function fetchAll($select = ['*'], $where = [], $order = ['id', 'desc'], $limit = [0, 12])
    {
        $result = $this->db->fetchAll($select, $where, $order, $limit);
        return $result;
    }

    public function insert($data = [], &$newId)
    {
        $result = $this->db->insert($data, $newId);
        return $result;
    }

    public function update($id, $data = [])
    {
        $result = $this->db->update($id, $data);
        return $result;
    }

    public function delete($id)
    {
        $result = $this->db->delete($id);
        return $result;
    }

    public function getById($id)
    {
        $result = $this->db->getById($id);
        return $result;
    }
}