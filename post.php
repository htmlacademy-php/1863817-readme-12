<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$con = connect();

$id = $_GET['post-id'];
$updateViews = mysqli_query($con, "UPDATE posts SET number_of_views = number_of_views + 1 WHERE id_post = $id");

$commentsWithoutAllInfo = getEssenceForPost('comments');

foreach ($commentsWithoutAllInfo as $key => $comment) {
  $AuthorCommentId = $comment['id_user'];
  $author = doQuery($con, "SELECT user_login, avatar_link FROM users WHERE id_user = $AuthorCommentId");
  $authorLogin = $author[0]['user_login'];
  $authorAvatar = $author[0]['avatar_link'];
  $comment['user_login'] = $authorLogin;
  $comment['avatar_link'] = $authorAvatar;
  $comments[] = $comment;
}

if (isset($comments) && !empty($comments)) {
  if (count($comments) > 2 && !isset($_GET['comments'])) {
    $firstLength = count($comments);
    $comments = array_slice($comments, 0, 2);
    $moreCommentsExist = $firstLength - 2;
  }
}

$card = getPostById();
$userId = $_SESSION['userId'];
$amILikeThisPost = doQuery(connect(), "SELECT * from likes where id_post = $id and id_user = $userId");
if ($amILikeThisPost) {
  $card[0]['amILikeThisPost'] = 1;
}
$profileId = $card[0]["id_user"];
$amISubOnMainProfile = doQuery($con, "SELECT * from subscriptions where id_receiver_sub = $profileId AND id_subscriber = $userId");


if (isset($amISubOnMainProfile) && !empty($amISubOnMainProfile)) {
  $amISubOnMainProfile = 1;
} else {
  $amISubOnMainProfile = 0;
}

$registrationDate = doQuery($con, "SELECT registration_date from users where id_user = $profileId");
$registrationDate = $registrationDate[0]['registration_date'];

if ($con == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  if (isset($_GET['post-id']) && empty($_GET['post-id']) === false) {
    if (empty($card)) {
      $layout_content = include_template('layout.php', ['content' => 'ERROR 404', 'title' => 'readme: публикация', 'avatar' => getAvatarForUser($_SESSION['username'])]);
    } else {
      $page_content = include_template(
        'post-details.php',
        [
          'amISubOnMainProfile' => $amISubOnMainProfile,
          'registrationDate' => $registrationDate,
          'moreCommentsExist' => $moreCommentsExist,
          'card' => $card,
          'subscriptions' => getSubById(),
          'posts' => getAllPostsPostsById(),
          'likes' => getEssenceForPost('likes'),
          'comments' => $comments,
          'tags' => getEssenceForPost('hashtags')
        ]
      );
      $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: публикация', 'avatar' => getAvatarForUser($_SESSION['username'])]);
    }
  }
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
