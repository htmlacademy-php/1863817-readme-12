<?php
require_once 'helpers.php';

function addLinkForBigText ($string, $symbols = 300) {
    if (is_string($string)) {
        $arrayWords = explode (" ", $string);
        $newString ='';
        $length = 0;
        $cut = false;
        foreach ($arrayWords as $key => $value) {
            $length += iconv_strlen($value);
            $newString .= ' ' . $value;
            if ($length >= 300) {
                $newString = $newString . '...';
                $cut = true;
                break;
            } else {
                $newString = implode (" ", $arrayWords);
            }
        }
    }

    return [$newString, $cut];
}

$is_auth = rand(0, 1);

$user_name = 'Макарий';

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
        "description" => "Не могу дождаться начала финального сезона своего любимого сериала! ",
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

date_default_timezone_set('Europe/Moscow');

function createUnixInterval ($dataForPost) {

    $dataNow = strtotime('now');
    $dataPostInUnix = strtotime($dataForPost);

    $resultInterval = $dataNow - $dataPostInUnix;

    return $resultInterval;
}

function createTextForDate ($data) {
    $resultInterval = createUnixInterval ($data);

    if ($resultInterval / 60 / 60 < 1) {
        $resultNumber = ($resultInterval / 60);
        $rightForm = get_noun_plural_form($resultNumber, 'минута', 'минуты', 'минут');
        $finalString = $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    } else if ($resultInterval / 60 / 60 / 24 < 1) {
        $resultNumber = ($resultInterval / 60 / 60);
        $rightForm = get_noun_plural_form($resultNumber, 'час', 'часа', 'часов');
        $finalString = $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    } else if ($resultInterval / 60 / 60 / 24 >= 1 && $resultInterval / 60 / 60 / 24 < 7) {
        $resultNumber = ($resultInterval / 60 / 60 / 24);
        $rightForm = get_noun_plural_form($resultNumber, 'день', 'дня', 'дней');
        $finalString = $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    } else if ($resultInterval / 60 / 60 / 24 >= 7 and $resultInterval / 60 / 60 / 24 / 7 < 5) {
        $resultNumber = ($resultInterval / 60 / 60 / 24 / 7);
        $rightForm = get_noun_plural_form($resultNumber, 'неделя', 'недели', 'недель');
        $finalString = $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    } else if ($resultInterval / 60 / 60 / 24 / 7 > 5) {
        $resultNumber = floor($resultInterval / 60 / 60 / 24 / 30);
        $rightForm = get_noun_plural_form($resultNumber, 'месяц', 'месяца', 'месяцев');
        $finalString = $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    }

    return $finalString;
}

$page_content = include_template('main.php', ['cards' => $cards]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);

print($layout_content);
?>
