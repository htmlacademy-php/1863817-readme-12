<?php

/**
 * Устанавливает соединение с бд
 * @return object в случаи успешного соединения возвращает объект соединения
 * @return false в случаи ошибки соединения
 */
function connect()
{
  $con =  mysqli_connect("127.0.0.1", "root", "", "readme");
  mysqli_set_charset($con, "utf8");

  return $con;
}

/**
 * Делает запрос к БД и преобразовывает результат в двумерный массив
 * @param object $conWithDatabase ресурс соединения с бд
 * @param string $sql запрос
 * @return array в случаи успешного запроса возвращает массив с данными
 * @return false в случаи ошибки запроса
 */
function doQuery($conWithDatabase, $sql)
{
  $result = mysqli_query($conWithDatabase, $sql);

  if (isset($result)) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $rows;
  } else {
    return false;
  }
}

/**
 * Получение аватара пользователя
 * @param string $login уникальный логин пользователя
 * @return array в случаи успешного запроса возвращает массив с полем avatar_link
 * @return false в случаи ошибки запроса
 */
function getAvatarForUser($login)
{
  return $result = doQuery(connect(), "SELECT avatar_link FROM users WHERE user_login = '$login'");
}

/**
 * Делает запрос на получение постов в зависимости от типа контента или его отсутствии
 * @param string $userId уникальный id юзера, сессия которого открыта
 * @return array в случаи успешного запроса возвращает массив с постами
 * @return false в случаи ошибки запроса
 */
function doQueryForType($userId)
{
  $condition = '';

  if (isset($_GET['post']) && !empty($_GET['post'])) {
    if ($_GET['post'] === '1') {
      $condition = " WHERE posts.content_type = 'post-quote'";
    }
    if ($_GET['post'] === '2') {
      $condition = " WHERE posts.content_type = 'post-text'";
    }
    if ($_GET['post'] === '3') {
      $condition = " WHERE posts.content_type = 'post-photo'";
    }
    if ($_GET['post'] === '4') {
      $condition = " WHERE posts.content_type = 'post-link'";
    }
    if ($_GET['post'] === '5') {
      $condition = " WHERE posts.content_type = 'post-video'";
    }
  }

  $sql = "SELECT posts.*, users.user_login, users.avatar_link, COUNT(likes.id_post) AS likes_amount,
  (SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id_post AND likes.id_user = $userId) AS amILikeThisPost,
  (SELECT COUNT(*) FROM comments WHERE comments.id_post = posts.id_post) AS comments_amount
  FROM posts
  LEFT JOIN likes ON likes.id_post = posts.id_post
  JOIN users ON posts.id_user = users.id_user" . $condition . " GROUP BY posts.id_post, users.id_user";

  $posts = doQuery(connect(), $sql);

  return $posts;
}

/**
 * Создает транзакцию для добавления информации о посте в таблицу posts и добавления информации о тэгах в таблицу hashtags в случаи их наличия,
 * в случаии отсутствия тэгов происходит запрос только на добавление информации о посте
 * @param object $con ресурс соединения с бд
 * @param string $tags значение поля теги
 * @param string $sql запроса на добавление поста
 * @return integer в случаи успешной транзакции и коммита возвращает id добавленного поста
 * @return string в случаи ошибки транзакции происходит откат возвращается строка 'error'
 */
function transactionForAddPosts($con, $tags, $sql)
{
  if (!empty($tags)) {
    $result = mysqli_query($con, $sql);
    $id = mysqli_insert_id($con);
    $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");

    if ($result && $tagResult) {
      mysqli_query($con, "COMMIT");
    } else {
      mysqli_query($con, "ROLLBACK");
      $id = 'error';
    }
  } else {
    $result = mysqli_query($con, $sql);
    $id = mysqli_insert_id($con);
  }

  return $id;
}

/**
 * Возвращает количество непрочитанных сообщений пользоателя
 * @param integer $userId уникальный id юзера, сессия которого открыта
 * @return integer в случаи наличия непрочитанных сообщений возвращает их количество
 * @return false в случаи отсутствия непрочитанных сообщений
 */
function getCountNoCheckedMessages($userId)
{
  $noCheckedMessages = doQuery(connect(), "SELECT id_message FROM messages WHERE id_for_who_writed = $userId AND checked = 0");

  if (isset($noCheckedMessages) && !empty($noCheckedMessages)) {
    return count($noCheckedMessages);
  }

  return false;
}
