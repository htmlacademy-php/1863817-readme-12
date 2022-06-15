<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$userId = $_SESSION['userId'];

if (connect() == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  $posts = doQueryForType($userId);
}

$pageAmount = ceil(count($posts) / 6);

$sort = $_GET['sort'];

if ($sort === 'likes') {
  usort($posts, function ($a, $b) {
    return ($a['likes_amount'] < $b['likes_amount']);
  });
} else if ($sort === 'data') {
  usort($posts, function ($a, $b) {
    return (strtotime($a['post_date']) < strtotime($b['post_date']));
  });
} else if ($sort === 'popular' || (!isset($_GET['post']) && !isset($sort))) {
  usort($posts, function ($a, $b) {
    return ($a['number_of_views'] < $b['number_of_views']);
  });
}

if (count($posts) > 9) {
  $linkForMorePages = 1;
}

$pageNumber = $_GET['page'];

if (!isset($posts[$pageNumber * 6 + 1])) {
  $noMorePages = 1;
}

if ($linkForMorePage) {
  if ($pageNumber === 1) {
    $posts = array_slice($posts, 0, 6);
  } else {
    $posts = array_slice($posts, $pageNumber * 6 - 6, 6);
  }
}

$page_content = include_template('popular-template.php', ['cards' => $posts, 'linkForMorePage' => $linkForMorePages, 'noMorePages' => $noMorePages]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'avatar' => getAvatarForUser($_SESSION['username'])]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
