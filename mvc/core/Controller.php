<?php
class Controller{

    public function repository($repository){
        require_once "./mvc/repository/".$repository.".php";
        return new $repository;
    }

    public function view($view, $data=[]){
        require_once "./mvc/views/".$view.".php";
    }

}
