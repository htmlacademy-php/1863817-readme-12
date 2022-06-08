<?php
require 'util/helpers.php';
require 'util/mysql.php';
// require_once 'vendor/autoload.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

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

    // $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
    //   ->setUsername('blinov228322@mail.ru')
    //   ->setPassword('kV7WExHcHmpiMAwfwqet');

    // $mailer = new Swift_Mailer($transport);

    // $message = (new Swift_Message(''))
    //   ->setFrom('blinov228322@mail.ru')
    //   ->setTo($emailTo)
    //   ->setSubject('У вас новый подписчик')
    //   ->setBody('Здравствуйте, ' . $loginReceiverSub . '. На вас подписался новый пользователь ' . $loginSub . '. Вот ссылка на его профиль: http://readme/profile.php?id=' . $userId . '14&active=posts');

    // $result = $mailer->send($message);
  } else {
    $deleteSub = mysqli_query($con, "DELETE FROM subscriptions WHERE id_subscriber = $userId AND id_receiver_sub = $profileId");
  }

  $location = 'Location: ' . $_SERVER['HTTP_REFERER'];
  header($location);
}
