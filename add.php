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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['photo-heading'])) {
    // video
    $location = "Location: /add.php?filter=3";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["photo-heading"], 5, 20);
    $errors['resultFile'] = validatePhotoFile($_FILES["userpic-file-photo"]);
    $errors['resultLink'] = validatePhotoLink($_POST["photo-link"]);
    $errors['resultTags'] = validateTags($_POST["photo-tags"]);

    if ($errors['resultHeading']) {
      $value = $errors['resultHeading'];
      $location .= "&resultHeading=$value";
    }

    if ($errors['resultTags']) {
      $value = $errors['resultTags'];
      $location .= "&resultTags=$value";
    }

    if (empty($_FILES["userpic-file-photo"]['tmp_name']) && empty($_POST["photo-link"]) && empty($_POST["link-download-if-reload"])) {
      $textError = 'Хотя бы одно из полей с указанием фотографии должно быть заполненно';
      $location .= "&resultLink=$textError";
    }

    if ($errors['resultLink'] && !$errors['resultFile'] && empty($_POST["link-download-if-reload"])) {
      $value = $errors['resultLink'];
      $location .= "&resultLink=$value";
    }

    if ($errors['resultFile'] && !$errors['resultLink']) {
      $value = $errors['resultFile'];
      $location .= "&resultFile=$value";
    }


    if (strlen($location) > 27) {
      $error = true;
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=$value";
      }

      if (!empty($_FILES["userpic-file-photo"]['tmp_name'])) {
        $file_name = getEndPath($_FILES["userpic-file-photo"]['name'], '.');
        $randomName = generateRandomFileName();
        $file_name = $randomName . '.' . $file_name;
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
        $photo = urlencode('uploads/' . $file_name);
      }

      if (!empty($_POST["link-download-if-reload"])) {
        $photo = $photoPathForPageReload;
      }

      if (!empty($_POST["link-download-if-reload"]) || !empty($_FILES["userpic-file-photo"]['tmp_name'])) {
        $location .= "&photo=$photo";
      }

      header($location);
    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, video_link) VALUE (NOW(), '$heading', 'post-video', '$linkVideo')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, image_link, id_user) VALUE (NOW(), '$heading', 'post-photo', '$photo', 1)");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  // if (isset($_POST['photo-heading'])) {
  //   // photo

  //   $heading = test_input($_POST["photo-heading"]);
  //   $userpicFilePhoto = $_FILES["userpic-file-photo"];
  //   $linkPhoto = test_input($_POST["photo-link"]);
  //   $photoPathForPageReload = test_input($_POST["link-download-if-reload"]);
  //   $tags = test_input($_POST["photo-tags"]);

  //   $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

  //   if ($resultHeading) {
  //     $errors = $resultHeading . 'heading, ';
  //   }

  //   $resultPhoto = validateFileInputAndPhotoLink($userpicFilePhoto, $linkPhoto);
  //   $flagPhoto = gettype($resultPhoto);

  //   if ($flagPhoto === 'string' || $resultPhoto = 3) {
  //     if (!empty($photoPathForPageReload)) {
  //       $resultPhoto = 4;
  //     } else {
  //       $errors .= $resultPhoto;
  //     }
  //   }

  //   $resultTags = validateTags($tags);

  //   if ($resultTags) {
  //     $errors .= $resultTags . 'tags';
  //   }

  //   if (!empty($errors)) {
  //     $location .= "3&errors=$errors";

  //     if (!empty($heading)) {
  //       $heading = urlencode($heading);
  //       $location .= "&heading=$heading";
  //     }

  //     if (!empty($linkPhoto)) {
  //       $link = urlencode($linkPhoto);
  //       $location .= "&link=$link";
  //     }

  //     if (!empty($tags)) {
  //       $tags = urlencode($tags);
  //       $location .= "&tags=$tags";
  //     }

  //     if (!empty($userpicFilePhoto['tmp_name'])) {
  //       $file_name = getEndPath($userpicFilePhoto['name'], '.');
  //       $randomName = generateRandomFileName();
  //       $file_name = $randomName . '.' . $file_name;
  //       $file_path = __DIR__ . '/uploads/';
  //       move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
  //       $photo = urlencode('uploads/' . $file_name);
  //       $location .= "&photo=$photo";
  //     } else {
  //       if (!empty($photoPathForPageReload)) {
  //         $photo = $photoPathForPageReload;
  //         $location .= "&photo=$photo";
  //       }
  //     }

  //     header($location);
  //   } else {
  //     if ($resultPhoto === 3) {
  //       downloadPhotoFromWebLink($linkPhoto);
  //       $photo = '/uploads/' . getEndPath($linkPhoto, '/');
  //     } else if ($resultPhoto === 2) {
  //       if (!empty($photoPathForPageReload)) {
  //         unlink($photoPathForPageReload);
  //       }
  //       $file_name = $userpicFilePhoto['name'];
  //       $file_path = __DIR__ . '/uploads/';
  //       move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
  //       $photo = '/uploads/' . $userpicFilePhoto['name'];
  //     } else if ($resultPhoto === 4) {
  //       $photo = $photoPathForPageReload;
  //     }

  //     $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, image_link, id_user) VALUE (NOW(), '$heading', 'post-photo', '$photo', 1)");
  //     $id = mysqli_insert_id($con);
  //     if (!empty($tags)) {
  //       $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
  //     }
  //     header("Location: /post.php?post-id=$id");
  //   }
  // }

  if (isset($_POST['video-heading'])) {
    // video
    $location = "Location: /add.php?filter=5";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["video-heading"], 5, 20);
    $errors['resultLink'] = validateVideo($_POST["video-link"], 30, 600);
    $errors['resultTags'] = validateTags($_POST["video-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=$value";
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
    $location = "Location: /add.php?filter=2";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["text-heading"], 5, 20);
    $errors['resultText'] = validateHeadingTextAndAuthor($_POST["text-text"], 30, 600);
    $errors['resultTags'] = validateTags($_POST["text-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content, id_user) VALUE (NOW(), '$heading', 'post-text', '$text', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  if (isset($_POST['quote-heading'])) {
    // text
    $location = "Location: /add.php?filter=1";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["quote-heading"], 5, 20);
    $errors['resultText'] = validateHeadingTextAndAuthor($_POST["quote-text"], 30, 600);
    $errors['resultAuthor'] = validateHeadingTextAndAuthor($_POST["quote-author"], 5, 20);
    $errors['resultTags'] = validateTags($_POST["quote-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content, quote_author, id_user) VALUE (NOW(), '$heading', 'post-quote', '$text', '$author', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /post.php?post-id=$id");
    }
  }

  if (isset($_POST['link-heading'])) {
    // link
    $location = "Location: /add.php?filter=4";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateHeadingTextAndAuthor($_POST["link-heading"], 5, 20);
    $errors['resultText'] = validateWebLink($_POST["link-link"], 30, 600);
    $errors['resultTags'] = validateTags($_POST["link-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=$value";
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
