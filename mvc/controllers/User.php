<?php
namespace mvc\controllers;
use mvc\middlewares\Middleware;
use mvc\repository\UserRepository;
use mvc\repository\SessionRepository;
use mvc\core\Response;
use PDOException;

class User
{
    private $userRepository;
    private $sessionRepository;
    private $middleware;
    private $response;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
        $this->middleware = new Middleware();
        $this->response = new Response();
    }

    public function get($id){
        $this->userRepository->getUserByID($id);
    }

    public function login(){

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

        if (!isset($jsonData->username) || !isset($jsonData->password)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->username) ? $this->response->addMessage("chua nhap Username") : false);
            (!isset($jsonData->password) ? $this->response->addMessage("chua nhap Password") : false);
            return $this->response->send();
        }

        if (strlen($jsonData->username) < 1 || strlen($jsonData->username) > 20 || strlen($jsonData->password) < 8 || strlen($jsonData->password) > 16) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Do dai username, password chua dung");
            return $this->response->send();
        }

        try {
            $username = $jsonData->username;
            $password = $jsonData->password;
            $userData = $this->userRepository->login($username);
            if (!$userData){
                $this->response->setHttpStatusCode(404);
                $this->response->setSuccess(false);
                $this->response->addMessage("Username, password chua dung");
                return $this->response->send();
            }
            $re_password = $userData[0]['password'];
            if (!password_verify($password, $re_password)) {
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(401);
                $this->response->addMessage("Username, password khong dung");
                return  $this->response->send();
            }

            $accesstoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24).time()));
            $refreshtoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24).time()));
            $access_token_expiry_seconds = 1200;
            $refresh_token_expiry_seconds = 1209600;

            $date = date('Y-m-d H:i:s');
            $access_token_expiry = date('Y-m-d H:i:s',strtotime($date) + $access_token_expiry_seconds);
            $refresh_token_expiry = date('Y-m-d H:i:s',strtotime($date) + $refresh_token_expiry_seconds);
            $data = [
                'user_id' => $userData[0]['id'],
                'accesstoken' => $accesstoken,
                'accesstokenexpiry' => $access_token_expiry,
                'refreshtoken' => $refreshtoken,
                'refreshtokenexpiry' => $refresh_token_expiry

            ];
            $session = $this->sessionRepository->store($data,$sessionsid);
            if (!$session){
                $this->response->setHttpStatusCode(400);
                $this->response->setSuccess(false);
                $this->response->addMessage("Create session error");
                return $this->response->send();
            }

            $returnData = [];
            $returnData['session_id'] = $sessionsid;
            $returnData['user_id'] = $userData[0]['id'];
            $returnData['user_name'] = $userData[0]["name"];
            $returnData['access_token'] = $accesstoken;
            $returnData['access_token_expiry_seconds'] = $access_token_expiry_seconds;
            $returnData['refresh_token'] = $refreshtoken;
            $returnData['refresh_token_expiry_seconds'] = $refresh_token_expiry_seconds;

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->setData($returnData);
            return $this->response->send();

        } catch (PDOException $ex) {
            $this->response->setHttpStatusCode(500);
            $this->response->setSuccess(false);
            $this->response->addMessage("Login error");
            return $this->response->send();
        }
    }

    public function logout($id){
        if ($this->middleware->checkToken() == false){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Vui long dang nhap");
            return $this->response->send();
        }
        $header = getallheaders();
        try {
            $accesstoken = $header['Authorization'];
            $sessionData = $this->sessionRepository->getSessionById($id);

            if (!$sessionData){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("Vui lòng đăng nhập");
                return $this->response->send();
            }

            if ($sessionData['accesstoken'] !== $accesstoken){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("Vui lòng đăng nhập");
                return $this->response->send();
            }

            $this->sessionRepository->delete($id);

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage("Logout thanh cong");
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(500);
            $this->response->addMessage("Error");
            return $this->response->send();
        }
    }
    public function register(){
        return $this-> userRepository->register();
    }
}