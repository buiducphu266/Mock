<?php
namespace mvc\controllers;
use mvc\repository\CategoryRepository;
use mvc\repository\NewsRepository;
use mvc\repository\UserRepository;
use mvc\repository\SessionRepository;
use mvc\core\Response;
use mvc\helpers\Helpers;
use PDOException;

class Category
{
    private $categoryRepository;
    private $userRepository;
    private $newsRepository;
    private $sessionRepository;
    private $response;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
        $this->newsRepository = new NewsRepository();
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
        $this->response = new Response();
    }

    public function show(){

        $data = $this->categoryRepository->getAll();
        $this->response->setSuccess(true);
        $this->response->setHttpStatusCode(200);
        $this->response->toCache(true);
        $this->response->setData($data);
        return $this->response->send();

    }

    public function get($id){
        try {
            $newsDatas = $this->newsRepository->getNewsByCateID($id);
            if (!$newsDatas){
                $this->response->setSuccess(true);
                $this->response->setHttpStatusCode(200);
                $this->response->toCache(true);
                return $this->response->send();
            }
            foreach ($newsDatas as $newsData){
                $userData = $this->userRepository->getUserByID($newsData['user_id']);
                $cateData = $this->categoryRepository->getCategoryByID($newsData['category_id']);

                $data[] = [
                    'id' => $newsData['id'],
                    'user_name' => $userData['name'],
                    'category_title' => $cateData['title'],
                    'news_title' => $newsData['title'],
                    'description' => $newsData['description'],
                    'image' => $newsData['image'],
                    'public_at' => $newsData['public_at'],
                ];
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->setData($data);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setHttpStatusCode(500);
            $this->response->setSuccess(false);
            $this->response->addMessage("Get page error");
            return $this->response->send();
        }

    }

    public function create(){
        if ($this->checkToken() == false){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Vui long dang nhap");
            return $this->response->send();
        }
        if ($_SERVER['CONTENT_TYPE'] != 'application/json') {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Content type not json");
            return $this->response->send();
        }

        $rawPostData = file_get_contents('php://input');
        $jsonData = json_decode($rawPostData);
        if (!$jsonData) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Request body not json");
            return $this->response->send();
        }

        if (!isset($jsonData->parent_id) || !isset($jsonData->title) || !isset($jsonData->description) || !isset($jsonData->image)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->parent_id) ? $this->response->addMessage("chua co parent_id") : false);
            (!isset($jsonData->title) ? $this->response->addMessage("chua co title") : false);
            (!isset($jsonData->description) ? $this->response->addMessage("chua co description") : false);
            (!isset($jsonData->image) ? $this->response->addMessage("chua co image") : false);
            $this->response->send();
            exit();
        }
        if (strlen($jsonData->parent_id) < 1 || strlen($jsonData->title) < 1 || strlen($jsonData->description) < 1 || strlen($jsonData->image) < 1) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Chua nhap du thong tin");
            $this->response->send();
            exit();
        }
        if (!is_numeric($jsonData->parent_id)){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Parent_id phai la so");
            $this->response->send();
            exit();
        }
        $id = '';
        $image = Helpers::save($jsonData->image);
        $data = [
            'parent_id' => $jsonData->parent_id,
            'title' => $jsonData->title,
            'description' => $jsonData->description,
            'image' => $image
        ];
        if (!$this->categoryRepository->store($data, $id)){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Them category loi");
            return $this->response->send();
        }

        $this->response->setSuccess(true);
        $this->response->setHttpStatusCode(200);
        $this->response->addMessage("Them category thanh cong");
        $this->response->toCache(true);
        return $this->response->send();
    }

    public function update($id){
        if ($this->checkToken() == false){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Vui long dang nhap");
            return $this->response->send();
        }
        if ($_SERVER['CONTENT_TYPE'] != 'application/json') {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Content type not json");
            return $this->response->send();
        }

        $rawPostData = file_get_contents('php://input');
        $jsonData = json_decode($rawPostData);
        if (!$jsonData) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Request body not json");
            return $this->response->send();
        }

        if (!isset($jsonData->parent_id) || !isset($jsonData->title) || !isset($jsonData->description) || !isset($jsonData->image)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->parent_id) ? $this->response->addMessage("chua co parent_id") : false);
            (!isset($jsonData->title) ? $this->response->addMessage("chua co title") : false);
            (!isset($jsonData->description) ? $this->response->addMessage("chua co description") : false);
            (!isset($jsonData->image) ? $this->response->addMessage("chua co image") : false);
            $this->response->send();
            exit();
        }

        if (strlen($jsonData->parent_id) < 1 || strlen($jsonData->title) < 1 || strlen($jsonData->description) < 1 || strlen($jsonData->image) < 1) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Chua nhap du thong tin");
            $this->response->send();
            exit();
        }
        if (!is_numeric($jsonData->parent_id)){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Parent_id phai la so");
            $this->response->send();
            exit();
        }

        if (!$this->categoryRepository->getCategoryByID($id)){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Category not found");
            $this->response->send();
            exit();
        }
        $image = Helpers::save($jsonData->image);
        $data = [
            'title' => $jsonData->title,
            'parent_id' => $jsonData->parent_id,
            'image' => $image,
            'description' => $jsonData->description

        ];
        if (!$this->categoryRepository->update($id, $data)){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Them category loi");
            return $this->response->send();
        }

        $this->response->setSuccess(true);
        $this->response->setHttpStatusCode(200);
        $this->response->addMessage("Cap nhap category thanh cong");
        $this->response->toCache(true);
        return $this->response->send();
    }

    public function random($id){
        try {
            $data = $this->newsRepository->getRandomNewsByCateID($id);
            if (!$data){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("News not found");
                return $this->response->send();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->setData($data);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Error");
            return $this->response->send();
        }


    }

    public function hot($id){
        try {
            $data = $this->newsRepository->getHotNewsByCateID($id);
            if (!$data){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("News not found");
                return $this->response->send();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->setData($data);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Error");
            return $this->response->send();
        }


    }

    public function delete($id){
        if ($this->checkToken() == false){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Vui long dang nhap");
            return $this->response->send();
        }
        try {
            $data = $this->categoryRepository->getCategoryByID($id);
            if (!$data){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("Category not found");
                return $this->response->send();
            }
            $this->categoryRepository->delete($id);
            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->addMessage("Xoá thành công");
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Error");
            return $this->response->send();
        }
    }

    public function detail($id){
        try {
            $data = $this->categoryRepository->getCategoryByID($id);
            if (!$data){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("News not found");
                return $this->response->send();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->setData($data);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Error");
            return $this->response->send();
        }
    }

    public function checkToken(){
        $header = getallheaders();
        if (empty($header['Authorization'])){
            return false;
        }

        try {
            $accesstoken = $header['Authorization'];
            $sessionData = $this->sessionRepository->getSessionByToken($accesstoken);

            if (!$sessionData){
                return false;
            }
            $date = date('Y-m-d H:i:s');

            if (strtotime($sessionData[0]['accesstokenexpiry']) < strtotime($date)){
                return false;
            }

            return true;
        }
        catch (PDOException $exception){
            return false;
        }
    }
}