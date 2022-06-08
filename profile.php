<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}


$profileId = $_GET['id'];
$userId = $_SESSION['userId'];
$userInfo = doQuery($con, "SELECT user_login, registration_date FROM users WHERE id_user = $profileId");
$login = $userInfo[0]['user_login'];
$registrationDate = $userInfo[0]['registration_date'];

$subscriptions = doQuery($con, "SELECT * FROM subscriptions WHERE id_receiver_sub = $profileId");
$subscriptionsAmount = $subscriptions ? count($subscriptions) : 0;


$posts = doQuery($con, "SELECT * FROM posts WHERE id_user = $profileId");

if ($login === $_SESSION['username']) {
  $isMyProfile = true;
} else {
  $isMyProfile = false;
}

if (isset($posts) && !empty($posts)) {

  foreach ($posts as $key => $post) {
    $idPost = $post['id_post'];
    $likes = doQuery($con, "SELECT * from likes where id_post = $idPost");
    if (isset($likes)) {
      foreach ($likes as $key => $like) {
        $allLikes[] = $like;
      }
    }
    $post['likesAmount'] = count($likes);
    $postsWithLikesCountInfo[] = $post;
  }

  foreach ($postsWithLikesCountInfo as $key => $value) {
    $idPost = $value['id_post'];
    $tagsForPost = doQuery($con, "SELECT hashtag_title from hashtags where id_post = $idPost");
    $value['tags'] = $tagsForPost[0]['hashtag_title'];
    $postsWithLikesCountInfoAndTags[] = $value;
  }

  foreach ($postsWithLikesCountInfoAndTags as $key => $value) {
    $idPost = $value['id_post'];
    $comments = doQuery($con, "SELECT * FROM comments WHERE id_post = $idPost");

    if ($value['repost'] === '1') {
      $idUser = $value['id_author'];
      $AuthorInfo = doQuery($con, "SELECT avatar_link, user_login FROM users WHERE id_user = $idUser");
      $value['author_avatar_link'] = $AuthorInfo[0]['avatar_link'];
      $value['author_login'] = $AuthorInfo[0]['user_login'];
    }

    if (!empty($comments)) {
      foreach ($comments as $key => $comment) {
        $AuthorCommentId = $comment['id_user'];
        $author = doQuery($con, "SELECT user_login, avatar_link FROM users WHERE id_user = $AuthorCommentId");
        $comment['user_login'] = $author[0]['user_login'];
        $comment['avatar_link'] = $author[0]['avatar_link'];
        $commentsWithAllInfo[] = $comment;
      }

      if (count($commentsWithAllInfo) > 2 && !isset($_GET['comments'])) {
        $firstLength = count($commentsWithAllInfo);
        $commentsWithAllInfo = array_slice($commentsWithAllInfo, 0, 2);
        $moreCommentsExist = $firstLength - 2;
        $value['moreCommentsExist'] = $moreCommentsExist;
      }

      $value['comments'] = $commentsWithAllInfo;
    } else {
      $value['comments'] = '';
    }

    $finalPosts[] = $value;
  }
}

$postsAmount = $posts ? count($posts) : 0;

$subs = doQuery($con, "SELECT id_receiver_sub from subscriptions where id_subscriber = $profileId");

if (isset($subs) && !empty($subs)) {
  foreach ($subs as $key => $value) {
    $subId = $value['id_receiver_sub'];
    $subsLoginAndAvatar = doQuery($con, "SELECT user_login, avatar_link from users where id_user = $subId");
    $value['login'] = $subsLoginAndAvatar[0]['user_login'];
    $value['avatar_link'] = $subsLoginAndAvatar[0]['avatar_link'];
    $subsPostsCount = doQuery($con, "SELECT COUNT(id_post) from posts where id_user = $subId");
    $value['postsAmount'] = $subsPostsCount[0]['COUNT(id_post)'];
    $subsCount = doQuery($con, "SELECT COUNT(id_subscriber) from subscriptions where id_receiver_sub = $subId");
    $value['subsAmount'] = $subsCount[0]['COUNT(id_subscriber)'];
    $amISub = doQuery($con, "SELECT * from subscriptions where id_receiver_sub = $subId AND id_subscriber = $userId");
    if (isset($amISub) && !empty($amISub)) {
      $value['amISub'] = 1;
    } else {
      $value['amISub'] = 0;
    }
    $subsWithAllInfo[] = $value;
  }
}

if (isset($allLikes) && !empty($allLikes)) {
  foreach ($allLikes as $key => $value) {
    $userId = $value['id_user'];
    $postId = $value['id_post'];
    $infoWholiked = doQuery($con, "SELECT user_login, avatar_link from users where id_user = $userId");
    $value['user_login'] = $infoWholiked[0]['user_login'];
    $value['avatar_link'] = $infoWholiked[0]['avatar_link'];
    $infoPostReceiverLike = doQuery($con, "SELECT content_type, video_link, image_link from posts where id_post = $postId");
    $value['content_type'] = $infoPostReceiverLike[0]['content_type'];
    $value['video_link'] = $infoPostReceiverLike[0]['video_link'];
    $value['image_link'] = $infoPostReceiverLike[0]['image_link'];
    $likesAllInfo[] = $value;
  }
}

$userId = $_SESSION['userId'];

$amISubOnMainProfile = doQuery($con, "SELECT * from subscriptions where id_receiver_sub = $profileId AND id_subscriber = $userId");

if (isset($amISubOnMainProfile) && !empty($amISubOnMainProfile)) {
  $amISubOnMainProfile = 1;
} else {
  $amISubOnMainProfile = 0;
}

$page_content = include_template('profile-template.php', [
  'registrationDate' => $registrationDate,
  'amISubOnMainProfile' => $amISubOnMainProfile,
  'likes' => $likesAllInfo,
  'subs' => $subsWithAllInfo,
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
