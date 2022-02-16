<?php
namespace mvc\repository;
use mvc\models\Category;

class CategoryRepository
{
    private $model;
    public function __construct(){
        $this->model = new Category();
    }

    public function getNewsByCateID(){

    }

    public function store($data, &$id){
        return $this->model->insert($data, $id);
    }

    public function getCategoryByID($id){
        return $this->model->getById($id);
    }

    public function getAll(){
        return $this->model->fetchAll('*','', ['id', 'desc'], '');
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

}