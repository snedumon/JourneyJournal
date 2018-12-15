<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:22
 */

class NoteController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Note');
    }
    public function addByUser(){ //добавление пользователем заметки
        /*echo("Add note<br>");
            echo "Заполните форму:
        <form enctype=\"multipart/form-data\" method=\"POST\" action=\"/note/addbyuser\">
        Заголовок: <input type=\"text\" name=\"title\"><br>
        Текст: <input type=\"text\" name=\"message\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Локация: <input type=\"text\" name=\"latitude\" required><br>
        Локация: <input type=\"text\" name=\"longitude\" required><br>
        <input type=\"date\" name=\"date\">
        Изображение: <input type=\"file\" name=\"image[]\" /><input type=\"file\" name=\"image[]\" />
    
	    <input type=\"submit\" name=\"enter\" value=\"Добавить\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";*/
            //print_r(_POST);

            $errors = array();
            if (isset($_POST['enter'])) {
                $title = htmlspecialchars($_POST['title']);
                $message = htmlspecialchars($_POST['message']);
                $date = htmlspecialchars($_POST['date']);
                $latitude = htmlspecialchars($_POST['latitude']);
                $longitude = htmlspecialchars($_POST['longitude']);
                //$rating = htmlspecialchars($_POST['rating']);
                $user_ID = $_SESSION['user']['id'];

                //print_r($_FILES);
                //print_r($_FILES['image']['name'][0]);
                $image = array();
                $size = array();
                $name = array();
                $type = array();
                for ($i = 0; isset($_FILES['image']['name'][$i]); $i++) {
                    if ($_FILES ['image']['tmp_name'][$i]) {
                        $image[] = file_get_contents($_FILES ['image']['tmp_name'][$i]); //Читает содержимое файла в строку
                    }
                        $size[] = filesize ($_FILES['image']['tmp_name'][$i]);
                        $name[] = $_FILES["image"]["name"][$i];
                        $type[] = $_FILES["image"]["type"][$i];
                }
                //print_r($name);
                if (empty($message))
                    $errors[] = Note::ERROR_MESSAGE_EMPTY;
                if (empty($date))
                    $errors[] = Note::ERROR_DATE_EMPTY;
                if (empty($latitude))
                    $errors[] = Note::ERROR_LOCATION_EMPTY;
                if (empty($longitude))
                    $errors[] = Note::ERROR_LOCATION_EMPTY;
                $note = new Note;
                //echo sizeof($errors);

                if (sizeof($errors) == 0) {
                    $note->setTitle($title);
                    $note->setMessage($message);
                    $note->setDate($date);
                    $note->setLatitude($latitude);
                    $note->setLongitude($longitude);
                    $note->setUserID($user_ID);
                    $note->setImages($image,$size,$name,$type);
                    if ($note->save()) {
                        Route::redirect('note/usrnotes', 0);
                    } else {

                    }
                }
            }
            if (sizeof($errors) > 0) {
                echo(sizeof($errors));
            }
    }
    public function addByPublic($publicID){ //добавление пабликом заметки
        //проверка на админа
        $community = new Community;
        $user_ID = $_SESSION['user']['id'];
        $cheak = $community->haveRights($publicID, $user_ID);
        if ($cheak) return false;
        /*echo("Add note to public");
        echo "Заполните форму:
        <form enctype=\"multipart/form-data\" method=\"POST\" action=\"/note/addbypublic/$publicID\">
        Текст: <input type=\"text\" name=\"message\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Локация: <input type=\"text\" name=\"latitude\" required><br>
        Локация: <input type=\"text\" name=\"longitude\" required><br>
        <input type=\"date\" name=\"date\">
    
	    <input type=\"submit\" name=\"enter\" value=\"Добавить\">
        <input type=\"reset\"  value=\"Очистить\">
        </form>";*/

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
                    Route::redirect('note/usrnotes', 0);
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
    public function usrNotes() { //список заметок пользователя
        $note = new Note;
        $noteMas = $note->userNoteList($_SESSION['user']['id']);
        //print_r($noteMas);
        /*foreach($noteMas as $n)
        {
            $id = $n['note_id'];
            $message = $n['message'];
            $date = $n['date'];
            echo "$id \n $message \n $date";
            echo "<script>initMap();
			setNote(15.45014, 44.5241,
                '<h4 class=\"headline\">Заголовочек</h4>',
                '<li><img src=\"images/tst1.jpg\"></li>'+
                '<li><img src=\"images/tst2.jpg\"></li>',
                '<div class=\"description\">Как я оказался в ОАЭ? Ну тут вариантов немного: дурная голова рукам покоя не даёт, да и бережно скопленный кэш-бэк программы лояльности клиентов банка «Открытие» тоже. <span>Холодная уральская осень, легкое депрессивное настроение, отсутствие сна и шёпот банковской карточки «халява, халява, халява» заставили меня скорректировать рабочий график, совместить это дело с ноябрьскими праздниками, вот я и на чемоданах. Поэтому если Вы до сих пор не верите в священную силу кэш-бэка, то делаете это зря. Тем не менее, одними билетами сыт не будешь, поэтому Вашему вниманию предлагаются авторские программа и взгляд краткого знакомства с этой необычной страной.</span></div>',
			); </script>";
        }*/
        include ('app/view/journal.php');

    }
    public function pubNotes($publicID) { //список заметок пабликов
        $note = new Note;
        $noteMas = $note->publicNoteList($publicID);
        /*foreach($noteMas as $n)
        {
            $id = $n['note_id'];
            $message = $n['message'];
            $date = $n['date'];
            $location = $n['location'];
            echo "$id \n $message \n $date \n $location";
        }*/
        include ('app/view/showPublic.php');
    }
    public function show($note_id) { //показать заметку
        $note = new Note;
        $noteOnce = $note->selectNote($_SESSION['user']['id'], $note_id);
            $id = $noteOnce['note_id'];
            $message = $noteOnce['message'];
            $date = $noteOnce['date'];
            //$location = $noteOnce['location'];
        echo "$id \n $message \n $date";
        $photos = $note->selectPhoto($note_id);
        foreach($photos as $p)
        {
            $id = $p['id'];
            echo "<img src=\"http://tourdiary/photo/show/$id\">";

        }
    }
    public function edit($note_id){ //редактировать заметку
        echo("Edit note<br>");
        echo "Заполните форму:
        <form method=\"POST\" action=\"/note/edit\">
        Текст: <input type=\"text\" name=\"message\" maxlength=\"10\" pattern=\"[A-Za-z-0-9]{3,10}\" title=\"\" required><br>
        Локация: <input type=\"text\" name=\"latitude\" required><br>
        Локация: <input type=\"text\" name=\"longitude\" required><br>
        <input type=\"date\" name=\"date\">
    
	    <input type=\"submit\" name=\"enter\" value=\"Исправить\">
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
            $user_ID = $_SESSION['user']['id'];
            if(empty($message))
                $errors[] = Note::ERROR_MESSAGE_EMPTY;
            if(empty($date))
                $errors[] = Note::ERROR_DATE_EMPTY;
            if(empty($latitude))
                $errors[] = Note::ERROR_LOCATION_EMPTY;
            if(empty($longitude))
                $errors[] = Note::ERROR_LOCATION_EMPTY;

            $note = new Note;
            $note->isNewRecord = false;

            if(sizeof($errors)==0)
            {
                $note->setUserID($note_id);
                $note->setMessage($message);
                $note->setDate($date);
                $note->setLatitude($latitude);
                $note->setLongitude($longitude);
                $note->setUserID($user_ID);
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
    }
    public function delete($note_id){ //удаление заметки
        $note = new Note;
        $note->setUserID($note_id);
        $note->deleteNote();
    }
}