<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 11:53
 */

class Route
{
    static function start()
    {
        $controllerName = 'Site'; //по умолчанию сюда ведет
        $actionName = 'index';
        $params = '';
        $routes = explode('/', $_SERVER['REQUEST_URI'], 4); //Разбивает строку с помощью разделителя. REQUEST_URI - URI, который был предоставлен для доступа к этой странице. Например, '/index.html'.
        $routeSolver = 0;
        if(empty($routes[1])) $routeSolver = 1;
        if(empty($routes[2])) $routeSolver += 2;
        switch ($routeSolver)
        {
            case 0:
                // Right way route
                $controllerName = $routes[1];
                $actionName = $routes[2];
                if(!empty($routes[3])) $params = $routes[3];
                break;
            case 2:
                // Controller is defined, action is missed
//                $controllerName = $routes[1];
                // controller set to default, action updated
                $actionName = $routes[1];
                break;
        }
        $modelName = ucfirst($controllerName); //Преобразует первый символ строки в верхний регистр
        $controllerName = ucfirst($controllerName).'Controller';
        $actionName = ucfirst($actionName);
        // Load model's file
        $modelFile = $modelName.'.php';
        $modelPath = 'app/models/'.$modelFile;
        if(file_exists($modelPath)) include $modelPath;
        //Load controller's file
        $controllerFile = $controllerName.'.php';
        $controllerPath = 'app/controllers/'.$controllerFile;
        if(file_exists($controllerPath))
            include $controllerPath;
        else
        {
            //Route::ErrorPage404();
        }
        $controller = new $controllerName;
        $action = $actionName;
        if(method_exists($controller, $action)) //Проверяет, существует ли метод в данном классе
            $controller->$action($params);
        else {}
            //Route::ErrorPage404();
    }
    static function redirect($page, $timeout = 0) //редирект
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Refresh: '.$timeout.'; url='.$host.$page);
    }
    static function ErrorPage404() //обязательно ли статический?
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        header('Location: '.$host.'error404');
    }
}