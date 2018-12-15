<!DOCTYPE html>
<html>
<head>
    <title>Geo</title>

    <meta charset="utf-8" />
    <!--	<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
    -->
    <link rel="stylesheet" type="text/css" href="http://tourdiary/styles/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
</head>
<body>

<div class="map" id="mapid"></div>

<div class="panel">
    <div class="logo-wrapper"><!--<a href="#"></a>-->
    </div>


    <div class="section-selector-wrapper">
        <div class="active">
            <a href="#">Дневник</a>
        </div>
        <div class="">
            <a href="#">Планировщик</a>
        </div>
    </div>

    <div class="dayly-menu">
        <div class="active">Мой дневник
        </div>
        <div>Пользователи
            <form id="user-form" action="/user/search" method="post">
                <input id="user-name" type="text" name="user">
                <input class="search-button" id="user-search" value="Search" label="User Search" type="image" src="http://tourdiary/images/Search.png">
            </form>
        </div>
        <div>Паблики
            <form id="public-form" action="/community/search" method="post">
                <input id="public-name" type="text" name="public">
                <input class="search-button" id="public-search" value="Search" alt="Submit" label="Public search" type="image" src="http://tourdiary/images/Search.png">
            </form>
        </div>
    </div>
    <div class="login-button">
        <?php
        $user = $_SESSION['user']['username'];
        if($user) {echo "<div><a href='http://tourdiary/logout'><img src='http://tourdiary/images/LogOut1.png' alt='Log out'></a></div>";echo "<div>$user</div>";}
        else echo '<div id="loginButton"><img src="http://tourdiary/images/LogIn1.png" alt="Log in"></div>'; //"<a href='http://tourdiary/logout'><img src='http://tourdiary/images/LogOut1.png' alt='Log out'></a><div>user</div>"
        ?>
    </div>
</div>




<div class="add-note" id="addNoteButton">
    <div>Add note</div>
</div>




<div class="screen-wrapper" id="loginWrapper">
    <div>
        <form id="login-form" action="/login" method="post">
            <!--<div class="wrapper-close">X</div>-->
            <div>
                <div>Username</div>
                <input id="email" type="text" name="username">
            </div>
            <div>
                <div>Пароль</div>
                <input id="password" type="password" name="password">
            </div>
            <div>
                <input id="login" value="Отправить" label="Log In" type="submit" name="enter">
            </div>
        </form>
        <form id="register-form" action="" method="post">
            <div>
                <h2>Регистрация<h2>
                        <div></div>
            </div>
        </form>
    </div>
</div>

<div class="screen-wrapper" id="addNoteWrapper">
    <div>
        <div class="close-wrapper">x</div>
    <form enctype="multipart/form-data" method="POST" action="/note/addbyuser">
        <!--<div class="wrapper-close">X</div>-->
        <div>
            <div>Заголовок</div>
            <input id="noteHeader" type="text" name="title">
        </div>
        <div>
            <div>Широта</div>
            <input id="latitude" type="text" name="longitude">
            <div class="button" id="latlng">Определить координаты</div>
            <div>Долгота</div>
            <input id="longitude" type="text" name="latitude">
        </div>
        <div>
            <div>Описание</div>
            <textarea id="description" type="description" name="message"></textarea>
        </div>
        <div>
            <div>Дата и время</div>
            <input type="date" name="date">
        </div>
        <div >
            <div>Фото</div>
            <div class="button" id="addPhoto">Добавить фото</div>
            <ul id="addPhotoList">
                <li><input type="file" name="image[]"><span class="del-photo">x</span></li>
            </ul>
        </div>
        <div>
            <input type="reset"  value="Очистить"> <input id="addNote" value="Отправить" label="addNote" type="submit" name="enter">
        </div>

    </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script src="http://tourdiary/scripts/mapadapter.js"></script>

<script>
    initMap(50.45014, 30.5241);
    console.log("1");
    <?php
    foreach($noteMas as $n)
    {
        $id = $n['note_id'];
        $title = $n['title'];
        $message = $n['message'];

        $vismessage = substr($message, 0, 256);
        $nonvismessage = substr($message, 256);

        $longitude = $n['longitude'];
        $latitude = $n['latitude'];
        $date = $n['date'];
        $photos = $note->selectPhoto($id);
        $photoList = "";
        if ($photos != null) {
            foreach ($photos as $p) {
                $idPhoto = $p['id'];
                //echo "<img src=\"http://tourdiary/photo/show/$idPhoto\">";
                $photoList .= '<li><img src="http://tourdiary/photo/show/' . $idPhoto . '"></li>';
                //$photoList .= '<li><a href="http://tourdiary/photo/show/'.$idPhoto.'"> teree </a></li>';
            }
        }
        echo "setNote($latitude, $longitude,
                '<h4 class=\"headline\">$title</h4>',
                '$photoList',
                '<div class=\"description\">$vismessage<span>$nonvismessage</span></div>'
			);";
    }

    ?>
    console.log("2");
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="http://tourdiary/scripts/script.js"></script>
</body>
</html>