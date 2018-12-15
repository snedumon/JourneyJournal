<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 04.12.2018
 * Time: 18:41
 */

class CommunityController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Community');
    }
    public function create(){ //создаём паблик
        echo("Add public<br>");
        //print_r($_POST);
        echo "Заполните форму:
        <form method=\"POST\" action=\"/community/create\">
        Название: <input type=\"text\" name=\"name\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Описание: <input type=\"text\" name=\"description\" required><br>
    
	    <input type=\"submit\" name=\"enter\" value=\"Добавить\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";

        $errors = array();
        if(isset($_POST['enter']))
        {
            $name = htmlspecialchars($_POST['name']);
            $description = htmlspecialchars($_POST['description']);
            $creator = $_SESSION['user']['id'];
            if(empty($name))
                $errors[] = Community::ERROR_NAME_EMPTY;
            if(empty($description))
                $errors[] = Community::ERROR_DESCRIPTION_EMPTY;
            if(empty($creator))
                $errors[] = Community::ERROR_CREATOR_EMPTY;
            $community = new Community;

            if(sizeof($errors)==0)
            {
                $community->setName($name);
                $community->setDescription($description);
                $community->setCreator($creator);
                if($community->save())
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
            print_r($name);
            print_r($description);
            print_r($creator);
            echo("Oshibka");
            echo(sizeof($errors));
        }
    }
    public function publicList(){ //список всех пабликов
        $community = new Community;
        $publicMas = $community->pubList();
        include ('app/view/publics.php');
    }
    public function myPublics(){ //список моих пабликов
        $community = new Community;
        $publicMas = $community->myPubList();
        include ('app/view/myPublics.php');
    }
    public function edit($public_id){ //редактировать паблик
        echo("Edit public<br>");
        //print_r($_POST);
        echo "Заполните форму:
        <form method=\"POST\" action=\"/community/create\">
        Название: <input type=\"text\" name=\"name\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Описание: <input type=\"text\" name=\"description\" required><br>
    
	    <input type=\"submit\" name=\"enter\" value=\"Добавить\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";

        $errors = array();
        if(isset($_POST['enter']))
        {
            $name = htmlspecialchars($_POST['name']);
            $description = htmlspecialchars($_POST['description']);
            $creator = $_SESSION['user']['id'];
            if(empty($name))
                $errors[] = Community::ERROR_NAME_EMPTY;
            if(empty($description))
                $errors[] = Community::ERROR_DESCRIPTION_EMPTY;
            if(empty($creator))
                $errors[] = Community::ERROR_CREATOR_EMPTY;
            $community = new Community;
            $community->isNewRecord = false;

            if(sizeof($errors)==0)
            {
                $community->setName($name);
                $community->setDescription($description);
                $community->setCreator($creator);
                if($community->save())
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
            print_r($name);
            print_r($description);
            print_r($creator);
            echo("Oshibka");
            echo(sizeof($errors));
        }
    }
    public function delete($public_id){ //удалить паблик
        $community = new Community();
        $community->setPublicID($public_id);
        $community->deletePublic();
    }
    public function subscribe($public_id) { //подписатья на паблик
        $community = new Community();
        $community->subscribeToPublic($public_id);
        Route::redirect('community/publiclist', 0);
    }
    public function unsubscribe($public_id) {
        $community = new Community();
        $community->unsubscribeToPublic($public_id);
        Route::redirect('community/myPublics', 0);
    }
    /*
    public function addNote($publicID){
        //проверка на админа
        $community = new Community;
        $user_ID = $_SESSION['user']['id'];
        if (!($community->haveRights($publicID, $user_ID))) return false;
        echo("Add note to public");
        echo "Заполните форму:
        <form method=\"POST\" action=\"/community/addnote/$publicID\">
        Текст: <input type=\"text\" name=\"message\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Локация: <input type=\"text\" name=\"latitude\" required><br>
        Локация: <input type=\"text\" name=\"longitude\" required><br>
        <input type=\"date\" name=\"date\">

	    <input type=\"submit\" name=\"enter\" value=\"Вход\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";

        $errors = array();
        if(isset($_POST['enter']))
        {
            $message = htmlspecialchars($_POST['message']);
            $date = htmlspecialchars($_POST['date']);
            $latitude = htmlspecialchars($_POST['latitude']);
            $longitude = htmlspecialchars($_POST['longitude']);
            //$rating = htmlspecialchars($_POST['rating']);
            if(empty($message))
                $errors[] = Note::ERROR_MESSAGE_EMPTY;
            if(empty($date))
                $errors[] = Note::ERROR_DATE_EMPTY;
            if(empty($latitude))
                $errors[] = Note::ERROR_LOCATION_EMPTY;
            if(empty($longitude))
                $errors[] = Note::ERROR_LOCATION_EMPTY;
            $note = new Note;

            if(sizeof($errors)==0)
            {
                $note->setMessage($message);
                $note->setDate($date);
                $note->setLatitude($latitude);
                $note->setLongitude($longitude);
                $note->setPublicID($publicID);
                if($note->save())
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
    }*/

    public function search(){
        $query = $_POST['public'];
        $comm = new Community;
        $publicMas = $comm->searchPub($query);
        //print_r($publicMas);
        include ('app/view/publics.php');
    }
}