<?php


namespace mvc\repository;
use mvc\models\Session;

class SessionRepository
{
    private $model;
    public function __construct(){
        $this->model = new Session();
    }

    public function store($data, &$id){
        return $this->model->insert($data, $id);
    }

    public function getSessionById($id)
    {
        return $this->model->getById($id);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function getSessionByToken($token){
        return $this->model->fetchAll('id', ['accesstoken' , '=', $token], [], [0, 1]);
    }


}