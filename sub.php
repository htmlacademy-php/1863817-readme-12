<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();
isSessionExist();
$con =  connect();

$profileId = intval($_GET['id']);
$userId = $_SESSION['userId'];

$checkExistProfile = doQuery($con, "SELECT * FROM users WHERE id_user = $profileId");
$emailTo = $checkExistProfile[0]['email'];
$loginSub = $_SESSION['username'];
$loginReceiverSub = $checkExistProfile[0]['user_login'];

if ($checkExistProfile) {
  if ($_GET['sub'] === 'sub') {
    $insertSub = mysqli_query($con, "INSERT INTO subscriptions(id_subscriber, id_receiver_sub) VALUE ($userId, $profileId)");
    sendEmail($emailTo, 'У вас новый подписчик', 'Здравствуйте, ' . $loginReceiverSub . '. На вас подписался новый пользователь ' . $loginSub . '. Вот ссылка на его профиль: http://readme/profile.php?id=' . $userId . '&active=posts');
  } else {
    $deleteSub = mysqli_query($con, "DELETE FROM subscriptions WHERE id_subscriber = $userId AND id_receiver_sub = $profileId");
  }

  $location = 'Location: ' . $_SERVER['HTTP_REFERER'];
  header($location);
}
