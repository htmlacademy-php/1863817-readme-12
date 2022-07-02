<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();
isSessionExist();
$con =  connect();

$userId = $_SESSION['userId'];
$idPost = test_input($con, $_GET['post-id']);
$updateViews = mysqli_query($con, "UPDATE posts SET number_of_views = number_of_views + 1 WHERE id_post = $idPost");

$card = doQuery($con, "SELECT posts.*, users.registration_date, users.user_login, users.avatar_link, hashtags.hashtag_title, COUNT(likes.id_post) AS likes_amount,
(SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id_post AND likes.id_user = $userId) AS amILikeThisPost,
(SELECT COUNT(*) FROM comments WHERE comments.id_post = posts.id_post) AS comments_amount,
(SELECT COUNT(id_subscriber) FROM subscriptions WHERE id_receiver_sub = users.id_user) AS subs_amount,
(SELECT COUNT(*) FROM subscriptions WHERE id_receiver_sub = users.id_user AND id_subscriber = $userId) AS amISub,
(SELECT COUNT(*) FROM posts AS second_posts WHERE posts.id_post = second_posts.original_post_id) AS reposts_amount
FROM posts
LEFT JOIN likes ON likes.id_post = posts.id_post
LEFT JOIN hashtags ON posts.id_post = hashtags.id_post
JOIN users ON posts.id_user = users.id_user
WHERE posts.id_post = $idPost
GROUP BY posts.id_post, users.id_user, hashtags.hashtag_title");

if (isset($card[0])) {
  $postAuthor = $card[0]['id_user'];
  $postsAmount = doQuery($con, "SELECT COUNT(*) AS posts_amount FROM posts WHERE id_user = $postAuthor");
  $dataForTemplate['card'] = $card[0];
  $dataForTemplate['postsAmount'] = $postsAmount[0]['posts_amount'];

  $comments = doQuery($con, "SELECT comments.*, users.user_login, users.avatar_link
  FROM comments
  LEFT JOIN users ON users.id_user = comments.id_user
  WHERE id_post = $idPost");

  if (isset($comments) && !empty($comments)) {
    $dataForTemplate['comments'] = $comments;
    if (count($comments) > 2 && !isset($_GET['comments'])) {
      $firstLength = count($comments);
      $comments = array_slice($comments, 0, 2);
      $moreCommentsExist = $firstLength - 2;
      $dataForTemplate['moreCommentsExist'] = $moreCommentsExist;
    }
  }

  if ($postAuthor === $userId) {
    $dataForTemplate['isMyProfile'] = true;
  } else {
    $dataForTemplate['isMyProfile'] = false;
  }
}

if (isset($dataForTemplate)) {
  $dataForTemplate['avatar'] = getAvatarForUser($_SESSION['username']);
  $page_content = include_template('post-details.php', $dataForTemplate);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: публикация', 'avatar' => getAvatarForUser($_SESSION['username'])]);
} else {
  $layout_content = include_template('layout.php', ['content' => 'ERROR 404', 'title' => 'readme: публикация', 'avatar' => getAvatarForUser($_SESSION['username'])]);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
