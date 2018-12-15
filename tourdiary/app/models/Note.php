<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:21
 */

class Note extends AppModel
{
    const ERROR_NONE = '';
    const ERROR_MESSAGE_EMPTY = 'Message can\'t be blank.';
    const ERROR_DATE_EMPTY = 'Date can\'t be blank.';
    const ERROR_LOCATION_EMPTY = 'Location can\'t be blank.';
    protected $_id;
    private $_title;
    private $_message;
    private $_date;
    private $_latitude;
    private $_longitude;
    private $_rating;
    private $_images = array(); //сделать массив
    private $_imgsize = array();
    private $_imgname = array();
    private $_imgtype = array();
    private $_userID;
    private $_publicID;

    public function userNoteList($userID) { //список заметок пользователя
        $sql = "SELECT n.note_id, n.title, n.longitude, n.latitude, n.message, n.date
            FROM notes as n
            WHERE n.user_id = ?";
        $this->setSql($sql);
        $notes = $this->getAll(array($userID));
        return ($notes)? $notes : false;
    }
    public function publicNoteList($publicID) { //список заметок паблика
        $sql = "SELECT n.note_id, n.title, n.longitude, n.latitude, n.message, n.date
            FROM notes as n
            WHERE n.public_id = ?";
        $this->setSql($sql);
        $notes = $this->getAll(array($publicID));
        return ($notes)? $notes : false;
    }
    public function selectNote($userID, $note_id) { //выбрать конкретную заметку
        $sql = "SELECT n.note_id, n.message, n.date
            FROM notes as n
            WHERE n.user_id = ? AND n.note_id = ?";
        $this->setSql($sql);
        $notes = $this->getRow(array($userID, $note_id));
        return ($notes)? $notes : false;
    }
    public function selectPhoto($note_id) { //выбрать фото к заметке
        $sql = "SELECT * FROM photos as p
            WHERE p.note_id = ?";
        $this->setSql($sql);
        $photos = $this->getAll(array($note_id));
        return ($photos)? $photos : false;
    }
    public function deleteNote(){ //удаление заметки
        $sql = "DELETE FROM notes 
                WHERE note_ID = ?";
        $this->setSql($sql);
        $this->query(array($this->_id));
    }
    public function updateRating($note_id){ //обновить рейтинг

    }
    public function setMessage($message) {
        $this->_message = $message;
    }
    public function setDate($date) {
        $this->_date = $date;
    }
    public function setLatitude($latitude) {
        $this->_latitude = $latitude;
    }
    public function setLongitude($longitude) {
        $this->_longitude = $longitude;
    }
    public function getMessage()
    {
        return $this->_message;
    }
    public function getDate()
    {
        return $this->_date;
    }
    public function getLatitude()
    {
        return $this->_latitude;
    }
    public function getLongitude()
    {
        return $this->_longitude;
    }
    public function setImages($images,$size,$name,$type) {
        //print_r($images);
        $this->_images = $images;
        $this->_imgsize = $size;
        $this->_imgname = $name;
        $this->_imgtype = $type;
    }
    public function getImages()
    {
        return $this->_images;
    }
    public function setUserID($id) {
        $this->_userID = $id;
    }
    public function getUserID()
    {
        return $this->_userID;
    }
    public function setPublicID($id) {
        $this->_publicID = $id;
    }
    public function getPublicID()
    {
        return $this->_publicID;
    }
    public function setTitle($title) {
        $this->_title = $title;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function save() //сохранить или изменить заметку
    {
        if($this->isNewRecord)
        {
            $sql = "INSERT INTO notes values(null, :title, :message, :datetime, :latitude, :longitude, :rating, :user_id, :public_id)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':title' => $this->_title,
                ':message' => $this->_message,
                ':datetime' => $this->_date,
                ':latitude' => $this->_latitude,
                ':longitude' => $this->_longitude,
                ':rating' => $this->_rating,
                ':user_id' => $this->_userID,
                ':public_id' => $this->_publicID
            ));
            $id = $this->db->lastInsertId();
            $sql = "INSERT INTO photos VALUES(null, :image, :imgname, :imgsize, :imgtype, :note_id, null)";
            $this->setSql($sql);
            //print_r($this->_imgsize);
            for($i =0; $i < count($this->_images); $i++) {
                $res1 = $this->query(array(
                    ':image' => $this->_images[$i],
                    ':imgname' => $this->_imgname[$i],
                    ':imgsize' => $this->_imgsize[$i],
                    ':imgtype' => $this->_imgtype[$i],
                    ':note_id' => $id
                ));
            }
        }
        else
        {
            $sql = "UPDATE notes SET title =:title, message = :message, datetime = :datetime, latitude = :latitude, longitude = :longitude, rating = :rating, user_id = :user_id, public_id = :public_id WHERE id = :id";
            $this->setSql($sql);
            $res = $this->query(array(
                ':id' => $this->_id,
                ':title' => $this->_title,
                ':message' => $this->_message,
                ':datetime' => $this->_date,
                ':latitude' => $this->_latitude,
                ':longitude' => $this->_longitude,
                ':rating' => $this->_rating,
                ':user_id' => $this->_userID,
                ':public_id' => $this->_publicID
            ));
            echo "Запрос не прошел";
        }
        return $res ? true : false;
    }

}