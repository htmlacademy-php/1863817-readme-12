<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

$con =  connect();

if ($con == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  $rows_for_types = doQuery($con, "SELECT * FROM contentTypes");
}

function getEndPath ($fullpath, $symbol)
{
  $url = $fullpath;
  $stringToArray = explode($symbol, $url);
  $lastElement = count($stringToArray) - 1;
  return $endPath = $stringToArray[$lastElement];
}

function downloadPhotoFromWebLink ($link)
{
  $endPath = getEndPath($link, '/');
  $file_path = __DIR__ . '/uploads/';
  $pathLink = $file_path . $endPath;
  file_put_contents($pathLink, file_get_contents($link));
}

function generateRandomFileName ()
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  return $randomName = substr(str_shuffle($permitted_chars), 0, 10);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $location = 'Location: /add.php?filter=';

  if (isset($_POST['photo-heading'])) {
    // photo

    $heading = test_input($_POST["photo-heading"]);
    $userpicFilePhoto = $_FILES["userpic-file-photo"];
    $linkPhoto = test_input($_POST["photo-link"]);
    $photoPathForPageReload = test_input($_POST["link-download-if-reload"]);
    $tags = test_input($_POST["photo-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors = $resultHeading . 'heading, ';
    }

    if (!empty($userpicFilePhoto['tmp_name']) || !empty($linkPhoto)) {
      $resultPhoto = validateFileInputAndPhotoLink($userpicFilePhoto, $linkPhoto);
    } else {
      $resultPhoto = 4;
    }

    $flagPhoto = gettype($resultPhoto);

    if ($flagPhoto === 'string') {
      $errors .= $resultPhoto;
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors .= $resultTags . 'tags';
    }

    if (!empty($errors)) {

      print_r($errors);

      $location .= '3&errors=$errors';

      if (!empty($heading)) {
        $heading = urlencode($heading);
        print_r($heading);
        $location .= '&heading=$heading';
      }

      if (!empty($linkPhoto)) {
        $link = urlencode($linkPhoto);
        $location .= '&link=$link';
      }

      if (!empty($tags)) {
        $tags = urlencode($tags);
        $location .= '&tags=$tags';
      }

      if (!empty($userpicFilePhoto['tmp_name'])) {
        $file_name = getEndPath($userpicFilePhoto['name'], '.');
        $randomName = generateRandomFileName();
        $file_name = $randomName . '.' . $file_name;
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
        $photo = urlencode('uploads/' . $file_name);
        $location .= '&photo=$photo';
      }

      // header($location);

    } else {
      if ($resultPhoto === 3) {
        downloadPhotoFromWebLink($linkPhoto);
        $photo = '/uploads/' . getEndPath($linkPhoto, '/');
      } else if ($resultPhoto === 2) {
        if (!empty($photoPathForPageReload)) {
          unlink($photoPathForPageReload);
        }
        $file_name = $userpicFilePhoto['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
        $photo = '/uploads/' . $userpicFilePhoto['name'];
      } else if ($resultPhoto === 4) {
        $photo = $photoPathForPageReload;
      }

      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, image_link, id_user) VALUE (NOW(), '$heading', 'post-photo', '$photo', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  if (isset($_POST['video-heading'])) {
    // video

    $heading = test_input($_POST["video-heading"]);
    $link = test_input($_POST["video-link"]);
    $tags = test_input($_POST["video-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors = $resultHeading . 'heading, ';
    }

    $resultVideo = validateVideo($link);

    if ($resultVideo) {
      $errors .= $resultVideo . 'link, ';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors .= $resultTags . 'tags';
    }

    if (!empty($errors)) {

      $location .= '5&errors=$errors';

      if (!empty($heading)) {
        $heading = urlencode($heading);
        $location .= '&heading=$heading';
      }

      if (!empty($link)) {
        $link = urlencode($link);
        $location .= '&link=$link';
      }

      if (!empty($tags)) {
        $tags = urlencode($tags);
        $location .= '&tags=$tags';
      }

      header($location);

    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, video_link) VALUE (NOW(), '$heading', 'post-video', '$linkVideo')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  if (isset($_POST['text-heading'])) {
    // text

    $heading = test_input($_POST["text-heading"]);
    $text = test_input($_POST["text-text"]);
    $tags = test_input($_POST["text-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors = $resultHeading . 'heading, ';
    }

    $resultText = validateHeadingTextAndAuthor($text, 30, 600);

    if ($resultText) {
      $errors .= $resultText . 'text, ';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors .= $resultTags . 'tags';
    }

    if (!empty($errors)) {

      $location .= '2&errors=$errors';

      if (!empty($heading)) {
        $heading = urlencode($heading);
        $location .= '&heading=$heading';
      }

      if (!empty($text)) {
        $text = urlencode($text);
        $location .= '&text=$text';
      }

      if (!empty($tags)) {
        $tags = urlencode($tags);
        $location .= '&tags=$tags';
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

  if (isset($_POST['quote-heading'])) {
    // quote

    $heading = test_input($_POST["quote-heading"]);
    $text = test_input($_POST["quote-text"]);
    $author = test_input($_POST["quote-author"]);
    $tags = test_input($_POST["quote-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors = $resultHeading . 'heading, ';
    }

    $resultText = validateHeadingTextAndAuthor($textm, 30, 600);

    if ($resultText) {
      $errors .= $resultText . 'text, ';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors .= $resultTags . 'tags, ';
    }

    $resultAuthor = validateHeadingTextAndAuthor($author, 5, 25);

    if ($resultAuthor) {
      $errors .= $resultAuthor . 'author';
    }

    if (!empty($errors)) {

      $location .= '1&errors=$errors';

      if (!empty($heading)) {
        $heading = urlencode($heading);
        $location .= '&heading=$heading';
      }

      if (!empty($text)) {
        $text = urlencode($text);
        $location .= '&text=$text';
      }

      if (!empty($tags)) {
        $tags = urlencode($tags);
        $location .= '&tags=$tags';
      }

      if (!empty($author)) {
        $author = urlencode($author);
        $location .= '&author=$author';
      }

      header($location);

    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content, quote_author) VALUE (NOW(), '$heading', 'post-quote', '$text', '$author')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  if (isset($_POST['link-heading'])) {
    // link

    $heading = test_input($_POST["link-heading"]);
    $link = test_input($_POST["link-link"]);
    $tags = test_input($_POST["link-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors = $resultHeading . 'heading, ';
    }

    $resultLink = validateWebLink($link);

    if ($resultLink) {
      $errors .= $resultLink . 'link, ';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors .= $resultTags . 'tags';
    }

    if (!empty($errors)) {

      $location .= '4&errors=$errors';

      if (!empty($heading)) {
        $heading = urlencode($heading);
        $location .= '&heading=$heading';
      }

      if (!empty($link)) {
        $link = urlencode($link);
        $location .= '&link=$link';
      }

      if (!empty($tags)) {
        $tags = urlencode($tags);
        $location .= '&tags=$tags';
      }

      header($location);

    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, website_link, id_user) VALUE (NOW(), '$heading', 'post-link', '$link', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }
}

if (!empty($_GET['filter'])) {
  $page_content = include_template('adding-post.php', ['types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации']);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
?>
