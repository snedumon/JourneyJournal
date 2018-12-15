<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 12:02
 */

class AppModel
{
    protected $db;
    protected $sql;
    public $isNewRecord = true; //обновляем ли мы существующую запись или добавляем новую
    public function __construct()
    {
        $this->db = DB::init();
    }
    protected function setSql($sql)
    {
        $this->sql = $sql;
    }
    public function getAll($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');
        $sth = $this->db->prepare($this->sql);
        $sth->execute($data); //сюда передаем то что под знаком ?
        return $sth->fetchAll();//Возвращает массив, содержащий все строки результирующего набора
    }
    public function getRow($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');
        $sth = $this->db->prepare($this->sql); //Подготавливает запрос к выполнению и возвращает связанный с этим запросом объект
        $sth->execute($data); //Запускает подготовленный запрос на выполнение
        return $sth->fetch(); //Извлечение следующей строки из результирующего набора
    }
    public function query($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');
        $sth = $this->db->prepare($this->sql);
        $res = $sth->execute($data);
        return $res;
    }
}