<?php
require_once 'helpers.php';

$con = mysqli_connect("localhost", "root", "","readme");

mysqli_set_charset($con, "utf8");

if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$sql_get_types = "SELECT * FROM contentTypes";
$result_for_types = mysqli_query($con, $sql_get_types);
$rows_for_types = mysqli_fetch_all($result_for_types, MYSQLI_ASSOC);

$sql_get_posts = "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC";
$result_for_posts = mysqli_query($con, $sql_get_posts);
$rows_for_posts = mysqli_fetch_all($result_for_posts, MYSQLI_ASSOC);

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

date_default_timezone_set('Europe/Moscow');

function createUnixInterval ($dataForPost) {
    return strtotime('now') - strtotime($dataForPost);
}

function createTextForDate ($data)
{
    $resultInterval = createUnixInterval($data);

    if ($resultInterval / 60 / 60 < 1) {
        $resultNumber = ($resultInterval / 60);
        $rightForm = get_noun_plural_form($resultNumber, 'минута', 'минуты', 'минут');
    } else if ($resultInterval / 60 / 60 / 24 < 1) {
        $resultNumber = ($resultInterval / 60 / 60);
        $rightForm = get_noun_plural_form($resultNumber, 'час', 'часа', 'часов');
    } else if ($resultInterval / 60 / 60 / 24 >= 1 && $resultInterval / 60 / 60 / 24 < 7) {
        $resultNumber = ($resultInterval / 60 / 60 / 24);
        $rightForm = get_noun_plural_form($resultNumber, 'день', 'дня', 'дней');
    } else if ($resultInterval / 60 / 60 / 24 >= 7 and $resultInterval / 60 / 60 / 24 / 7 < 5) {
        $resultNumber = ($resultInterval / 60 / 60 / 24 / 7);
        $rightForm = get_noun_plural_form($resultNumber, 'неделя', 'недели', 'недель');
    } else if ($resultInterval / 60 / 60 / 24 / 7 > 5) {
        $resultNumber = floor($resultInterval / 60 / 60 / 24 / 30);
        $rightForm = get_noun_plural_form($resultNumber, 'месяц', 'месяца', 'месяцев');
    }

    if (isset($resultNumber, $rightForm) && !empty($resultNumber) && !empty($rightForm)) {
        return $resultNumber . ' ' . $rightForm . ' ' . 'назад';
    }

    return false;
}

$page_content = include_template('main.php', ['cards' => $rows_for_posts, 'types' => $rows_for_types]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);

print($layout_content);
?>
