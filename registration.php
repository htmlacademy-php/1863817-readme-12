<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $location = "Location: /registration.php?registration=1";

  foreach ($_POST as $key => $value) {
    test_input($value);
  }

  $errors['resultEmail'] = validateEmail($_POST["email"]);
  $errors['resultLogin'] = validateLogin($_POST["login"]);
  $errors['resultPassword'] = validatePassword($_POST["password"]);
  $errors['resultRepeatPassword'] = validateRepeatPassword($_POST["password"], $_POST["password-repeat"]);
  $errors['resultFile'] = validatePhotoFile($_FILES["userpic-file-photo"]);

  foreach ($errors as $key => $value) {
    if ($value) {
      $value = urlencode($value);
      $location .= "&$key=$value";
      $error = true;
    }
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
    if (!empty($_FILES["userpic-file-photo"]['tmp_name'])) {
      if (!empty($_POST["link-download-if-reload"])) {
        unlink($_POST["link-download-if-reload"]);
      }
      $file_name = $_FILES["userpic-file-photo"]['name'];
      $file_path = __DIR__ . '/uploads/';
      move_uploaded_file($_FILES["userpic-file-photo"]['tmp_name'], $file_path . $file_name);
      $photo = '/uploads/' . $_FILES["userpic-file-photo"]['name'];
    } else if (!empty($_POST["link-download-if-reload"])) {
      $photo = $_POST["link-download-if-reload"];
    }

    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    // $password = substr($password, 0, 60);
    $email = $_POST["email"];
    $login = $_POST["login"];
    $result = mysqli_query(connect(), "INSERT INTO users (registration_date, email, password, avatar_link, user_login) VALUE (NOW(), '$email', '$password', '$photo', '$login')");
    header("Location: /login.php");
  }
}

if ($_GET['registration'] === '1') {
  $page_content = include_template('reg-template.php');
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации']);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
