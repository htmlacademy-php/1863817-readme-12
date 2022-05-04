<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $location = "Location: /registration.php?registration=1";

  $email = test_input($_POST["email"]);
  $login = test_input($_POST["login"]);
  $password = test_input($_POST["password"]);
  $passwordRepeat = test_input($_POST["password-repeat"]);
  $userpicFilePhoto = $_FILES["userpic-file-photo"];
  $photoPathForPageReload = test_input($_POST["link-download-if-reload"]);

  $resultEmail = validateEmail($email);

  if ($resultEmail) {
    $errors = $resultEmail . 'email join';
  }

  $resultLogin = validateLogin($login);

  if ($resultLogin) {
    $errors .= $resultLogin . 'login join';
  }

  $resultPassword = validatePassword($password);

  if ($resultPassword) {
    $errors .= $resultPassword . 'password join';
  }

  $resultRepeatPassword = validateRepeatPassword($password, $passwordRepeat);

  if ($resultRepeatPassword) {
    $errors .= $resultRepeatPassword . 'repeat join';
  }

  $resultPhoto = validatePhotoForRegistration($userpicFilePhoto);

  if ($resultPhoto) {
    $errors .= $resultPhoto . 'photo join';
  }

  if (!empty($errors)) {

    $location .= "&errors=$errors";

    if (!empty($email)) {
      $email = urlencode($email);
      $location .= "&email=$email";
    }

    if (!empty($login)) {
      $login = urlencode($login);
      $location .= "&login=$login";
    }

    if (!empty($password)) {
      $password = urlencode($password);
      $location .= "&password=$password";
    }

    if (!empty($passwordRepeat)) {
      $passwordRepeat = urlencode($passwordRepeat);
      $location .= "&passwordRepeat=$passwordRepeat";
    }

    if (!empty($userpicFilePhoto['tmp_name'])) {
      $file_name = getEndPath($userpicFilePhoto['name'], '.');
      $randomName = generateRandomFileName();
      $file_name = $randomName . '.' . $file_name;
      $file_path = __DIR__ . '/uploads/';
      move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
      $photo = urlencode('uploads/' . $file_name);
    }

    if (!empty($photoPathForPageReload)) {
      $photo = $photoPathForPageReload;
    }

    if (!empty($photoPathForPageReload) || !empty($userpicFilePhoto['tmp_name'])) {
      $location .= "&photo=$photo";
    }


    header($location);
  } else {
    if (!empty($userpicFilePhoto['tmp_name'])) {
      if (!empty($photoPathForPageReload)) {
        unlink($photoPathForPageReload);
      }
      $file_name = $userpicFilePhoto['name'];
      $file_path = __DIR__ . '/uploads/';
      move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
      $photo = '/uploads/' . $userpicFilePhoto['name'];
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $result = mysqli_query(connect(), "INSERT INTO users (registration_date, email, password, avatar_link, user_login) VALUE (NOW(), '$email', '$password', '$photo', '$login')");
    header("Location: /main.html");
  }
}

if ($_GET['registration'] === '1') {
  $page_content = include_template('reg-template.php');
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации']);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
?>

