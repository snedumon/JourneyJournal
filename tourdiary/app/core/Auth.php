<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:45
 */

//тут со всем согласен
class Auth
{
    const ERROR_NONE = '';
    const ERROR_USERNAME_INVALID = 'Username invalid';
    const ERROR_PASSWORD_INVALID = 'Password invalid';
    private $_username;
    private $_password;
    private $_errorCode;
    private $_id;
    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }
    public function authenticate()
    {
        $user = new User;
        if($user->findByUsername($this->_username, false)) //true лучше
        {
            if($user->validatePassword($this->_password))
            {
                $this->_errorCode = self::ERROR_NONE;
                $this->_id = $user->getId();
            }
            else
            {
                $this->_errorCode = self::ERROR_PASSWORD_INVALID;
            }
        }
        else
        {
            $this->_errorCode = self::ERROR_USERNAME_INVALID;
        }
        return $this->_errorCode==self::ERROR_NONE;
    }
    public function login()
    {
        if($this->authenticate())
        {
            $_SESSION['user']['username'] = $this->_username;
            $_SESSION['user']['id'] = $this->_id;
            //print_r($_SESSION);
            return true;
        }
        else {
            return false;
        }
    }
}