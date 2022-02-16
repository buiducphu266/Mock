<?php
namespace mvc\repository;
use mvc\models\User;

class UserRepository
{
    private $model;
    public function __construct(){
        $this->model = new User();
    }

    public function getUserByID($id){
        return $this->model->getById($id);
    }

    public function login($username){
        return $this->model->fetchAll('*', ['username', '=', $username], [], [0,1]);
    }

    public function register()
    {
        if ($_SERVER['CONTENT_TYPE'] != 'application/json') {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Content type not json");
            $this->response->send();
            exit();
        }

        $rawPostData = file_get_contents('php://input');
        $jsonData = json_decode($rawPostData);
        if (!$jsonData) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Request body not json");
            $this->response->send();
            exit();
        }

        if (!isset($jsonData->username) || !isset($jsonData->password) || !isset($jsonData->name)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->username) ? $this->response->addMessage("chua nhap Username") : false);
            (!isset($jsonData->password) ? $this->response->addMessage("chua nhap Password") : false);
            (!isset($jsonData->name) ? $this->response->addMessage("chua nhap Name") : false);
            $this->response->send();
            exit();
        }

        if (strlen($jsonData->username) < 1 || strlen($jsonData->username) > 20 || strlen($jsonData->password) < 8 || strlen($jsonData->password) > 16 || strlen($jsonData->name) < 1 || strlen($jsonData->name) > 50) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Do dai name, username, password chua dung");
            $this->response->send();
            exit();
        }

        try {
            $name = $jsonData->name;
            $username = $jsonData->username;
            $password = $jsonData->password;
            $qr = $this->connectDB()->prepare('SELECT id FROM user where username = :username ');
            $qr->bindParam(':username', $username);
            $qr->execute();
            $rowCount = $qr->rowCount();

            if ($rowCount !== 0) {
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(409);
                $this->response->addMessage("Username da ton tai");
                $this->response->send();
                exit();
            }

            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = $this->connectDB()->prepare('INSERT INTO user (name, username, password) VALUES (:name, :username, :password)');
            $query->bindParam(':name', $name);
            $query->bindParam(':username', $username);
            $query->bindParam(':password', $password);
            $query->execute();
            $rowCountUser = $query->rowCount();

            if ($rowCountUser == 0) {
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(500);
                $this->response->addMessage("Dang ki loi");
                $this->response->send();
                exit();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage("Dang ki thanh cong");
            $this->response->toCache(true);
            $this->response->send();
        } catch (PDOException $ex) {
            $this->response->setHttpStatusCode(500);
            $this->response->setSuccess(false);
            $this->response->addMessage("Login error");
            $this->response->send();
            exit();
        }
    }
}