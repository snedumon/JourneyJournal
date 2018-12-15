<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:18
 */

class UserController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('User');
        $this->setView('manage'); //доделать
    }
    public function profile($userName)
    {
        //echo("manage");
        //Метод выводит всю информацию о пользователе
    }
    public function edit($userName)
    {
        if(empty($userName))
        {
            Route::redirect('user/profile');
            return false;
        }
        if(isset($_POST['saveUser']))
        {
            $password = htmlspecialchars($_POST['password']); //Преобразует специальные символы в HTML-сущности
            $email = htmlspecialchars($_POST['email']);
            //$active = (int)$_POST['active'];
            $model = $this->model;
            if($model->findByUsername($userName))
            {
                if(!empty($password))
                    $model->setPassword($password);
                if(!empty($email))
                    $model->setEmail($email);
                //$model->setStatus($active);
                //$model->setRoles($userRoles);
                if($model->save())
                    $this->view->set('message', 'Information updated.');
                else
                    $this->view->set('errorMessage', 'Not able to update user info.');
            }
            else
            {
                Route::redirect('user/manage');
                return false;
            }
        }
    }
    public function create() //добавление пользователя админом
    {
        if(isset($_POST['saveUser']))
        {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $email = htmlspecialchars($_POST['email']);
            //$active = (int)$_POST['active'];
            //$userRoles = $_POST['roles'];
            $model = $this->model;
            if($model->findByUsername($username))
            {
                $this->view->set('errorMessage', 'User with the same name is already exist.');
            }
            else
            {
                $newUser = new User;
                $newUser->setUsername($username);
                $newUser->setPassword($password);
                $newUser->setEmail($email);
                //$newUser->setStatus($active);
                if($newUser->save())
                    $this->view->set('message', 'New user successfully added.');
                else
                    $this->view->set('errorMessage', 'Not able to save user.');
            }
        }
    }
    public function search(){
        $query = $_POST['user'];
        $usr = new User;
        $publicMas = $usr->searchUsr($query);
        //print_r($publicMas);
        include ('app/view/publics.php');
    }
    public function info($userID) {
        $usr = new User;
        $infoMas = $usr->allInfo($userID);
        foreach ($infoMas as $i) {
            $userName = $i['username'];
            $publicName = $i['name'];
            echo "Имя юзера: $userName Имя паблика: $publicName </br>";
        }
    }
}