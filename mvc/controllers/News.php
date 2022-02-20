<?php
namespace mvc\controllers;
use mvc\helpers\Helpers;
use mvc\middlewares\Middleware;
use mvc\repository\NewsRepository;
use mvc\repository\UserRepository;
use mvc\repository\CategoryRepository;
use mvc\repository\SessionRepository;
use mvc\core\Response;
use PDOException;

class News
{
    private $newsRepository;
    private $categoryRepository;
    private $userRepository;
    private $sessionRepository;
    private $middleware;
    private $response;

    public function __construct()
    {
        $this->newsRepository = new NewsRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
        $this->middleware = new Middleware();
        $this->response = new Response();
    }

    public function create(){
        if ($this->middleware->checkToken() == false){
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

        if (!isset($jsonData->category_id) || !isset($jsonData->user_id) || !isset($jsonData->title) || !isset($jsonData->description) || !isset($jsonData->content) || !isset($jsonData->keyword) || !isset($jsonData->image) || !isset($jsonData->public_at)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->category_id) ? $this->response->addMessage("chua co category_id") : false);
            (!isset($jsonData->user_id) ? $this->response->addMessage("chua co user_id") : false);
            (!isset($jsonData->title) ? $this->response->addMessage("chua co title") : false);
            (!isset($jsonData->description) ? $this->response->addMessage("chua co description") : false);
            (!isset($jsonData->content) ? $this->response->addMessage("chua co content") : false);
            (!isset($jsonData->keyword) ? $this->response->addMessage("chua co keyword") : false);
            (!isset($jsonData->image) ? $this->response->addMessage("chua co image") : false);
            (!isset($jsonData->public_at) ? $this->response->addMessage("chua co public_at") : false);
            return $this->response->send();
        }

