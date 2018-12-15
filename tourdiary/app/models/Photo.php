<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 09.12.2018
 * Time: 21:19
 */

class Photo extends AppModel
{
    public function findPhoto($photoID) //поиск фото
    {
        $sql = "SELECT * FROM photos as p
            WHERE p.id = ?";
        $this->setSql($sql);
        $photo = $this->getRow(array($photoID));
        return ($photo)? $photo : false;
    }
    public function deletePhoto($photoID){ //удаление фото

    }
}