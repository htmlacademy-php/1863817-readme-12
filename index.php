<?php
require_once 'helpers.php';

function connect () {
    $con =  mysqli_connect("localhost", "root", "","readme");
    mysqli_set_charset($con, "utf8");

    return $con;
}

function doQuery ($conWithDatabase, $sql) {
    $result = mysqli_query($conWithDatabase, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rows;
}

if (connect () == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $rows_for_types = doQuery(connect (), "SELECT * FROM contentTypes");
    $posts = doQuery(connect (), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC");
}

function checkQueryForType () {
    if ($_GET['post'] === 1) {
        $posts = doQuery(connect(), "SELECT * FROM posts WHERE content_type = 'post-quote'");
    }
    if ($_GET['post'] === 2) {
        $posts = doQuery(connect(), "SELECT * FROM posts WHERE content_type = 'post-text'");
    }
    if ($_GET['post'] === 3) {
        $posts = doQuery(connect(), "SELECT * FROM posts WHERE content_type = 'post-photo'");
    }
    if ($_GET['post'] === 4) {
        $posts = doQuery(connect(), "SELECT * FROM posts WHERE content_type = 'post-link'");
    }
    if ($_GET['post'] === 5) {
        $posts = doQuery(connect(), "SELECT * FROM posts WHERE content_type = 'post-video'");
    }

    if (!$_GET['post']) {
        $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC");
    }

    return $posts;
}

function addClass ($param) {
    if ($_GET['post'] === $param) {
        return 'filters__button--active' ;
    }
}

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

function checkQueryForPost ($id) {
    if ($_GET['post-id'] === $id) {
        $post = doQuery(connect (), "SELECT * FROM posts WHERE post-id = '$id'");
    }

    return $post;
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

// if (isset($_GET['post-id']) && )

if (empty($_GET)) {
    $page_content = include_template('main.php', ['cards' => $posts, 'types' => $rows_for_types]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);
}

// $page_content = include_template('main.php', ['cards' => $posts, 'types' => $rows_for_types]);
// $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);

if (empty($_GET['post']) === false) {
    $page_content = include_template('main.php', ['cards' => checkQueryForType(), 'types' => $rows_for_types]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);
}

// if (isset($_GET['post-id'])) {
//     $page_content = include_template('main.php', ['cards' => checkQueryForType()]);
//     $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: публикация']);
// }

// $post_content = include_template('post.php', ['post' => checkQueryForPost($card["content_type"]), 'title' => 'readme: публикация']);

print($layout_content);
?>
