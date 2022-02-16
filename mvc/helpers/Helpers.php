<?php


namespace mvc\helpers;

class Helpers
{
    public static function save($image, $path = '', $name = '')
    {
        $data = preg_replace('/(data:image\/)|(base64,)/', '', $image);
        list($extension, $base64) = explode(';', $data);
        $decode = base64_decode($base64);
        $filename = empty($name) ? time().rand().'.'.$extension : $name.'.'.$extension;
        $pathNew = 'mvc/views/public/uploads/'.$path.'/';
//        if(!file_exists($path)) {
//            mkdir($pathNew, 0777, true);
//        }
        file_put_contents($pathNew.$filename, $decode);
        return $filename;
    }


}