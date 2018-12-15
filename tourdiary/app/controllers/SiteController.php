<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 14:01
 */
//нужно менять
class SiteController extends AppController
{
    public function index()
    {
        include ('app/view/main.php');
        if(!isset($_SESSION['user']['username']))
        {
            //echo("MainPage");
            //Route::redirect('login');

            return true;
        }
    }
    public function login() //вход в аккаунт
    {
        //include ('app/view/main.php');
        //echo("Login");
        /*echo "Заполните форму:
        <form method=\"POST\" action=\"/login\">
        Логин: <input type=\"text\" name=\"username\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Пароль: <input type=\"password\" name=\"password\" maxlength=\"15\" pattern=\"[A-Za-z-0-9]{5,15}\" title=\"Не менее 5 и не более 15 латинских символов или цифр\" required><br>
    
	    <input type=\"submit\" name=\"enter\" value=\"Вход\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";*/

        $error = false;
        if(isset($_POST['enter']))
        {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            if(empty($username) || empty($password)){
                $error = true;
            }
            else
            {
                $auth = new Auth($username, $password);
                if($auth->login())
                {
                    Route::redirect('index');
                }
                else
                    $error = true;
            }
        }
        Route::redirect('note/usrnotes');
    }
    public function logout() //выход  аккаунта
    {
        unset($_SESSION['user']);
        Route::redirect('index');
    }
    public function register() //регистрация
    {
        echo("Registration");
        echo "Заполните форму:
        <form method=\"POST\" action=\"/register\">
        Логин: <input type=\"text\" name=\"username\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Пароль: <input type=\"password\" name=\"password\" maxlength=\"15\" pattern=\"[A-Za-z-0-9]{5,15}\" title=\"Не менее 5 и не более 15 латинских символов или цифр\" required><br>
        E-mail: <input type=\"email\" name=\"email\" required><br>
    
	    <input type=\"submit\" name=\"enter\" value=\"Вход\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";

        print_r($_POST);

        $errors = array();
        if(isset($_POST['enter']))
        {
            $email = htmlspecialchars($_POST['email']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            if(empty($email))
                $errors[] = User::ERROR_EMAIL_EMPTY;
            if(empty($username))
                $errors[] = User::ERROR_USERNAME_EMPTY;
            if(empty($password))
                $errors[] = User::ERROR_PASSWORD_EMPTY;
            $user = new User;
            if($user->findByUsername($username))
                $errors[] = User::ERROR_USER_ALREADY_EXIST;
            if(sizeof($errors)==0)
            {
                $user->setEmail($email);
                $user->setUsername($username);
                $user->setPassword($password);
                if($user->save())
                {
                    Route::redirect('index', 4);
                }
                else
                {

                }
            }
        }
        if(sizeof($errors)>0)
        {
            echo(sizeof($errors));
        }
    }
    public function verify($code)
    {
        $code = explode('/', $code, 2);
        $username = htmlspecialchars($code[0]);
        $verification = htmlspecialchars($code[1]);
        $user = new User;
        if($user->findByUsername($username))
        {
            if($verification == $user->verificationCode())
            {
                $user->setStatus(1);
                if($user->save())
                    $message = 'The email was successfully confirmed! Now you can to log in.';
                else
                    $errorMessage = 'Can\t update user information.';
            }
            else
                $errorMessage = 'Wrong confirmation code.';
        }
        else
            $errorMessage = 'User not found.';
        $view = new AppView('login');
        $view->set('pageTitle', 'Authentication');
        $view->set('message',$message);
        $view->set('errorMessage',$errorMessage);
        echo $view->output();
    }
    public function requestVerify()
    {
        $view = new AppView('requestVerify');
        $view->set('pageTitle', 'Requesting confirmation email');
        echo $view->output();
    }
    public function error403()
    {

    }
    public function error404()
    {

    }
}