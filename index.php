<?php
session_start();
require_once "vendor/autoload.php";
//use Core\App;
use NoahBuscher\Macaw\Macaw;
//$myApp = new App();

Macaw::get('PHP-MVC-API/news/numberofpage', 'mvc\controllers\News@numberOfPage');
Macaw::post('PHP-MVC-API/news/create', 'mvc\controllers\News@create');
Macaw::post('PHP-MVC-API/news/update/(:num)', 'mvc\controllers\News@update');
Macaw::get('PHP-MVC-API/news/page/(:num)', 'mvc\controllers\News@page');
Macaw::get('PHP-MVC-API/news/get/(:num)', 'mvc\controllers\News@get');
Macaw::get('PHP-MVC-API/news/detail/(:num)', 'mvc\controllers\News@detail');
Macaw::get('PHP-MVC-API/news/random', 'mvc\controllers\News@random');
Macaw::get('PHP-MVC-API/news/search/(:any)', 'mvc\controllers\News@search');
Macaw::get('PHP-MVC-API/news/hot', 'mvc\controllers\News@hot');
Macaw::delete('PHP-MVC-API/news/delete/(:num)', 'mvc\controllers\News@delete');

Macaw::get('PHP-MVC-API/category/show', 'mvc\controllers\Category@show');
Macaw::get('PHP-MVC-API/category/get/(:num)', 'mvc\controllers\Category@get');
Macaw::get('PHP-MVC-API/category/detail/(:num)', 'mvc\controllers\Category@detail');
Macaw::get('PHP-MVC-API/category/random/(:num)', 'mvc\controllers\Category@random');
Macaw::get('PHP-MVC-API/category/hot/(:num)', 'mvc\controllers\Category@hot');
Macaw::post('PHP-MVC-API/category/create', 'mvc\controllers\Category@create');
Macaw::post('PHP-MVC-API/category/update/(:num)', 'mvc\controllers\Category@update');
Macaw::delete('PHP-MVC-API/category/delete/(:num)', 'mvc\controllers\Category@delete');

Macaw::post('PHP-MVC-API/user/login', 'mvc\controllers\User@login');
Macaw::post('PHP-MVC-API/user/register', 'mvc\controllers\User@register');
Macaw::delete('PHP-MVC-API/user/logout/(:num)', 'mvc\controllers\User@logout');



Macaw::dispatch();