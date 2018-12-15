<?php session_start();//Стартует новую сессию, либо возобновляет существующую

/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 30.11.2018
 * Time: 11:26
 */
//phpinfo();
define('ROOT', dirname(__FILE__)); //__FILE__	Полный путь и имя текущего файла с развернутыми симлинками. Если используется внутри подключаемого файла, то возвращается имя данного файла. dirname - Возвращает имя родительского каталога из указанного пути
define('SITE_URL', $_SERVER['HTTP_HOST']); //Информация о сервере и среде исполнения, Содержимое заголовка Host: из текущего запроса, если он есть. В элементе $_SERVER['HTTP_HOST'] содержится имя сервера, которое, как правило, совпадает с доменным именем сайта, расположенного на сервере
ini_set('display_errors', true); //Устанавливает значение настройки конфигурации
date_default_timezone_set('Europe/Kiev'); //устанавливает временную зону по умолчанию для всех функций даты/времени в скрипте.
require_once 'app/config.php'; //Выражение require_once аналогично require за исключением того, что PHP проверит, включался ли уже данный файл, и если да, не будет включать его еще раз.
Route::start();
function __autoload($class) //автозагрузка классов, вызывается каждый раз, когда создается объект неизвестного класса
{
    if(file_exists('app/models/'.$class.'.php'))
        require_once 'app/models/'.$class.'.php';
    else if(file_exists('app/controllers/'.$class.'.php'))
        require_once 'app/controllers/'.$class.'.php';
    else if(file_exists('app/core/'.$class.'.php'))
        require_once 'app/core/'.$class.'.php';
    else if(file_exists('app/components/'.$class.'.php'))
        require_once 'app/components/'.$class.'.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

</body>
</html>