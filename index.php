<?php
require 'util/helpers.php';
require 'util/mysql.php';

$con = connect();
$valueEmail = $_POST['login'];

if ($_POST) {
  $location = "Location: /login.php?errors=1";

  $con = connect();
  $valueLogin = $_POST['login'];
  $sameLogin = mysqli_query($con, "SELECT * FROM users WHERE user_login = '$valueLogin'");
  $resultLogin = mysqli_num_rows($sameLogin);

  if (empty($valueLogin)) {
    $location .= "&loginError=Введите логин";
  } else {
    $location .= "&valueLogin=$valueLogin";
  }

  if (!$resultLogin && !empty($valueLogin)) {
    $location .= "&loginError=Неверный логин";
  }

  $hash = doQuery($con, "SELECT password FROM users WHERE user_login = '$valueLogin'");
  $hash = $hash[0]['password'];
  $pass = $_POST['password'];
  $resultPassword = password_verify($pass, $hash);

  if (!$resultPassword && !empty($pass)) {
    $location .= "&passError=Пароли не совпадают";
  }

  if (empty($pass)) {
    $location .= "&passError=Введите пароль";
  }

  if ($resultLogin && $resultPassword) {
    session_start();
    $_SESSION['username'] = $valueLogin;
    // echo ('<pre>');
    // print_r($_SESSION);
    // echo ('</pre>');
  } else {
    header($location);
  }
}


if (isset($_SESSION)) {
  header('Location: /feed.php');
} else {
  header('Location: /login.php');
}
