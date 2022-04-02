<?php
    $cards = [
        [
            "title" => "Цитата",
            "type" => "post-quote",
            "description" => "Мы в жизни любим только раз, а после ищем лишь похожих",
            "userName" => "Лариса",
            "avatar" => "userpic-larisa-small.jpg"
        ],
        [
            "title" => "Игра престолов",
            "type" => "post-text",
            "description" => "Не могу дождаться начала финального сезона своего любимого сериала!",
            "userName" => "Владик",
            "avatar" => "userpic.jpg"
        ],
        [
            "title" => "Наконец, обработал фотки!",
            "type" => "post-photo",
            "description" => "rock-medium.jpg",
            "userName" => "Виктор",
            "avatar" => "userpic-mark.jpg",
        ],
        [
            "title" => "Моя мечта",
            "type" => "post-photo",
            "description" => "coast-medium.jpg",
            "userName" => "	Лариса",
            "avatar" => "userpic-larisa-small.jpg"
        ],
        [
            "title" => "Лучшие курсы",
            "type" => "post-link",
            "description" => "www.htmlacademy.ru",
            "userName" => "Владик",
            "avatar" => "userpic.jpg"
        ]
    ];
    $title = ['readme: популярное'];
    require('helpers.php');
    include_template('main.php', $cards);
    $allPage = include_template('layout.php', $title);
    print $allPage;
?>
