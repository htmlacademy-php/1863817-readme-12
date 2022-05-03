<?php

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validateEmail ($value)
{
  if (empty($value)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  $correctEmail = filter_var($value, FILTER_VALIDATE_EMAIL);

  if (!$correctEmail) {
    return $textError = 'Введен некорректный адрес электронной почты';
  }

  $con = mysqli_connect("localhost", "root", "","readme");
  $sameEmail = mysqli_query($con, "SELECT * FROM users WHERE email='$value'");
  $result = mysqli_num_rows($sameEmail);

  if ($result) {
    return $textError = 'Введённый вами адрес электронной почты уже используется на сайте';
  }

  return false;
}

function validateLogin ($value)
{
  if (empty($value)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  $length = strlen($value);

  if ($length < 3 || $length > 20) {
    return $textError = "Значение должно быть от 3 до 20 символов";
  }

  $con = mysqli_connect("localhost", "root", "","readme");
  $sameLogin = mysqli_query($con, "SELECT * FROM users WHERE user_login ='$value'");
  $result = mysqli_num_rows($sameLogin);

  if ($result) {
    return $textError = 'Выбранный вами логин уже занят';
  }

  return false;
}

function validatePassword ($value)
{
  if (empty($value)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  $length = strlen($value);

  if ($length < 9 || $length > 20) {
    $textError = 'Значение должно быть от 9 до 20 символовseparator';
  }

  if (!preg_match("#[A-Z]+#", $value)) {
    $textError .= 'Пароль должен содержать хотя бы одну заглавную буквуseparator ';
  }

  if (!preg_match("#[a-z]+#", $value)) {
    $textError .= 'Пароль должен содержать хотя бы одну маленькую буквуseparator';
  }

  if (!preg_match("#[0-9]+#", $value)) {
    $textError .= 'Пароль должен содержать хотя бы одну цифруseparator ';
  }

  if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
    $textError .= 'Пароль должен содержать только цифры и буквы латинского алфавита, без использования спецсимволов или пробеловseparator';
  }

  if (!empty($textError)) {
    return $textError;
  }

  return false;
}

function validateRepeatPassword ($firstPassword, $secondPassword)
{
  if (empty($secondPassword)) {
    return $textError = 'Поле обязательно для заполнения';
  }

  if ($firstPassword !== $secondPassword) {
    return $textError = 'Пароли не совпадают';
  }
}

function validatePhotoForRegistration ($file)
{
  if (empty($file['tmp_name'])) {
    return false;
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $file_name = $file['tmp_name'];
  $file_size = $file['size'];
  $file_type = finfo_file($finfo, $file_name);

  if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/gif' && $file_type !== 'image/jpg') {
    return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gif';
  } else {
    if ($file_size > 500000) {
      return $textError = 'Максимальный размер файла: 500Кбphoto';
    }

    return false;
  }
}

function validateFileInputAndPhotoLink ($file, $link)
{
  if (empty($file['tmp_name']) && empty($link)) {
    return $textError = 'Хотя бы одно из полей с указанием фотографии должно быть заполненноphoto, ';
  } else if (!empty($file['tmp_name'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_type = finfo_file($finfo, $file_name);

    if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/gif' && $file_type !== 'image/jpg') {
      if (!empty($link)) {
        $flag = filter_var($link, FILTER_VALIDATE_URL);

        if($flag) {
          $result = file_get_contents($flag);
          if ($result) {
            return 3;
          } else {
            return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto Ссылка для загрузки ресурса также указана некорректноphotolinkForPic, ';
          }
        } else {
          return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto Ссылка для загрузки ресурса также указана некорректноphotolinkForPic, ';
        }
      } else {
        return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto, ';
      }
    }

    if ($file_size > 500000) {
      return $textError = 'Максимальный размер файла: 500Кбphoto, ';
    } else {
      return 2;
    }
  } else if (empty($file['tmp_name']) && !empty($link)) {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if($flag) {
      $result = file_get_contents($flag);
      if ($result) {
        return 3;
      } else {
        return $textError = 'При загрузке изображения по ссылке произошла ошибкalinkForPic, ';
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурсаlinkForPic, ';
    }
  }
}

function validateVideo ($link)
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

function validateTags ($string)
{
  if (!empty($string)) {
    $result = explode('#', $string);

    foreach($result as $key => $value) {
      $value = trim($value);
      $result2[] = $value;
    }

    $result = implode(' #', $result2);
    $result = trim($result);
    $allElementInString = str_split($string);

    $tag = strripos($string, '#');

    if (!$tag && $tag !== 0) {
      print($tag);
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

    if (!preg_match("/^[a-zA-Z0-9#]+$/", $string)) {
      $textError .= 'Тег не должен содержать спецсимволов, помимо знака "%23"';
    } else {
      return false;
    }

    if (!empty($textError)) {
      return $textError;
    }

  } else {
    return false;
  }
}

function validateWebLink ($link)
{
  if (empty($link)) {
    return $textError = 'Это поле должно быть заполнено';
  } else {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if($flag) {
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

function validateHeadingTextAndAuthor ($name, $min, $max)
{
  if (empty($name)) {
    return $textError = 'Это поле должно быть заполнено';
  }

  $len = strlen($name);

  if ($len > $max || $len < $min) {
    return $textError = "Значение должно быть от $min до $max символов";
  } else {
    return false;
  }
}
?>
