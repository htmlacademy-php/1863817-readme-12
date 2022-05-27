<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();
$con = connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $comment = test_input($_POST['comment']);
  $postId = $_POST['id'];
  $commentError = validateLength($comment, 4, 100);

  if ($commentError) {
    $locationBack = 'Location: ' . $_SERVER['HTTP_REFERER'] . "&error=$commentError&value=$comment";
    header($locationBack);
  } else {
    $postExist = doQuery($con, "SELECT * FROM posts WHERE id_post = $postId");
    $postAuthorId = $postExist[0]['id_user'];

    if ($postExist) {
      $userId = $_SESSION['userId'];
      $insertComment = mysqli_query($con, "INSERT INTO comments (comment_date, comment_text, id_user, id_post) VALUE (NOW(), '$comment', $userId, $postId)");
      $locationProfile = 'Location: /profile.php?id=' . $postId . '&active=posts';
      header($locationProfile);
    }
  }
}
