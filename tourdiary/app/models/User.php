<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:18
 */

class User extends AppModel
{
    const ERROR_NONE = '';
    const ERROR_USERNAME_EMPTY = 'Username can\'t be blank.';
    const ERROR_PASSWORD_EMPTY = 'Password can\'t be blank.';
    const ERROR_EMAIL_EMPTY = 'Email can\'t be blank.';
    const ERROR_USER_ALREADY_EXIST = 'User with such username already exists.';
    protected  $_id;
    protected  $_username;
    private $_password;
    private $_email;
    private $_city;
    private $_active = 0;

    public function findByUsername($username, $activeOnly = false) //найти пользователя по имени
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        if($activeOnly)
            $sql .= " AND active = 1";
        $this->setSql($sql);
        $row = $this->getRow(array($username));
        if($row)
        {
            $this->isNewRecord = false;
            $this->_password = $row['password'];
            $this->_id = $row['id'];
            $this->_username = $row['username'];
            $this->_email = $row['email'];
            $this->_active = $row['active'];
            $this->_city = $row['city'];
            return true;
        }
        return false;
    }
    /**
     * Checks whether user exist
     * @param $username
     * @param $email
     * @return bool|mixed
     */
    public function findUser($username, $email) //найти по имейлу и никнейму
    {
        $sql = "SELECT * FROM users WHERE username = :username AND email = :email";
        $this->setSql($sql);
        $user = $this->getRow(array($username, $email));
        return ($user) ? $user : false;
    }
    public function getUsersCount() //количество пользователей
    {
        $sql = "SELECT count(id) as count FROM users";
        $this->setSql($sql);
        $row = $this->getRow();
        return $row['count'];
    }
    public function save() //редактировать или создать пользователя
    {
        if(!$this->validate()) return false;
        if($this->isNewRecord)
        {
            $sql = "INSERT INTO users values(null, :username, :password, :email, :city, :active)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':username' => $this->_username,
                ':password' => $this->_password,
                ':email' => $this->_email,
                ':city' => $this->_city,
                ':active' => $this->_active
            ));
            echo "Запрос прошел";
        }
        else
        {
            $sql = "UPDATE users SET username = :username, email = :email, password = :password, city = :city, active = :active WHERE id = :id";
            $this->setSql($sql);
            $res = $this->query(array(
                ':id' => $this->_id,
                ':username' => $this->_username,
                ':password' => $this->_password,
                ':email' => $this->_email,
                ':city' => $this->_city,
                ':active' => $this->_active
            ));
            echo "Запрос не прошел";
        }
        return $res ? true : false;
    }
    public function validate()
    {
        if(!$this->isNewRecord && empty($this->_id)) return false;
        if(empty($this->_username)) return false;
        if(empty($this->_password)) return false;
        if(!filter_var($this->_email, FILTER_VALIDATE_EMAIL)) return false;
        if(!in_array($this->_active, array(0, 1))) return false;
        return true;
    }
    public function delete($userId) //удаление пользователя
    {
        if(empty($userId)) return false;
        $sql = "DELETE FROM users WHERE id = :userId; ";
        //$sql .= "DELETE FROM user_role WHERE user_id = :userId; ";
        //$sql .= "UPDATE blog SET published = 0 WHERE author_id = :userId; ";
        $this->setSql($sql);
        return $this->query(array(':userId'=>$userId));
    }
    public function getId()
    {
        return $this->_id;
    }
    public function setId($id)
    {
        $this->_id = (int)$id;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    public function getUsername()
    {
        return $this->_username;
    }
    public function setUsername($username)
    {
        $this->_username = $username;
    }
    public function getPassword()
    {
        return $this->_password;
    }
    public function setPassword($password)
    {
        $this->_password = $this->hashPassword($password);
    }
    public function getStatus()
    {
        return $this->_active;
    }
    public function setStatus($active)
    {
        $this->_active = $active ? 1 : 0;
    }
    public function getCity()
    {
        return $this->_city;
    }
    public function setCity($city)
    {
        $this->_city = $city;
    }
    /*public function sendVerificationMail()
    {
        if(empty($this->_email) || empty($this->_password) || empty($this->_username))
            return false;
        $verificationUrl = 'http://'.SITE_URL.'/site/verify/'.$this->_username.'/'.$this->verificationCode();
        $view = new AppView('_registerVerification');
        $view->set('verificationUrl', $verificationUrl);
        $message = $view->output();
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        if(mail($this->_email, 'Подтверждение регистрации на сайте SiteName', $message, $headers))
            return true;
        else
            return false;
    }*/
    /**
     * Validates password
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        /*echo 'User pass: '.$password.
            '<br>DB pass: '.$this->_password.
            '<br>Batman hash: '.$this->hashPassword('batman').
            '<br>Crypt: '.crypt($password, $this->_password);*/
        return crypt($password, $this->_password)===$this->_password; //crypt() возвращает хешированную строку, полученную с помощью стандартного алгоритма UNIX, основанного на DES или другого алгоритма, имеющегося в системе.
    }
    public function verificationCode()
    {
        return crypt($this->_password, $this->_username);
    }
    /**
     * Generate hash of password
     * @param $password
     * @return string Password's hash
     */
    public function hashPassword($password)
    {
        return crypt($password, $this->blowfishSalt());
    }
    /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     */
    public static function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }
        $rand = array();
        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }
        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . str_pad((int) $cost, 2, '0', STR_PAD_RIGHT) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }
    public function searchUsr($query)
    {
        //$query = trim($query);
        //$query = mysql_real_escape_string($query);
        //$query = htmlspecialchars($query);
        if (!empty($query)) {
            $sql = "SELECT *
                  FROM users WHERE username LIKE '%$query%'";

            $this->setSql($sql);
            $res = $this->getAll();
            if ($res == null) {
                $text = '<p>По вашему запросу ничего не найдено.</p>';
            }

            return $res;
        }
    }
    public function allInfo($userID)
    {
        $sql = "SELECT u.username, u.email, p.name
                  FROM users AS u INNER JOIN publics AS p ON u.id = creatorUsr_ID";
        $this->setSql($sql);
        $res = $this->getAll();
        return $res;
    }
}