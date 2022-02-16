<?php
namespace mvc\repository;
use mvc\models\News;

class NewsRepository
{
    private $model;
    public function __construct(){
        $this->model = new News();
    }

    public function store($data, &$id){
        return $this->model->insert($data, $id);
    }

    public function getRandomNews(){
        return $this->model->fetchAll(['id', 'title'],'', ['', 'RAND()'], [0, 7]);
    }

    public function getHotNews(){
        return $this->model->fetchAll(['id', 'title', 'image', 'numofreads'],'', ['numofreads', 'DESC'], [0, 7]);
    }

    public function getNewsByID($id){
        return $this->model->getById($id);
    }

    public function getNewsByTitle($title){
        return $this->model->fetchAll('id', ['title' , '=', $title], [], [0, 1]);
    }

    public function update($id, $data){
        return $this->model->update($id, $data);

    }

    public function numberOfNews(){
        return $this->model->fetchAll(['COUNT(id) as total'], '', '', [0, 1]);
    }

    public function getPage($offset, $limit){
        return $this->model->fetchAll(['*'], '', ['id', 'DESC'], [$offset, $limit]);
    }

    public function getNewsByCateID($id){
        return $this->model->fetchAll(['*'], ['category_id', '=', $id], ['id', 'DESC'], []);
    }

    public function getRandomNewsByCateID($id){
        return $this->model->fetchAll(['id', 'title'], ['category_id', '=', $id], ['', 'RAND()'], [0, 7]);
    }

    public function getHotNewsByCateID($id){
        return $this->model->fetchAll(['id', 'title', 'image', 'numofreads'],['category_id', '=', $id], ['numofreads', 'DESC'], [0, 7]);
    }

    public function search($keyword){
        return $this->model->fetchAll(['id', 'title', 'image', 'keyword'], ['keyword', 'like', '%'.$keyword.'%'], ['id', 'DESC'], [0, 5]);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}