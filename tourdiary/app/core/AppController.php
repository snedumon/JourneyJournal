<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:02
 */

class AppController
{
    protected $model;
    protected $view;
    protected $modelBaseName;
    protected $user;
    public function __construct()
    {
        //print_r($_SESSION);
        if(isset($_SESSION['user']['username']))
        {
            $this->user = new User;
            $this->user->findByUsername($_SESSION['user']['username']);
            //echo("USER LOGINED<br>");
        }
        else
        {
            $this->user = new UserGuest;
            //Route::redirect('login');
        }
    }
    protected function setModel($model) //включение модели
    {
        $this->model = new $model;
        $this->modelBaseName = $model;
    }
    protected function setView($view) //доделать
    {
        //$this->view = new AppView(strtolower($this->modelBaseName).'/'.$view);
    }
    protected function useView($view) //доделать
    {
        //$this->view = new AppView($view);
    }
}