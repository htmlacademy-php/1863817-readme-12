<?php
if (isset($_POST['text-heading'])) {
  // text

  $heading = test_input($_POST["text-heading"]);
  $text = test_input($_POST["text-text"]);
  $tags = test_input($_POST["text-tags"]);

  $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

  if ($resultHeading) {
    $errors = true;
  }

  $resultText = validateHeadingTextAndAuthor($text, 30, 600);

  if ($resultText) {
    $errors = true;
  }

  $resultTags = validateTags($tags);

  if ($resultTags) {
    $errors = true;
  }

  if ($errors) {
    if ($resultHeading) {
      $location .= "&headingError=$resultHeading";
    }

    if ($resultText) {
      $location .= "&textError=$resultText";
    }

    if ($resultTags) {
      $location .= "&tagsError=$resultTags";
    }

    if (!empty($heading)) {
      $heading = urlencode($heading);
      $location .= "&heading=$heading";
    }

    if (!empty($text)) {
      $text = urlencode($text);
      $location .= "&text=$text";
    }

    if (!empty($tags)) {
      $tags = urlencode($tags);
      $location .= "&tags=$tags";
    }

    echo('<pre>');
    print_r($errors);
    echo('</pre>');

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
