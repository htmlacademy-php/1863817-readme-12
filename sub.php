<?php
require 'util/helpers.php';
require 'util/mysql.php';
// require 'util/validate.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$profileId = intval($_GET['id']);
$userId = $_SESSION['userId'];

$checkExistProfile = doQuery($con, "SELECT * FROM users WHERE id_user = $profileId");

if ($checkExistProfile) {
  if ($_GET['sub'] === 'sub') {
    $insertSub = doQuery($con, "INSERT INTO subscriptions(id_subscriber, id_receiver_sub) VALUE ($userId, $profileId)");
  } else {
    $deleteSub = doQuery($con, "DELETE FROM subscriptions WHERE id_subscriber = $userId AND id_receiver_sub = $profileId");
  }

  $location = 'Location: ' . $_SERVER['HTTP_REFERER'];
  header($location);
}
