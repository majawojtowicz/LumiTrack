<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/HistoryController.php';
require_once 'src/controllers/ProfileController.php';
require_once 'src/controllers/AdminController.php';
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
        "delete-entry" => [
            "controller" => "HistoryController",
            "action" => "deleteEntry"
        ],
        "profile" => [
            "controller" => "ProfileController",
            "action" => "index"
        ],
        "update-profile" => [
        "controller" => "ProfileController",
        "action" => "updateProfile"
        ],
        "logout" => [
            "controller" => "ProfileController",
            "action" => "logout"
        ],
        "admin" => [
            "controller" => "AdminController",
            "action" => "index"
        ],
        "admin-toggle-block" => [
            "controller" => "AdminController",
            "action" => "toggleBlock"
        ]
    ];

    public static function run(string $path) {
        $urlParts = explode("?", $path);
        $actionName = $urlParts[0];

        if (!array_key_exists($actionName, Routing::$routes)) {
            include 'public/views/404.html';
            return;
        }

        $controller = Routing::$routes[$actionName]["controller"];
        $action = Routing::$routes[$actionName]["action"];

        $controllerObj = new $controller;
        $result = $controllerObj->$action();

        if ($result !== null) {
            echo $result;
        }
    }
}