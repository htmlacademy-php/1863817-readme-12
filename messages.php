<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();

$con = connect();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$userId = $_SESSION['userId'];

$idWhoWrited = $_GET['dialogWithUser'];
$noCheckedMessages = doQuery($con, "SELECT id_message FROM messages WHERE id_who_writed = $idWhoWrited AND id_for_who_writed = $userId AND checked = 0");

if (isset($noCheckedMessages) && !empty($noCheckedMessages)) {

  foreach ($noCheckedMessages as $key => $value) {
    $idMessage = $value['id_message'];
    $update = mysqli_query($con, "UPDATE messages SET checked = 1 WHERE id_message = $idMessage");
  }
}


$infoFromMessagesTable = doQuery($con, "SELECT messages.*, messages.id_message, users.user_login, users.id_user, users.avatar_link
FROM users
left JOIN messages ON users.id_user = messages.id_who_writed OR users.id_user = messages.id_for_who_writed and users.id_user != $userId
WHERE messages.id_for_who_writed = $userId OR messages.id_who_writed = $userId and users.id_user != $userId
ORDER BY messages.id_message");

if (isset($infoFromMessagesTable) && !empty($infoFromMessagesTable)) {
  foreach ($infoFromMessagesTable as $key => $value) {
    $humans[] = $value['user_login'];
  }

  $humans = array_unique($humans);

  foreach ($humans as $key => $value) {
    $dialogs[$value] = [];
  }

  foreach ($infoFromMessagesTable as $key => $value) {
    $userLogin = $value['user_login'];
    $dialogs[$userLogin][] = $value;
  }

  if (count($dialogs) !== 0 && !isset($_GET['dialogWithUser'])) {
    $firstKey = array_key_first($dialogs);
    $dialogWithUser = $dialogs[$firstKey][0]['id_user'];
    header('Location: /messages.php?dialogWithUser=' . $dialogWithUser);
  }

  if (isset($_POST['message'])) {
    $value = $_POST['message'];
    test_input($value);
    $error = validateLength($value, 1, 300);

    if (!empty($error)) {
      header("Location: /messages.php?dialogWithUser=" . $_POST['dialog'] . "&error=$error");
    } else {
      $forWhoWrited = $_POST['dialog'];
      print($forWhoWrited);
      $sql = "INSERT INTO messages (message_date, message_text, id_who_writed, id_for_who_writed) VALUE (NOW(), '$value', $userId, $forWhoWrited)";
      $result = mysqli_query($con, $sql);
      header("Location: /messages.php?dialog=" . $_POST['dialog']);
    }
  }

  foreach ($dialogs as $firstKey => $dialog) {
    usort($dialog, function ($a, $b) {
      return (strtotime($a['message_date']) < strtotime($b['message_date']));
    });

    foreach ($dialog as $secondKey => $message) {

      if ($message['id_for_who_writed'] !== $_SESSION['userId']) {
        break;
      } else {
        if ($message['checked'] === '0') {
          $lastElement = count($dialog) - 1;
          $dialog[$lastElement]['newMessagesCount'] += 1;
        }
      }
    }

    usort($dialog, function ($a, $b) {
      return (strtotime($a['message_date']) > strtotime($b['message_date']));
    });
    if ($dialog[0]['id_user'] === $_GET['dialogWithUser']) {
      $isNewDialog += 1;
    }

    $dialogsSortByDate[$firstKey] = $dialog;
  }

  if (!isset($isNewDialog)) {
    $id = $_GET['dialogWithUser'];
    $newUserInfo = doQuery($con, "SELECT user_login, avatar_link FROM users WHERE id_user = $id");
    $newUser['login'] = $newUserInfo[0]['user_login'];
    $newUser['avatar'] = $newUserInfo[0]['avatar_link'];
    $newUser['id'] = $_GET['dialogWithUser'];
  }

  if (isset($newUser) && !empty($newUser)) {
    $page_content = include_template('messages-template.php', ['dialogs' => $dialogsSortByDate, 'newMessagesCount' => $newMessagesCount, 'newUser' => $newUser, 'avatar' => getAvatarForUser($_SESSION['username'])]);
  } else {
    $page_content = include_template('messages-template.php', ['dialogs' => $dialogsSortByDate, 'newMessagesCount' => $newMessagesCount, 'avatar' => getAvatarForUser($_SESSION['username'])]);
  }

  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: личные сообщения', 'avatar' => getAvatarForUser($_SESSION['username'])]);
} else {
  $layout_content = include_template('layout.php', ['content' => 'В данный моменту вас нет активных диалогов', 'title' => 'readme: личные сообщения', 'avatar' => getAvatarForUser($_SESSION['username'])]);
}

// echo ('<pre>');
// print_r($dialogsSortByDate);
// echo ('</pre>');

// echo ('<pre>');
// print_r($newMessage);
// echo ('</pre>');


if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
