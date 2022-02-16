<?php
$page = $_GET['page'] ?? 'login';
echo file_get_contents($page . '.php');
