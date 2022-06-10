<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

test_input($_POST['login']);
test_input($_POST['password']);

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

  list($hash) = doQuery($con, "SELECT password FROM users WHERE user_login = '$valueLogin'");
  $hash = $hash['password'];
  $pass = $_POST['password'];
  $resultPassword = password_verify($pass, $hash);

  if (!$resultPassword && !empty($pass)) {
    $location .= "&passError=Пароли не совпадают";
  }

  if (empty($pass)) {
    $location .= "&passError=Введите пароль";
  }

  list($id) = doQuery($con, "SELECT id_user FROM users WHERE user_login = '$valueLogin'");
  $id = $id['id_user'];

  if ($resultLogin && $resultPassword) {
    session_start();
    $_SESSION['username'] = $valueLogin;
    $_SESSION['userId'] = $id;
    header('Location: /feed.php?filter=all');
  } else {
    header($location);
  }
} else {
  header('Location: /login.php');
}
