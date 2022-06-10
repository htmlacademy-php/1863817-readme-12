<?php
require_once 'vendor/autoload.php';

function sendEmail($email, $title, $body)
{
  $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
    ->setUsername('blinov228322@mail.ru')
    ->setPassword('kV7WExHcHmpiMAwfwqet');

  $mailer = new Swift_Mailer($transport);

  $message = (new Swift_Message(''))
    ->setFrom('blinov228322@mail.ru')
    ->setTo($email)
    ->setSubject($title)
    ->setBody($body);

  $result = $mailer->send($message);
}
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
function check_youtube_url($url)
{
  $id = extract_youtube_id($url);

  set_error_handler(function () {
  }, E_WARNING);
  $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
  restore_error_handler();

  if (!is_array($headers)) {
    return false;
  }

  $err_flag = strpos($headers[0], '200') ? 200 : 404;

  if ($err_flag !== 200) {
    return false;
  }

  return true;
}

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
function embed_youtube_cover(string $youtube_url = null, $width, $height, string $class = null)
{
  $res = "";
  $id = extract_youtube_id($youtube_url);

  if ($id) {
    $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
    $res = "<img class='$class' alt='youtube cover' width='$width' height='$height' src='" . $src . "' />";
  }

  return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
  $id = false;

  $parts = parse_url($youtube_url);

  if ($parts) {
    if ($parts['path'] == '/watch') {
      parse_str($parts['query'], $vars);
      $id = $vars['v'] ?? null;
    } else {
      if ($parts['host'] == 'youtu.be') {
        $id = substr($parts['path'], 1);
      }
    }
  }

  return $id;
}

// Добавляет ссылку в случаи если текст поста больше 300 символов
function addLinkForBigText($string, $symbols = 300)
{
  if (is_string($string)) {
    $arrayWords = explode(" ", $string);
    $newString = '';
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
        $newString = implode(" ", $arrayWords);
      }
    }
  }

  return [$newString, $cut];
}

// создает дату в формате Unix
function createUnixInterval($dataForPost)
{
  return strtotime('now') - strtotime($dataForPost);
}

// создает текст для даты в нужной форме
function createTextForDate($data)
{
  $resultInterval = createUnixInterval($data);

  if ($resultInterval / 60 / 60 < 1) {
    $resultNumber = ($resultInterval / 60);
    $resultNumber = round($resultNumber);
    $rightForm = get_noun_plural_form($resultNumber, 'минута', 'минуты', 'минут');
  } else if ($resultInterval / 60 / 60 / 24 < 1) {
    $resultNumber = ($resultInterval / 60 / 60);
    $resultNumber = round($resultNumber);
    $rightForm = get_noun_plural_form($resultNumber, 'час', 'часа', 'часов');
  } else if ($resultInterval / 60 / 60 / 24 >= 1 && $resultInterval / 60 / 60 / 24 < 7) {
    $resultNumber = ($resultInterval / 60 / 60 / 24);
    $resultNumber = round($resultNumber);
    $rightForm = get_noun_plural_form($resultNumber, 'день', 'дня', 'дней');
  } else if ($resultInterval / 60 / 60 / 24 >= 7 and $resultInterval / 60 / 60 / 24 / 7 < 5) {
    $resultNumber = ($resultInterval / 60 / 60 / 24 / 7);
    $resultNumber = round($resultNumber);
    $rightForm = get_noun_plural_form($resultNumber, 'неделя', 'недели', 'недель');
  } else if ($resultInterval / 60 / 60 / 24 / 7 > 5) {
    $resultNumber = floor($resultInterval / 60 / 60 / 24 / 30);
    $resultNumber = round($resultNumber);
    $rightForm = get_noun_plural_form($resultNumber, 'месяц', 'месяца', 'месяцев');
  }

  if (isset($resultNumber, $rightForm) && !empty($resultNumber) && !empty($rightForm)) {
    return $resultNumber . ' ' . $rightForm;
  }

  return false;
}

function getEndPath($fullpath, $symbol)
{
  $url = $fullpath;
  $stringToArray = explode($symbol, $url);
  $lastElement = count($stringToArray) - 1;
  return $endPath = $stringToArray[$lastElement];
}

function downloadPhotoFromWebLink($link)
{
  $endPath = getEndPath($link, '/');
  $file_path = '../readme/uploads/';
  $pathLink = $file_path . $endPath;
  file_put_contents($pathLink, file_get_contents($link));
}

function generateRandomFileName()
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  return $randomName = substr(str_shuffle($permitted_chars), 0, 10);
}
