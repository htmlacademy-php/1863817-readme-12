<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = test_input($_POST["email"]);
  $login = test_input($_POST["login"]);
  $password = test_input($_POST["password"]);
  $passwordRepeat = test_input($_POST["password-repeat"]);
  $userpicFilePhoto = $_FILES["userpic-file-photo"];
  $photoPathForPageReload = test_input($_POST["link-download-if-reload"]);

  // find errors

  $resultEmail = validateEmail($email);

  if ($resultEmail) {
    $errors[] = $resultEmail . 'email';
  }

  $resultLogin = validateLogin($login);

  if ($resultLogin) {
    $errors[] = $resultLogin . 'login';
  }

  $resultPassword = validatePassword($password);

  if ($resultPassword) {
    $errors[] = $resultPassword . 'password';
  }

  $resultRepeatPassword = validateRepeatPassword($password, $passwordRepeat);

  if ($resultRepeatPassword) {
    $errors[] = $resultRepeatPassword . 'repeat';
  }

  $resultPhoto = validatePhotoForRegistrations($userpicFilePhoto);

  if ($resultPhoto) {
    $errors[] = $resultPhoto . 'photo';
  }

  // save value

  if (!empty($email)) {
    $inputValues[] = $email . 'address';
  }

  if (!empty($login)) {
    $inputValues[] = $login . 'login';
  }

  if (!empty($password)) {
    $inputValues[] = $password . 'password';
  }

  if (!empty($passwordRepeat)) {
    $inputValues[] = $passwordRepeat . 'repeat';
  }

  if (!empty($errors)) {
    if (!empty($userpicFilePhoto['tmp_name'])) {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $file_name = $userpicFilePhoto['name'];
      $file_name = explode('.', $file_name);
      $lastElement = count($file_name) - 1;
      $file_name = $file_name[$lastElement];
      $randomName = substr(str_shuffle($permitted_chars), 0, 10);
      $file_name = $randomName . '.' . $file_name;
      print($file_name);
      $file_path = __DIR__ . '/uploads/';
      move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
      $inputValues['photo'] = 'uploads/' . $file_name . 'photo';
    }
    $errors = implode('join', $errors);
    $inputValues = implode('join', $inputValues);
    $errors = urlencode($errors);
    $inputValues = urlencode($inputValues);

    header("Location: /registration.php?registration=1&errors=$errors&inputValues=$inputValues");
  } else {
    if (!empty($photoPathForPageReload)) {
      unlink($photoPathForPageReload);
    }
    $file_name = $userpicFilePhoto['name'];
    $file_path = __DIR__ . '/uploads/';
    move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
    $photo = '/uploads/' . $userpicFilePhoto['name'];

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

