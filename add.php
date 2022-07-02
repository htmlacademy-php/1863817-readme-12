<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();
isSessionExist();
$con =  connect();

$login = $_SESSION['username'];
$userId = $_SESSION['userId'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  if (isset($_POST['photo-heading'])) {
    // photo
    $location = "Location: /add.php?filter=photo";

    foreach ($_POST as $key => $value) {
      $valuesAfterTest[$key] = test_input($con, $value);
    }

    $errors = [
      'resultHeading' => validateLength($valuesAfterTest["photo-heading"], 5, 20),
      'resultTags' => validateTags($valuesAfterTest["photo-tags"]),
      'resultFile' => validatePhotoFile($_FILES["userpic-file-photo"]),
      'resultLink' => validatePhotoLink($valuesAfterTest["photo-link"])
    ];

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

    if ($errors['resultLink'] && empty($_FILES["userpic-file-photo"]['tmp_name']) && !$errors['resultFile'] && empty($_POST["link-download-if-reload"])) {
      $value = $errors['resultLink'];
      $location .= "&resultLink=$value";
    }

    if ($errors['resultFile']) {
      $value = $errors['resultFile'];
      $location .= "&resultFile=$value";
    }

    if ($errors['resultFile'] && $errors['resultLink']) {
      $textError = ', Адрес ресурса также введен некорректно';
      $value = $errors['resultFile'] . $textError;
      $location .= "&resultLink=$value";
    }


    if (iconv_strlen($location) > 31) {
      $error = true;
    }

    if (isset($error) && $error === true) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=" . urlencode($value);
      }

      if (!empty($_FILES["userpic-file-photo"]['tmp_name']) && !$errors['resultFile']) {
        $file_name = getEndPath($_FILES["userpic-file-photo"]['name'], '.');
        $randomName = generateRandomFileName();
        $file_name = $randomName . '.' . $file_name;
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
        $photo = urlencode('uploads/' . $file_name);
      } else if (!empty($valuesAfterTest["link-download-if-reload"])) {
        $photo = $valuesAfterTest["link-download-if-reload"];
      }

      if (!empty($_POST["link-download-if-reload"]) || !empty($_FILES["userpic-file-photo"]['tmp_name'])) {
        $location .= "&photo=$photo";
      }

      header($location);
    } else {

      if ($_POST["photo-link"] && empty($_FILES["userpic-file-photo"]['tmp_name']) && empty($_POST["link-download-if-reload"])) {
        downloadPhotoFromWebLink($valuesAfterTest["photo-link"]);
        $photo = '/uploads/' . getEndPath($valuesAfterTest["photo-link"], '/');
      } else if ($_FILES["userpic-file-photo"]['tmp_name']) {
        if (!empty($_POST["link-download-if-reload"])) {
          unlink($_POST["link-download-if-reload"]);
        }
        $file_name = $_FILES["userpic-file-photo"]['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
        $photo = '/uploads/' . $_FILES["userpic-file-photo"]['name'];
      } else {
        $photo = $valuesAfterTest["link-download-if-reload"];
      }

      $heading = $valuesAfterTest["photo-heading"];
      $id = transactionForAddPosts($con, $valuesAfterTest["photo-tags"], "INSERT INTO posts (post_date, title, content_type, image_link, id_user, number_of_views) VALUE (NOW(), '$heading', 'post-photo', '$photo', $userId, 0)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        $result = 'ok';
      }
    }
  }

  if (isset($_POST['video-heading'])) {
    // video
    $location = "Location: /add.php?filter=video";

    foreach ($_POST as $key => $value) {
      $valuesAfterTest[$key] = test_input($con, $value);
    }

    $errors = [
      'resultHeading' => validateLength($valuesAfterTest["video-heading"], 5, 20),
      'resultLink' => validateVideo($valuesAfterTest["video-link"]),
      'resultTags' => validateTags($valuesAfterTest["video-tags"])
    ];

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if (isset($error) && $error === true) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=" . urlencode($value);
      }

      header($location);
    } else {
      $linkVideo = $valuesAfterTest["video-link"];
      $heading = $valuesAfterTest["video-heading"];
      $id = transactionForAddPosts($con, $valuesAfterTest["video-tags"], "INSERT INTO posts (post_date, title, content_type, video_link, id_user, number_of_views) VALUE (NOW(), '$heading', 'post-video', '$linkVideo', $userId, 0)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        $result = 'ok';
      }
    }
  }

  if (isset($_POST['text-heading'])) {
    // text
    $location = "Location: /add.php?filter=text";

    foreach ($_POST as $key => $value) {
      $valuesAfterTest[$key] = test_input($con, $value);
    }

    $errors = [
      'resultHeading' => validateLength($valuesAfterTest["text-heading"], 5, 20),
      'resultText' => validateLength($valuesAfterTest["text-text"], 30, 600),
      'resultTags' => validateTags($valuesAfterTest["text-tags"])
    ];

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if (isset($error) && $error === true) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=" . urlencode($value);
      }

      header($location);
    } else {
      $heading = $valuesAfterTest["text-heading"];
      $text = $valuesAfterTest["text-text"];
      $id = transactionForAddPosts($con, $valuesAfterTest["text-tags"], "INSERT INTO posts (post_date, title, content_type, text_content, id_user, number_of_views) VALUE (NOW(), '$heading', 'post-text', '$text', $userId, 0)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        $result = 'ok';
      }
    }
  }

  if (isset($_POST['quote-heading'])) {
    // quote
    $location = "Location: /add.php?filter=quote";

    foreach ($_POST as $key => $value) {
      $valuesAfterTest[$key] = test_input($con, $value);
    }

    $errors = [
      'resultHeading' => validateLength($valuesAfterTest["quote-heading"], 5, 20),
      'resultText' => validateLength($valuesAfterTest["quote-text"], 30, 100),
      'resultAuthor' => validateLength($valuesAfterTest["quote-author"], 5, 20),
      'resultTags' => validateTags($valuesAfterTest["quote-tags"])
    ];

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if (isset($error) && $error === true) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=" . urlencode($value);
      }

      header($location);
    } else {
      $heading = $valuesAfterTest["quote-heading"];
      $text = $valuesAfterTest["quote-text"];
      $author = $valuesAfterTest["quote-author"];
      $id = transactionForAddPosts($con, $valuesAfterTest["quote-tags"], "INSERT INTO posts (post_date, title, content_type, text_content, quote_author, id_user, number_of_views) VALUE (NOW(), '$heading', 'post-quote', '$text', '$author', $userId, 0)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        $result = 'ok';
      }
    }
  }

  if (isset($_POST['link-heading'])) {
    // link
    $location = "Location: /add.php?filter=link";

    foreach ($_POST as $key => $value) {
      $valuesAfterTest[$key] = test_input($con, $value);
    }

    $errors = [
      'resultHeading' => validateLength($valuesAfterTest["link-heading"], 5, 20),
      'resultLink' => validateWebLink($valuesAfterTest["link-link"]),
      'resultTags' => validateTags($valuesAfterTest["link-tags"]),
    ];

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if (isset($error) && $error === true) {
      foreach ($_POST as $key => $value) {
        $location .= "&$key=" . urlencode($value);
      }

      header($location);
    } else {
      $link = $valuesAfterTest["link-link"];
      $heading = $valuesAfterTest["link-heading"];
      $id = transactionForAddPosts($con, $valuesAfterTest["link-tags"], "INSERT INTO posts (post_date, title, content_type, website_link, id_user, number_of_views) VALUE (NOW(), '$heading', 'post-link', '$link', $userId, 0)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        $result = 'ok';
      }
    }
  }
}

if (isset($result) && $result === 'ok') {
  $subs = doQuery($con, "SELECT subscriptions.id_subscriber, users.*
  FROM subscriptions
  JOIN users ON users.id_user = subscriptions.id_subscriber
  WHERE subscriptions.id_receiver_sub = $userId");
  $title = 'Новая публикация от пользователя ' . $login;
  foreach ($subs as $key => $value) {
    $body = 'Здравствуйте, ' . $value['user_login'] . '. Пользователь ' . $login . ' только что опубликовал новую запись „' . $heading . '“. Посмотрите её на странице пользователя: http://readme/profile.php?id=' . $userId . '&active=posts';
    sendEmail($value['email'], $title, $body);
  }
  header("Location: /post.php?post-id=$id");
}

if (!empty($_GET['filter'])) {
  $page_content = include_template('adding-post.php');
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации', 'avatar' => getAvatarForUser($_SESSION['username'])]);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
