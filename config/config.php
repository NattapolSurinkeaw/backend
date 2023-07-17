<?php
    define("DB_HOST", "localhost"); 
    define("DB_DATABASE", "sql_berforyou_co"); 
	// define("DB_USER", "sql_berforyou_co"); 
	define("DB_USER", "root"); 
	// define("DB_PASSWORD", "y2pDJatcdnFWmeZ5"); 
	define("DB_PASSWORD", ""); 
	define("DB_CHARSET", "SET NAMES UTF8");   
	define("ROOTPATH", "backend/");
	define("SITE_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/backend/");
	define("BASE_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/backend/");
	define("ROOT_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/");
	define("AJAX_REQUEST_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/backend/");
	
	define('PATH_UPLOAD', $_SERVER['DOCUMENT_ROOT'] . '/upload/');
 
