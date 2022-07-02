<?php



/**
 * Убирает из параметра запроса/текстового поля sql/xss иньекции
 * @param object $con ресурс соединения с бд
 * @param string $data проверяемое значение
 * @return string очищенная, безопасная строка
 */
function test_input($con, $data)
{
  $data = trim($data);
  $data = mysqli_real_escape_string($con, $data);
  $data = htmlspecialchars($data);
  return $data;
}

/**
 * Проверяет поле email на наличие ошибок
 * @param string $value значение поля email
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateEmail($value)
{
  if (empty($value)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  $correctEmail = filter_var($value, FILTER_VALIDATE_EMAIL);

  if (!$correctEmail) {
    return $textError = 'Введен некорректный адрес электронной почты';
  }

  $con = mysqli_connect("localhost", "root", "", "readme");
  $sameEmail = mysqli_query($con, "SELECT email FROM users WHERE email='$value'");
  $result = mysqli_num_rows($sameEmail);

  if ($result) {
    return $textError = 'Введённый вами адрес электронной почты уже используется на сайте';
  }

  return false;
}

/**
 * Проверяет поле логин на наличие ошибок
 * @param string $value значение поля логин
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateLogin($value)
{
  if (empty($value)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  $length = iconv_strlen($value);

  if ($length < 3 || $length > 20) {
    return $textError = "Значение должно быть от 3 до 20 символов";
  }

  $con = mysqli_connect("localhost", "root", "", "readme");
  $sameLogin = mysqli_query($con, "SELECT * FROM users WHERE user_login ='$value'");
  $result = mysqli_num_rows($sameLogin);

  if ($result) {
    return $textError = 'Выбранный вами логин уже занят';
  }

  return false;
}

/**
 * Проверяет поле пароля на наличие ошибок
 * @param string $value значение поля пароль
 * @return string возвращает текст ошибки/ошибок в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validatePassword($value)
{
  if (empty($value)) {
    return $textErrors = 'Поле обязательно для заполнения';
  }

  $length = iconv_strlen($value);

  if ($length < 9 || $length > 20) {
    $textErrors[] = 'Значение должно быть от 9 до 20 символов';
  }

  if (!preg_match("#[A-Z]+#", $value)) {
    $textErrors[] = 'Пароль должен содержать хотя бы одну заглавную букву';
  }

  if (!preg_match("#[a-z]+#", $value)) {
    $textErrors[] = 'Пароль должен содержать хотя бы одну маленькую букву';
  }

  if (!preg_match("#[0-9]+#", $value)) {
    $textErrors[] = 'Пароль должен содержать хотя бы одну цифру';
  }

  if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
    $textErrors[] = 'Пароль должен содержать только цифры и буквы латинского алфавита, без использования спецсимволов или пробелов';
  }

  if (isset($textError) && count($textErrors) > 1) {
    $textErrors = implode('<br>', $textErrors);
  }

  if (!empty($textErrors)) {
    return $textErrors;
  }

  return false;
}

/**
 * Проверяет чтобы поле повтора пароля было заполненно и совпадало с паролем
 * @param string $firstPassword значение поля пароль
 * @param string $secondPassword значение поля повтора пароля
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateRepeatPassword($firstPassword, $secondPassword)
{
  if (empty($secondPassword)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  if ($firstPassword !== $secondPassword) {
    return $textError = 'Пароли не совпадают';
  }

  return false;
}

/**
 * Проверяет поле ссылки на фотографию из интернета на наличие ошибок
 * @param string $link значение поля ссылки
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validatePhotoLink($link)
{
  if (!empty($link)) {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if ($flag) {
      $result = file_get_contents($flag);
      if (!$result) {
        return $textError = 'При загрузке изображения по ссылке произошла ошибкa';
      } else {
        return false;
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурса';
    }
  } else {
    return false;
  }
}

/**
 * Проверяет добавленный пользователем файл на наличие ошибок
 * @param string $file загруженный пользователем файл
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validatePhotoFile($file)
{
  if (!empty($file['tmp_name'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_type = finfo_file($finfo, $file_name);

    if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/gif' && $file_type !== 'image/jpg') {
      return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gif';
    }

    if ($file_size > 500000) {
      return $textError = 'Максимальный размер файла: 500Кб';
    }
  } else {
    return false;
  }
}

/**
 * Проверяет поле ссылки на видео из интернета на наличие ошибок
 * @param string $link значение поля ссылки на видео
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateVideo($link)
{
  if (empty($link)) {
    return $textError = 'Это поле должно быть заполнено.';
  } else {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if ($flag) {
      $result = check_youtube_url($link);
      if ($result) {
        return false;
      } else {
        return $textError = 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурса';
    }
  }
}

/**
 * Проверяет поле теги на наличие ошибок
 * @param string $string значение поля теги
 * @return string возвращает текст ошибки/ошибок в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateTags($string)
{
  if (!empty($string)) {
    $result = explode('#', $string);

    foreach ($result as $key => $value) {
      $value = trim($value);
      $result2[] = $value;
    }

    $result = implode(' #', $result2);
    print($result);
    $result = trim($result);

    $tag = strripos($string, '#');

    if (!$tag && $tag !== 0) {
      $textError = 'Тег должен начинаться со знака "%23"';
    }

    if ($result === $string) {
      $flag = true;
    } else {
      $flag = false;
    }

    if ($flag === false) {
      $textError = 'Теги должны быть отделены пробелами';
    }

    if (!preg_match("/^[a-zA-Z0-9#\s]+$/", $string)) {
      $textError .= 'Тег не должен содержать спецсимволов, помимо знака "%23"';
    }

    if (!empty($textError)) {
      return $textError;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/**
 * Проверяет поле ссылки из интернета на наличие ошибок
 * @param string $link значение поля ссылки из интернета
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateWebLink($link)
{
  if (empty($link)) {
    return $textError = 'Это поле должно быть заполнено';
  } else {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if ($flag) {
      $result = file_get_contents($link);
      if ($result === false) {
        return $textError = 'Введен некорректный адрес ресурса';
      } else {
        return false;
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурса';
    }
  }
}

/**
 * Валидирует длину переданного поля
 * @param string $name поле, длину которого необходимо проверить
 * @param string $min минимально допустимая длинна
 * @param string $max максимально допустимая длинна
 * @return string возвращает текст ошибки в случаи передачи невалидного значения
 * @return false в случаи отсутствия ошибок
 */
function validateLength($name, $min, $max)
{
  if (empty($name)) {
    return $textError = 'Это поле должно быть заполнено';
  }

  $len = iconv_strlen(trim($name));

  if ($len > $max || $len < $min) {
    return $textError = "Значение должно быть от $min до $max символов";
  } else {
    return false;
  }
}
