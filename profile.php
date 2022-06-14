<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$profileId = test_input($con, $_GET['id']);
$userId = $_SESSION['userId'];
$userInfo = doQuery($con, "SELECT user_login, registration_date FROM users WHERE id_user = $profileId");
$login = $userInfo[0]['user_login'];
$registrationDate = $userInfo[0]['registration_date'];

list($subscriptionsAmount) = doQuery($con, "SELECT COUNT(id_subscriber) AS count_subs FROM subscriptions WHERE id_receiver_sub = $profileId");
$subscriptionsAmount = $subscriptionsAmount['count_subs'];

$postsAmount = doQuery($con, "SELECT COUNT(id_post) AS posts_amount FROM posts WHERE id_user = $profileId");
$postsAmount = $postsAmount[0]['posts_amount'];

if ($login === $_SESSION['username']) {
  $isMyProfile = true;
} else {
  $isMyProfile = false;
}

$amISubOnMainProfile = doQuery($con, "SELECT * from subscriptions where id_receiver_sub = $profileId AND id_subscriber = $userId");

if (isset($amISubOnMainProfile) && !empty($amISubOnMainProfile)) {
  $amISubOnMainProfile = 1;
} else {
  $amISubOnMainProfile = 0;
}

if ($_GET['active'] === 'posts') {

  $posts = doQuery($con, "SELECT posts.*, COUNT(likes.id_user) AS likesAmount, hashtags.hashtag_title AS tags,
  (SELECT COUNT(*) FROM posts AS second_posts WHERE posts.id_post = second_posts.original_post_id) AS reposts_amount
  FROM posts
  LEFT JOIN likes ON posts.id_post = likes.id_post
  LEFT JOIN hashtags ON posts.id_post = hashtags.id_post
  WHERE posts.id_user = $profileId
  GROUP BY posts.id_post, hashtags.hashtag_title");

  if (isset($posts) && !empty($posts)) {

    foreach ($posts as $key => $value) {
      $idPost = $value['id_post'];

      if ($value['repost'] === '1') {
        $idUser = $value['id_author'];
        $AuthorInfo = doQuery($con, "SELECT avatar_link, user_login FROM users WHERE id_user = $idUser");
        $value['author_avatar_link'] = $AuthorInfo[0]['avatar_link'];
        $value['author_login'] = $AuthorInfo[0]['user_login'];
      }

      $comments = doQuery($con, "SELECT comments.*, users.user_login, users.avatar_link
      FROM comments
      JOIN users ON users.id_user = comments.id_user
      WHERE comments.id_post = $idPost");

      if (!empty($comments)) {

        if (count($comments) > 2 && !isset($_GET['comments'])) {
          $firstLength = count($comments);
          $comments = array_slice($comments, 0, 2);
          $moreCommentsExist = $firstLength - 2;
          $value['moreCommentsExist'] = $moreCommentsExist;
        }

        $value['comments'] = $comments;
      } else {
        $value['comments'] = '';
      }

      $finalPosts[] = $value;
    }
  }
} else if ($_GET['active'] === 'likes') {

  $likesAllInfo = doQuery($con, "SELECT posts.content_type, likes.id_user AS user_like, likes.likes_date, users.avatar_link, users.user_login, posts.id_post, posts.image_link, posts.video_link
  FROM posts
  JOIN likes ON posts.id_post = likes.id_post
  LEFT JOIN users ON users.id_user = likes.id_user
  WHERE posts.id_user = $profileId");
} else if ($_GET['active'] === 'subs') {

  $subs = doQuery($con, "SELECT users.id_user, users.avatar_link, users.user_login, users.registration_date, COUNT(posts.id_post) AS postsAmount,
  (SELECT COUNT(id_subscriber) FROM subscriptions WHERE id_receiver_sub = users.id_user) AS subsAmount,
  (SELECT COUNT(*) FROM subscriptions WHERE id_receiver_sub = users.id_user AND id_subscriber = $userId) AS amISub
  FROM subscriptions
  LEFT JOIN users ON users.id_user = subscriptions.id_receiver_sub
  LEFT JOIN posts ON posts.id_user = subscriptions.id_receiver_sub
  WHERE id_subscriber = $profileId
  GROUP BY users.id_user");
}

$page_content = include_template('profile-template.php', [
  'registrationDate' => $registrationDate,
  'amISubOnMainProfile' => $amISubOnMainProfile,
  'likes' => $likesAllInfo,
  'subs' => $subs,
  'postsByUser' => $finalPosts,
  'login' => $login,
  'profileAvatar' => getAvatarForUser($login),
  'subscriptionsAmount' => $subscriptionsAmount,
  'postsAmount' => $postsAmount,
  'isMyProfile' => $isMyProfile,
  'avatarFotCommentIcon' => getAvatarForUser($_SESSION['username'])
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: мой профиль', 'avatar' => getAvatarForUser($_SESSION['username'])]);
print($layout_content);