        if (strlen($jsonData->category_id) < 1 || strlen($jsonData->user_id) < 1 || strlen($jsonData->title) < 1 || strlen($jsonData->description) < 1 || strlen($jsonData->content) < 1 || strlen($jsonData->keyword) < 1 || strlen($jsonData->image) < 1 || strlen($jsonData->public_at) < 1) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Chua nhap du thong tin");
            return $this->response->send();
        }

        if (!is_numeric($jsonData->category_id) || !is_numeric($jsonData->user_id)){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!is_numeric($jsonData->category_id) ? $this->response->addMessage("category_id phai la so") : false);
            (!is_numeric($jsonData->user_id) ? $this->response->addMessage("category_id phai la so") : false);
            $this->response->send();
            exit();
        }

        if (date_format(date_create_from_format('Y-m-d H:i:s',$jsonData->public_at),'Y-m-d H:i:s') != $jsonData->public_at){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Public_at la kieu datetime");
            $this->response->send();
            exit();
        }
        try {
            $id = '';
            if ($this->newsRepository->getNewsByTitle($jsonData->title)){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(409);
                $this->response->addMessage("Title da ton tai");
                return $this->response->send();
            }
            $image = Helpers::save($jsonData->image);
            $data = [
                'category_id' => $jsonData->category_id,
                'user_id' => $jsonData->user_id,
                'title' => $jsonData->title,
                'description' => $jsonData->description,
                'content' => $jsonData->content,
                'keyword' => $jsonData->keyword,
                'image' => $image,
                'public_at' => date_format(date_create_from_format('Y-m-d H:i:s',$jsonData->public_at),'Y-m-d H:i:s')
            ];
            if (!$this->newsRepository->store($data, $id)){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(500);
                $this->response->addMessage("Them category loi");
                return $this->response->send();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage("Them news thanh cong");
            $this->response->toCache(true);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setHttpStatusCode(500);
            $this->response->setSuccess(false);
            $this->response->addMessage("Error");
            return $this->response->send();
        }

    }

    public function update($id){
        if ($this->middleware->checkToken() == false){
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

        if (!isset($jsonData->category_id) || !isset($jsonData->user_id) || !isset($jsonData->title) || !isset($jsonData->description) || !isset($jsonData->content) || !isset($jsonData->keyword) || !isset($jsonData->image) || !isset($jsonData->public_at)) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!isset($jsonData->category_id) ? $this->response->addMessage("chua co category_id") : false);
            (!isset($jsonData->user_id) ? $this->response->addMessage("chua co user_id") : false);
            (!isset($jsonData->title) ? $this->response->addMessage("chua co title") : false);
            (!isset($jsonData->description) ? $this->response->addMessage("chua co description") : false);
            (!isset($jsonData->content) ? $this->response->addMessage("chua co content") : false);
            (!isset($jsonData->keyword) ? $this->response->addMessage("chua co keyword") : false);
            (!isset($jsonData->image) ? $this->response->addMessage("chua co image") : false);
            (!isset($jsonData->public_at) ? $this->response->addMessage("chua co public_at") : false);
            return $this->response->send();
        }

        if (strlen($jsonData->category_id) < 1 || strlen($jsonData->user_id) < 1 || strlen($jsonData->title) < 1 || strlen($jsonData->description) < 1 || strlen($jsonData->content) < 1 || strlen($jsonData->keyword) < 1 || strlen($jsonData->image) < 1 || strlen($jsonData->public_at) < 1) {
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Chua nhap du thong tin");
            return $this->response->send();
        }

        if (!is_numeric($jsonData->category_id) || !is_numeric($jsonData->user_id)){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            (!is_numeric($jsonData->category_id) ? $this->response->addMessage("category_id phai la so") : false);
            (!is_numeric($jsonData->user_id) ? $this->response->addMessage("category_id phai la so") : false);
            $this->response->send();
            exit();
        }

        if (date_format(date_create_from_format('Y-m-d H:i:s',$jsonData->public_at),'Y-m-d H:i:s') != $jsonData->public_at){
            $this->response->setHttpStatusCode(400);
            $this->response->setSuccess(false);
            $this->response->addMessage("Public_at la kieu datetime");
            $this->response->send();
            exit();
        }
        try {
            if (!$this->newsRepository->getNewsByID($id)){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("News not found");
                return $this->response->send();
            }
            $image = Helpers::save($jsonData->image);
            $data = [
                'category_id' => $jsonData->category_id,
                'user_id' => $jsonData->user_id,
                'title' => $jsonData->title,
                'description' => $jsonData->description,
                'content' => $jsonData->content,
                'keyword' => $jsonData->keyword,
                'image' => $image,
                'public_at' => date_format(date_create_from_format('Y-m-d H:i:s',$jsonData->public_at),'Y-m-d H:i:s')
            ];
            if (!$this->newsRepository->update($id, $data)){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(500);
                $this->response->addMessage("Them category loi");
                return $this->response->send();
            }

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage("Cap nhật news thanh cong");
            $this->response->toCache(true);
            return $this->response->send();
        }
        catch (PDOException $exception){
            $this->response->setHttpStatusCode(500);
            $this->response->setSuccess(false);
            $this->response->addMessage("Error");
            return $this->response->send();
        }

    }

    public function page($id){
        try {
            $limitPage = 10;
            $rowCount = $this->newsRepository->numberOfNews();
            $totalTask = ($rowCount[0]['total']);

            $numOfPage = ceil($totalTask/$limitPage);
            if ($numOfPage == 0){
                $numOfPage = 1;
            }

            $offset = $id == 1 ? 0 : $limitPage*($id-1);
            if($id > $numOfPage || $id == 0){
                $this->response->setHttpStatusCode(404);
                $this->response->setSuccess(false);
                $this->response->addMessage("Page not found");
                return $this->response->send();
            }
            $data = [];
            $data['number_of_page'] = $id;
            $data['total_page'] = $numOfPage;
            $cate_title = '';
            $newsDatas = $this->newsRepository->getPage($offset, $limitPage);
            foreach ($newsDatas as $newsData){
                if ($newsData['category_id'] == 0){
                    $cate_title = 'Danh mục cha';
                }
                else{
                    $cateData = $this->categoryRepository->getCategoryByID($newsData['category_id']);
                    $cate_title = $cateData['title'];
                }
                $userData = $this->userRepository->getUserByID($newsData['user_id']);
                if (!$newsData || !$userData){
                    $this->response->setSuccess(false);
                    $this->response->setHttpStatusCode(404);
                    $this->response->addMessage("Khong tim thay News");
                    return $this->response->send();
                }
                $data['newss'][] = [
                    'id' => $newsData['id'],
                    'user_name' => $userData['name'],
                    'category_title' => $cate_title,
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

    public function random(){

        try {
            $data = $this->newsRepository->getRandomNews();
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

    public function get($id){
        try {
            $newsData = $this->newsRepository->getNewsByID($id);

            if (!$newsData){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("Khong tim thay News");
                return $this->response->send();
            }
            $userData = $this->userRepository->getUserByID($newsData['user_id']);
            $cateData = $this->categoryRepository->getCategoryByID($newsData['category_id']);

            $data = [
                'id' => $newsData['id'],
                'user_name' => $userData['name'],
                'category_title' => $cateData['title'],
                'news_title' => $newsData['title'],
                'content' => $newsData['content'],
                'image' => $newsData['image'],
                'public_at' => $newsData['public_at'],
            ];
            $num = $newsData['numofreads'] + 1;
            $dataup = [
                'numofreads' => $num
            ];
            $this->newsRepository->update($id, $dataup);

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

    public function hot(){
        try {
            $data = $this->newsRepository->getHotNews();
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

    public function numberOfPage(){

    try {
        $limitPage = 10;
        $rowCount = $this->newsRepository->numberOfNews();
        $totalTask = ($rowCount[0]['total']);

        $numOfPage = ceil($totalTask/$limitPage);
        if ($numOfPage == 0){
            $numOfPage = 1;
        }

        $data = [
            'number_page' => $numOfPage
        ];
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

    public function search($keyword){
        try {
            $newsData = $this->newsRepository->search($keyword);

            $this->response->setSuccess(true);
            $this->response->setHttpStatusCode(200);
            $this->response->toCache(true);
            $this->response->setData($newsData);
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
            $data = $this->newsRepository->getNewsByID($id);
            if (!$data){
                $this->response->setSuccess(false);
                $this->response->setHttpStatusCode(404);
                $this->response->addMessage("News not found");
                return $this->response->send();
            }
            $this->newsRepository->delete($id);
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
            $data = $this->newsRepository->getNewsByID($id);
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

}