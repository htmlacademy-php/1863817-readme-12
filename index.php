<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

$con = connect();

if ($_POST) {
  $location = "Location: /login.php?errors=1";
  if (empty($_POST['login'])) {
    header('Location: /login.php?errors=1&loginError=Введите логин');
  } else {
    $valueLogin = test_input($con, $_POST['login']);
    $location .= "&valueLogin=$valueLogin";
  }
  $sameLogin = mysqli_query($con, "SELECT * FROM users WHERE user_login = '$valueLogin'");
  $resultLogin = mysqli_num_rows($sameLogin);

  if (!$resultLogin && !empty($valueLogin)) {
    header('Location: /login.php?errors=1&loginError=Неверный логин');
  } else {

    if (empty($_POST['password'])) {
      header($location . '&passError=Введите пароль');
    } else {
      list($hash) = doQuery($con, "SELECT password FROM users WHERE user_login = '$valueLogin'");
      $hash = $hash['password'];
      $pass = test_input($con, $_POST['password']);
      $resultPassword = password_verify($pass, $hash);

      if (!$resultPassword) {
        header($location . '&passError=Пароли не совпадают');
      } else {
        list($id) = doQuery($con, "SELECT id_user FROM users WHERE user_login = '$valueLogin'");
        $id = $id['id_user'];
        session_start();
        $_SESSION['username'] = $valueLogin;
        $_SESSION['userId'] = $id;
        header('Location: /feed.php?filter=all');
      }
    }
  }
} else {
  if (isset($_SESSION)) {
    header('Location: /feed.php?filter=all');
  } else {
    header('Location: /login.php');
  }
}
