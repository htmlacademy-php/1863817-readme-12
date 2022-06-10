<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$userId = $_SESSION['userId'];
$idPost = $_GET['id_post'];
$post = doQuery($con, "SELECT * FROM posts WHERE id_post = $idPost");

if (isset($post) && !empty($post)) {
  $idAuthor = $post[0]['id_user'];
  $textContent = $post[0]['text_content'];
  $title = $post[0]['title'];
  $quoteAuthor = $post[0]['quote_author'];
  $imageLink = $post[0]['image_link'];
  $videoLink = $post[0]['video_link'];
  $websiteLink = $post[0]['website_link'];
  $contentType = $post[0]['content_type'];
  mysqli_query($con, "INSERT INTO posts
  (text_content, title, quote_author, image_link, video_link, website_link, content_type, id_user, post_date, number_of_views, repost, id_author)
  VALUE ('$textContent', '$title', '$quoteAuthor', '$imageLink', '$videoLink', '$websiteLink', '$contentType', $userId, NOW(), 0, 1, $idAuthor)");
}
