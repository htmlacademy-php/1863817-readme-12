<?php
if (isset($_POST['text-heading'])) {
  // text
  $location = "Location: /add.php?filter=2";

  foreach($_POST as $key => $value) {
    test_input($value);
  }

  $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["text-heading"], 5, 20);
  $errors['resultText'] = validateHeadingTextAndAuthor($_POST["text-text"], 30, 600);
  $errors['resultTags'] = validateTags($_POST["text-tags"]);

  foreach($errors as $key => $value) {
    if ($value) {
      $location .= "&$key=$value";
      $error = true;
    }
  }

  if ($error) {
    foreach($_POST as $key => $value) {
      $location .= "&$key=$value";
    }

    header($location);
  } else {
    $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content) VALUE (NOW(), '$heading', 'post-text', '$text')");
    $id = mysqli_insert_id($con);
    if (!empty($tags)) {
      $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
    }
    header("Location: /post.php?post-id=$id");
  }
}
?>
