<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 04.12.2018
 * Time: 18:40
 */

class Community extends AppModel
{
    const ERROR_NONE = '';
    const ERROR_NAME_EMPTY = 'Name can\'t be blank.';
    const ERROR_DESCRIPTION_EMPTY = 'Description can\'t be blank.';
    const ERROR_CREATOR_EMPTY = 'Creator can\'t be blank.';
    protected $_publicID;
    private $_name;
    private $_description;
    private $_creatorID;

    public function setPublicID($id)
    {
        $this->_publicID = $id;
    }

    public function getPublicID()
    {
        return $this->_publicID;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setCreator($creator)
    {
        $this->_creatorID = $creator;
    }

    public function getCreator()
    {
        return $this->_creatorID;
    }

    public function save() //охранить данные о паблике
    {
        if ($this->isNewRecord) {
            $sql = "INSERT INTO publics values(null, :name, :description, :creatorUsr_ID)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':name' => $this->_name,
                ':description' => $this->_description,
                ':creatorUsr_ID' => $this->_creatorID
            ));
            /*$sql = "INSERT INTO public_admins values(:user_id, :public_id)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':public_id' => $this->_publicID,
                ':user_id' => $this->_creatorID
            ));*/
            echo "Запрос прошел";
        } else {
            $sql = "UPDATE publics SET name = :name, description = :description, creatorUsr_ID = :creatorUsr_ID WHERE id = :id";
            $this->setSql($sql);
            $res = $this->query(array(
                ':id' => $this->_id,
                ':name' => $this->_name,
                ':description' => $this->_description,
                ':creatorUsr_ID' => $this->_creatorID
            ));
            echo "Запрос не прошел";
        }
        return $res ? true : false;
    }

    public function haveRights($publicID, $userID)
    { //проверяет являеться ли пользователь админом или оздателем
        $sql = "SELECT * FROM public_admins WHERE public_id = ? AND user_id = ?";
        $this->setSql($sql);
        $rowAdmin = $this->getRow(array($publicID, $userID));
        $sql = "SELECT * FROM publics WHERE public_id = ? AND creatorUsr_ID = ?";
        $this->setSql($sql);
        $rowCreator = $this->getRow(array($publicID, $userID));
        if ($rowCreator or $rowAdmin) {
            return 0;
        } else return 1;
    }

    public function yourRole($publicID, $userID)
    { //проверяет являеться ли пользователь админом или оздателем
        $sql = "SELECT * FROM public_admins WHERE public_id = ? AND user_id = ?";
        $this->setSql($sql);
        $rowAdmin = $this->getRow(array($publicID, $userID));
        $sql = "SELECT * FROM publics WHERE public_id = ? AND creatorUsr_ID = ?";
        $this->setSql($sql);
        $rowCreator = $this->getRow(array($publicID, $userID));
        if ($rowCreator) {
            $mess = "Роль: Вы создатель этого паблика.";
        } else if ($rowAdmin) {
            $mess = "Роль: Вы администратор этого паблика.";
        } else $mess = "Роль: Вы пользователь.";
        return $mess;
    }

    public function pubList()
    { //список пабликов
        $sql = "SELECT *
            FROM publics as p";
        $this->setSql($sql);
        $publics = $this->getAll();
        return ($publics) ? $publics : false;
    }

    public function myPubList()
    { //список пабликов
        $sql = "SELECT s.public_ID
            FROM subscribers as s WHERE user_id = ?";
        $this->setSql($sql);
        $publicsID = $this->getAll(array($_SESSION['user']['id']));
        foreach ($publicsID as $id) {
            $ourID = $id['public_ID'];
            $sql = "SELECT *
            FROM publics as p WHERE public_ID = ?";
            $this->setSql($sql);
            $publics[] = $this->getRow(array($ourID));
        }
        //print_r($publics);
        return ($publics) ? $publics : false;
    }

    public function findPub($pubID)
    { //список пабликов
        $sql = "SELECT *
            FROM publics as p WHERE public_ID = ?";
        $this->setSql($sql);
        $publics = $this->getRow(array($pubID));
        return ($publics) ? $publics : false;
    }

    public function subscribeToPublic($publicID)
    { //подписаться на паблик
        $userID = $_SESSION['user']['id'];
        $sql = "INSERT INTO subscribers values(:user_ID, :public_ID)";
        $this->setSql($sql);
        $res = $this->query(array(
            ':user_ID' => $userID,
            ':public_ID' => $publicID
        ));
    }

    public function unsubscribeToPublic($publicID)
    { //отписаться от паблика
        $userID = $_SESSION['user']['id'];
        $sql = "DELETE FROM subscribers WHERE user_ID = ? AND public_ID = ?";
        $this->setSql($sql);
        $res = $this->query(array($userID, $publicID));
    }

    public function deletePublic()
    { //удалить паблик
        $sql = "DELETE FROM publics 
                WHERE public_ID = ?";
        $this->setSql($sql);
        $this->query(array($this->_publicID));
    }

    public function searchPub($query)
    {
        //$query = trim($query);
        //$query = mysql_real_escape_string($query);
        //$query = htmlspecialchars($query);
        if (!empty($query)) {
                $sql = "SELECT *
                  FROM publics WHERE name LIKE '%$query%'";

                $this->setSql($sql);
                $res = $this->getAll();
                if ($res == null) {
                    $text = '<p>По вашему запросу ничего не найдено.</p>';
                }

            return $res;
        }
    }
}