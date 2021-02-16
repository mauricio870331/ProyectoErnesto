<?php
$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$name_project = explode("/", $_SERVER["REQUEST_URI"])[1];
$config['base_url'] .= "://" . $_SERVER['HTTP_HOST'] . "/" . $name_project;
?>