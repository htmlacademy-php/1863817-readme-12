<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();
$con =  connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
} else {
  $login = $_SESSION['username'];
  $userId = doQuery($con, "SELECT id_user FROM users WHERE user_login = '$login'");
  $userId = $userId[0]['id_user'];
}

if ($con == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  $rows_for_types = doQuery($con, "SELECT * FROM contentTypes");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['photo-heading'])) {
    // photo
    $location = "Location: /add.php?filter=3";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateLength($_POST["photo-heading"], 5, 20);
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


    if (strlen($location) > 27) {
      $error = true;
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $value = urlencode($value);
        $location .= "&$key=$value";
      }

      if (!empty($_FILES["userpic-file-photo"]['tmp_name']) && !$errors['resultFile']) {
        $file_name = getEndPath($_FILES["userpic-file-photo"]['name'], '.');
        $randomName = generateRandomFileName();
        $file_name = $randomName . '.' . $file_name;
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
        $photo = urlencode('uploads/' . $file_name);
      } else if (!empty($_POST["link-download-if-reload"])) {
        $photo = $_POST["link-download-if-reload"];
      }

      if (!empty($_POST["link-download-if-reload"]) || !empty($_FILES["userpic-file-photo"]['tmp_name'])) {
        $location .= "&photo=$photo";
      }

      header($location);
    } else {

      if ($_POST["photo-link"] && empty($_FILES["userpic-file-photo"]['tmp_name']) && empty($_POST["link-download-if-reload"])) {
        downloadPhotoFromWebLink($_POST["photo-link"]);
        $photo = '/uploads/' . getEndPath($_POST["photo-link"], '/');
      } else if ($_FILES["userpic-file-photo"]['tmp_name']) {
        if (!empty($_POST["link-download-if-reload"])) {
          unlink($_POST["link-download-if-reload"]);
        }
        $file_name = $_FILES["userpic-file-photo"]['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
        $photo = '/uploads/' . $_FILES["userpic-file-photo"]['name'];
      } else {
        $photo = $_POST["link-download-if-reload"];
      }

      $heading = $_POST["photo-heading"];
      $id = transactionForAddPosts($con, $_POST["photo-tags"], "INSERT INTO posts (post_date, title, content_type, image_link, id_user) VALUE (NOW(), '$heading', 'post-photo', '$photo', $userId)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        header("Location: /post.php?post-id=$id");
      }
    }
  }

  if (isset($_POST['video-heading'])) {
    // video
    $location = "Location: /add.php?filter=5";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateLength($_POST["video-heading"], 5, 20);
    $errors['resultLink'] = validateVideo($_POST["video-link"]);
    $errors['resultTags'] = validateTags($_POST["video-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $value = urlencode($value);
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $linkVideo = $_POST["video-link"];
      $heading = $_POST["video-heading"];
      $id = transactionForAddPosts($con, $_POST["video-tags"], "INSERT INTO posts (post_date, title, content_type, video_link, id_user) VALUE (NOW(), '$heading', 'post-video', '$linkVideo', $userId)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        header("Location: /post.php?post-id=$id");
      }
    }
  }

  if (isset($_POST['text-heading'])) {
    // text
    $location = "Location: /add.php?filter=2";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateLength($_POST["text-heading"], 5, 20);
    $errors['resultText'] = validateLength($_POST["text-text"], 30, 600);
    $errors['resultTags'] = validateTags($_POST["text-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $value = urlencode($value);
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $heading = $_POST["text-heading"];
      $text = $_POST["text-text"];
      $id = transactionForAddPosts($con, $_POST["text-tags"], "INSERT INTO posts (post_date, title, content_type, text_content, id_user) VALUE (NOW(), '$heading', 'post-text', '$text', $userId)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        header("Location: /post.php?post-id=$id");
      }
    }
  }

  if (isset($_POST['quote-heading'])) {
    // quote
    $location = "Location: /add.php?filter=1";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateLength($_POST["quote-heading"], 5, 20);
    $errors['resultText'] = validateLength($_POST["quote-text"], 30, 100);
    $errors['resultAuthor'] = validateLength($_POST["quote-author"], 5, 20);
    $errors['resultTags'] = validateTags($_POST["quote-tags"]);

    foreach ($errors as $key => $value) {
      if ($value) {
        $location .= "&$key=$value";
        $error = true;
      }
    }

    if ($error) {
      foreach ($_POST as $key => $value) {
        $value = urlencode($value);
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $heading = $_POST["quote-heading"];
      $text = $_POST["quote-text"];
      $author = $_POST["quote-author"];
      $id = transactionForAddPosts($con, $_POST["quote-tags"], "INSERT INTO posts (post_date, title, content_type, text_content, quote_author, id_user) VALUE (NOW(), '$heading', 'post-quote', '$text', '$author', $userId)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        header("Location: /post.php?post-id=$id");
      }
    }
  }

  if (isset($_POST['link-heading'])) {
    // link
    $location = "Location: /add.php?filter=4";

    foreach ($_POST as $key => $value) {
      test_input($value);
    }

    $errors['resultHeading'] = validateLength($_POST["link-heading"], 5, 20);
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
        $value = urlencode($value);
        $location .= "&$key=$value";
      }

      header($location);
    } else {
      $link = $_POST["link-link"];
      $heading = $_POST["link-heading"];
      $id = transactionForAddPosts($con, $_POST["link-tags"], "INSERT INTO posts (post_date, title, content_type, website_link, id_user) VALUE (NOW(), '$heading', 'post-link', '$link', $userId)");
      if ($id === 'error') {
        print('При отправке данных на сервер произошла ошибка, попробуйте перезагрузить страницу и повторите попытку');
        header($location);
      } else {
        header("Location: /post.php?post-id=$id");
      }
    }
  }
}

if (!empty($_GET['filter'])) {
  $page_content = include_template('adding-post.php', ['types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации', 'avatar' => getAvatarForUser($_SESSION['username'])]);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
