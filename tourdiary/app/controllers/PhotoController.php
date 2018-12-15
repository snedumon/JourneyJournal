<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 09.12.2018
 * Time: 21:31
 */
class PhotoController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Photo');
        $this->setView('manage'); //доделать
    }
    public function show($photoID) //страница фото
    {
        $photo = new Photo();
        $img = $photo->findPhoto($photoID);
        header('Content-Type: image/jpeg');
        echo $img['bin_data'];
    }
}