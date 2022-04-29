<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
  $format_to_check = 'Y-m-d';
  $dateTimeObj = date_create_from_format($format_to_check, $date);

  return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
  $stmt = mysqli_prepare($link, $sql);

  if ($stmt === false) {
    $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
    die($errorMsg);
  }

  if ($data) {
    $types = '';
    $stmt_data = [];

    foreach ($data as $value) {
      $type = 's';

      if (is_int($value)) {
        $type = 'i';
      } else {
        if (is_string($value)) {
          $type = 's';
        } else {
          if (is_double($value)) {
            $type = 'd';
          }
        }
      }

      if ($type) {
        $types .= $type;
        $stmt_data[] = $value;
      }
    }

    $values = array_merge([$stmt, $types], $stmt_data);

    $func = 'mysqli_stmt_bind_param';
    $func(...$values);

    if (mysqli_errno($link) > 0) {
      $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
      die($errorMsg);
    }
  }

  return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
  $number = (int)$number;
  $mod10 = $number % 10;
  $mod100 = $number % 100;

  switch (true) {
    case ($mod100 >= 11 && $mod100 <= 20):
      return $many;

    case ($mod10 > 5):
      return $many;

    case ($mod10 === 1):
      return $one;

    case ($mod10 >= 2 && $mod10 <= 4):
      return $two;

    default:
      return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
  $name = 'templates/' . $name;
  $result = '';

  if (!is_readable($name)) {
    return $result;
  }

  ob_start();
  extract($data);
  require $name;

  $result = ob_get_clean();

  return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
// function check_youtube_url($url)
// {
//     $id = extract_youtube_id($url);

//     set_error_handler(function () {}, E_WARNING);
//     $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
//     restore_error_handler();

//     if (!is_array($headers)) {
//       return false;
//     }

//     $err_flag = strpos($headers[0], '200') ? 200 : 404;

//     if ($err_flag !== 200) {
//       return false;
//     }

//     return true;
// }

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
  $res = "";
  $id = extract_youtube_id($youtube_url);

  if ($id) {
    $src = "https://www.youtube.com/embed/" . $id;
    $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
  }

  return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string|null $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover(string $youtube_url = null)
{
  $res = "";
  $id = extract_youtube_id($youtube_url);

  if ($id) {
      $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
      $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
  }

  return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
// function extract_youtube_id($youtube_url)
// {
//     $id = false;

//     $parts = parse_url($youtube_url);

//     if ($parts) {
//         if ($parts['path'] == '/watch') {
//             parse_str($parts['query'], $vars);
//             $id = $vars['v'] ?? null;
//         } else {
//             if ($parts['host'] == 'youtu.be') {
//                 $id = substr($parts['path'], 1);
//             }
//         }
//     }

//     return $id;
// }

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
  $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
  $dcnt = count($deltas);

  if ($index < 0) {
    $index = 0;
  }

  if ($index >= $dcnt) {
    $index = $dcnt - 1;
  }

  $delta = $deltas[$index];
  $timeval = rand(1, current($delta));
  $timename = key($delta);

  $ts = strtotime("$timeval $timename ago");
  $dt = date('Y-m-d H:i:s', $ts);

  return $dt;
}

// Устанавливает соединение с БД
function connect () {
  $con =  mysqli_connect("localhost", "root", "","readme");
  mysqli_set_charset($con, "utf8");

  return $con;
}

// Делает запрос к БД и преобразовывает результат в двумерный массив
function doQuery ($conWithDatabase, $sql) {
  $result = mysqli_query($conWithDatabase, $sql);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  return $rows;
}

// Делает запрос в зависимости от типа контента
function doQueryForType ()
{
  if ($_GET['post'] === '1') {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND content_type = 'post-quote'");
  }
  if ($_GET['post'] === '2') {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND content_type = 'post-text'");
  }
  if ($_GET['post'] === '3') {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND content_type = 'post-photo'");
  }
  if ($_GET['post'] === '4') {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND content_type = 'post-link'");
  }
  if ($_GET['post'] === '5') {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND content_type = 'post-video'");
  }
  if (!$_GET['post']) {
    $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC");
  }

  return $posts;
}

// Добавляет класс в зависимости от типа контента
function addClass ($key, $param, $class)
{
  if ($_GET[$key] === $param) {
    return $class;
  }
}

// Добавляет ссылку в случаи если текст поста больше 300 символов
function addLinkForBigText ($string, $symbols = 300)
{
  if (is_string($string)) {
    $arrayWords = explode (" ", $string);
    $newString ='';
    $length = 0;
    $cut = false;
    foreach ($arrayWords as $key => $value) {
      $length += iconv_strlen($value);
      $newString .= ' ' . $value;
      if ($length >= 300) {
        $newString = $newString . '...';
        $cut = true;
        break;
      } else {
        $newString = implode (" ", $arrayWords);
      }
    }
  }

  return [$newString, $cut];
}

// Делает запрос на показ конкретного поста
function getPostById () {
  $id = $_GET['post-id'];
  $id *= 1;
  $post = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user AND id_post = $id");

  return $post;
}

// Делает запрос на показ подписчиков по айди
function getSubById ()
{
  $post = getPostById();
  $idUser = $post[0]['id_user'];
  $subscriptions = doQuery(connect(), "SELECT * FROM subscriptions WHERE id_receiver_sub = $idUser");

  return $subscriptions;
}

// Получение всех постов по айди
function getAllPostsPostsById ()
{
  $post = getPostById();
  $idUser = $post[0]['id_user'];
  $posts = doQuery(connect(), "SELECT * FROM posts WHERE id_user = $idUser");

  return $posts;
}

// Получение лайков поста
function getLikesForPost ()
{
  $id = $_GET['post-id'];
  $id *= 1;
  $likes = doQuery(connect(), "SELECT * FROM likes WHERE id_post = $id");

  return $likes;
}

// Получение тегов поста
function getTagsForPost ()
{
  $id = $_GET['post-id'];
  $id *= 1;
  $tags = doQuery(connect(), "SELECT * FROM hashtags WHERE id_post = $id");

  return $tags;
}

function getCommentsForPost ()
{
  $id = $_GET['post-id'];
  $id *= 1;
  $comments = doQuery(connect(), "SELECT * FROM comments WHERE id_post = $id");

  return $comments;
}

// создает дату в формате Unix
function createUnixInterval ($dataForPost)
{
  return strtotime('now') - strtotime($dataForPost);
}

// создает текст для даты в нужной форме
function createTextForDate ($data)
{
  $resultInterval = createUnixInterval($data);

  if ($resultInterval / 60 / 60 < 1) {
    $resultNumber = ($resultInterval / 60);
    $rightForm = get_noun_plural_form($resultNumber, 'минута', 'минуты', 'минут');
  } else if ($resultInterval / 60 / 60 / 24 < 1) {
    $resultNumber = ($resultInterval / 60 / 60);
    $rightForm = get_noun_plural_form($resultNumber, 'час', 'часа', 'часов');
  } else if ($resultInterval / 60 / 60 / 24 >= 1 && $resultInterval / 60 / 60 / 24 < 7) {
    $resultNumber = ($resultInterval / 60 / 60 / 24);
    $rightForm = get_noun_plural_form($resultNumber, 'день', 'дня', 'дней');
  } else if ($resultInterval / 60 / 60 / 24 >= 7 and $resultInterval / 60 / 60 / 24 / 7 < 5) {
    $resultNumber = ($resultInterval / 60 / 60 / 24 / 7);
    $rightForm = get_noun_plural_form($resultNumber, 'неделя', 'недели', 'недель');
  } else if ($resultInterval / 60 / 60 / 24 / 7 > 5) {
    $resultNumber = floor($resultInterval / 60 / 60 / 24 / 30);
    $rightForm = get_noun_plural_form($resultNumber, 'месяц', 'месяца', 'месяцев');
  }
  if (isset($resultNumber, $rightForm) && !empty($resultNumber) && !empty($rightForm)) {
    return $resultNumber . ' ' . $rightForm . ' ' . 'назад';
  }

  return false;
}
