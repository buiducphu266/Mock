<?php


class Repository
{
    public function model($model){
        require_once "./mvc/models/".$model.".php";
    }

    public function _response(){
        require_once "./mvc/core/Response.php";
        return new Response();
    }
}