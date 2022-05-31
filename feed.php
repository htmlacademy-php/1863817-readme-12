<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$con = connect();
$userId = $_SESSION['userId'];

if (isset($userId)) {
  $subs = doQuery($con, "SELECT id_receiver_sub from subscriptions where id_subscriber = $userId");
}

if ($subs) {
  foreach ($subs as $key => $sub) {
    $subId = $sub['id_receiver_sub'];

    if ($_GET['filter'] === 'all') {
      $subPosts = doQuery($con, "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND posts.id_user = $subId");
    } else {
      $filter = 'post-' . $_GET['filter'];
      $subPosts = doQuery($con, "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND posts.id_user = $subId AND content_type = '$filter'");
    }

    if ($subPosts) {
      foreach ($subPosts as $key => $post) {
        $postsWithoutLikes[] = $post;
      }
    }
  }
}

if ($postsWithoutLikes) {
  foreach ($postsWithoutLikes as $key => $value) {
    $idPost = $value['id_post'];
    $likesAmount = doQuery(connect(), "SELECT COUNT(id_post) from likes where id_post = $idPost");
    $value['likesAmount'] = $likesAmount[0]['COUNT(id_post)'];
    $amILikeThisPost = doQuery(connect(), "SELECT * from likes where id_post = $idPost and id_user = $userId");
    if ($amILikeThisPost) {
      $value['amILikeThisPost'] = 1;
    }
    $postsWithoutCommentsCount[] = $value;
  }
}

if ($postsWithoutCommentsCount) {
  foreach ($postsWithoutCommentsCount as $key => $value) {
    $idPost = $value['id_post'];
    $commentsAmount = doQuery(connect(), "SELECT COUNT(id_post) from comments where id_post = $idPost");
    $value['commentsAmount'] = $commentsAmount[0]['COUNT(id_post)'];
    $posts[] = $value;
  }
}

// echo ('<pre>');
// print_r($posts);
// echo ('</pre>');

$page_content = include_template('feed-template.php', ['posts' => $posts]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: моя лента', 'avatar' => getAvatarForUser($_SESSION['username'])]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
