<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/HistoryController.php';

class Routing {

    public static $routes = [
        "login" => [
            "controller" => "SecurityController",
            "action" => "login"
        ],
         "register" => [
            "controller" => "SecurityController",
            "action" => "register"
         ],
         "dashboard" => [
            "controller" => "DashboardController",
            "action" => "index"
         ],
            "save-entry" => [
                "controller" => "DashboardController",
                "action" => "saveEntry"
        ],
        "history" => [
            "controller" => "HistoryController",
            "action" => "index"
            ],
            "delete-entry" => ["controller" => "HistoryController", "action" => "deleteEntry"]

    ];

    public static function run(string $path) {
        switch($path) {
            case 'dashboard':
            case 'login':
            case 'register':
            case 'save-entry':
            case 'history':
            case 'delete-entry':
                $controller = Routing::$routes[$path]["controller"];
                $action = Routing::$routes[$path]["action"];

                $controllerObj = new $controller;
                $result= $controllerObj->$action();
                if ($result !== null) {
    echo $result;
}
                break; 
            default:
                include 'public/views/404.html';
                break;
        }
    }
}
