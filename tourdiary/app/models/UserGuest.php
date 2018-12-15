<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 01.12.2018
 * Time: 16:25
 */
//пока не использую
class UserGuest extends User
{
    const GUEST_ROLE_ID = 1;
    public function __construct()
    {
        parent::__construct();
        $this->showMessage(self::GUEST_ROLE_ID);
    }
    private function showMessage($message)
    {
        //echo("USER NOT LOGINED<br>");
    }
}