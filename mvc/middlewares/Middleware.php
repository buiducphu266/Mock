<?php
namespace mvc\middlewares;

;
use mvc\repository\SessionRepository;
use PDOException;


class Middleware
{
    private $sessionRepository;

    public function __construct()
    {

        $this->sessionRepository = new SessionRepository();
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