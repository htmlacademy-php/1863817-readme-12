<?php

function test_input($con, $data)
{
  $data = trim($data);
  $data = mysqli_real_escape_string($con, $data);
  $data = htmlspecialchars($data);
  return $data;
}

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

  if (count($textErrors) > 1) {
    $textErrors = implode('<br>', $textErrors);
  }

  if (!empty($textErrors)) {
    return $textErrors;
  }

  return false;
}

function validateRepeatPassword($firstPassword, $secondPassword)
{
  if (empty($secondPassword)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  if ($firstPassword !== $secondPassword) {
    return $textError = 'Пароли не совпадают';
  }
}

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

function validateLength($name, $min, $max)
{
  if (empty($name)) {
    return $textError = 'Это поле должно быть заполнено';
  }

  $len = iconv_strlen($name);

  if ($len > $max || $len < $min) {
    return $textError = "Значение должно быть от $min до $max символов $len";
  } else {
    return false;
  }
}
