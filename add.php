<?php

if ($_GET['add-post'] === '1') {
  $page_content = include_template('adding-post.php', ['types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: добавление публикации']);

}

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

function check_youtube_url($url)
{
  $id = extract_youtube_id($url);

  set_error_handler(function () {}, E_WARNING);
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

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validateFileInputAndPhotoLink ($file, $link)
{
  if (empty($file['tmp_name']) && empty($link)) {
    return $textError = 'Хотя бы одно из полей с указанием фотографии должно быть заполненноphoto';
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
            return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto Ссылка для загрузки ресурса также указана некорректноphotolinkForPic';
          }
        } else {
          return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto Ссылка для загрузки ресурса также указана некорректноphotolinkForPic';
        }
      } else {
        return $textError = 'Файл должен быть в одном из трех форматов jpeg/png/gifphoto';
      }
    }

    if ($file_size > 500000) {
      return $textError = 'Максимальный размер файла: 500Кбphoto';
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
        return $textError = 'При загрузке изображения по ссылке произошла ошибкalinkForPic';
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурсаlinkForPic';
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

    if ($tag === 0) {
      return false;
    }

    if (!$tag) {
      print($tag);
      return $textError = 'Тэг должен начинаться со знака "%23"';
    }

    if ($result === $string) {
      $flag = true;
      print(1);
    } else {
      $flag = false;
      print(2);
    }

    if ($flag === false) {
      print(3);
      return $textError = 'Тэги должны быть отделены пробелами';
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function validateWebLink ($link)
{
  if (empty($link)) {
    return $textError = 'Это поле должно быть заполнено.';
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['photo-heading'])) {
    // photo

    // inputs

    $heading = test_input($_POST["photo-heading"]);
    $userpicFilePhoto = $_FILES["userpic-file-photo"];
    $linkPhoto = test_input($_POST["photo-link"]);
    $linkDownloadIfReload = test_input($_POST["link-download-if-reload"]);
    $tags = test_input($_POST["photo-tags"]);

    // find errors

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    if (!empty($userpicFilePhoto['tmp_name']) || !empty($linkPhoto)) {
      $resultPhoto = validateFileInputAndPhotoLink($userpicFilePhoto, $linkPhoto);
    } else {
      $resultPhoto = 4;
    }

    $flagPhoto = gettype($resultPhoto);

    if ($flagPhoto === 'string') {
      $errors[] = $resultPhoto;
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors[] = $resultTags . 'tags';
    }

    // save value

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($linkPhoto)) {
      $inputValues['link'] = $linkPhoto . 'link';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = explode('#', $tags);
      $inputValues['tags'] = implode('.', $inputValues['tags']);
      $inputValues['tags'] = $inputValues['tags'] . 'tags';
    }

    if (!empty($errors)) {
      if (!empty($userpicFilePhoto['tmp_name'])) {
        $file_name = $userpicFilePhoto['name'];
        $file_name = explode('.', $file_name);
        $lastElement = count($file_name) - 1;
        $file_name = $file_name[$lastElement];
        $file_name = 'before-query.' . $file_name;
        print($file_name);
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
        $inputValues['photo'] = 'uploads/' . $file_name . 'photo';
      }

      $errors = implode(', ', $errors);
      if (!empty($inputValues)) {
        $inputValues = implode(', ', $inputValues);
      }
      header("Location: /?add-post=1&filter=3&errors=$errors&inputValues=$inputValues");
    } else {
      if ($resultPhoto === 3) {
        $url = $linkPhoto;
        $stringToArray = explode('/', $url);
        $lastElement = count($stringToArray) - 1;
        $endPath = $stringToArray[$lastElement];
        $file_path = __DIR__ . '/uploads/';
        $pathLink = $file_path . $endPath;
        $result = file_put_contents($pathLink, file_get_contents($url));
        $photo = '/uploads/' . $endPath;
      } else if ($resultPhoto === 2) {
        if (!empty($linkDownloadIfReload)) {
          unlink($linkDownloadIfReload);
        }
        $file_name = $userpicFilePhoto['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
        $photo = '/uploads/' . $userpicFilePhoto['name'];
      } else if ($resultPhoto === 4) {
        $flag = strripos($linkDownloadIfReload, 'before-query.');
        if ($flag === false) {
          $photo = $linkDownloadIfReload;
        } else {
          $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
          $randomName = substr(str_shuffle($permitted_chars), 0, 10);
          $file_name = explode('.', $linkDownloadIfReload);
          $lastElement = count($file_name) - 1;
          $file_name = $file_name[$lastElement];
          $newPath = 'uploads/' . $randomName . '.' . $file_name;
          $result = rename($linkDownloadIfReload, $newPath);
          $photo = $newPath;
        }
      }

      $con = mysqli_connect("localhost", "root", "","readme");
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, image_link, id_user) VALUE (NOW(), '$heading', 'post-photo', '$photo', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /?post-id=$id");
    }

  }

  if (isset($_POST['video-heading'])) {
    // video

    // inputs

    $heading = test_input($_POST["video-heading"]);
    $linkVideo = test_input($_POST["video-link"]);
    $tags = test_input($_POST["video-tags"]);

    // find errors

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    $resultVideo = validateVideo($linkVideo);

    if ($resultVideo) {
      $errors[] = $resultVideo . 'link';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors[] = $resultTags . 'tags';
    }

    // save value

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($linkVideo)) {
      $inputValues['link'] = $linkVideo . 'link';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = explode('#', $tags);
      $inputValues['tags'] = implode('.', $inputValues['tags']);
      $inputValues['tags'] = $inputValues['tags'] . 'tags';
    }

    if (!empty($errors)) {
      $errors = implode(', ', $errors);
      $inputValues = implode(', ', $inputValues);
      header("Location: /?add-post=1&filter=5&errors=$errors&inputValues=$inputValues");
    } else {
      $con = mysqli_connect("localhost", "root", "","readme");
      $result = mysqli_query(connect(), "INSERT INTO posts (post_date, title, content_type, video_link) VALUE (NOW(), '$heading', 'post-video', '$linkVideo')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /?post-id=$id");
    }
  }

  if (isset($_POST['text-heading'])) {
    // text

    // inputs

    $heading = test_input($_POST["text-heading"]);
    $text = test_input($_POST["text-text"]);
    $tags = test_input($_POST["text-tags"]);

    // find errors

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    $resultText = validateHeadingTextAndAuthor($text, 30, 600);

    if ($resultText) {
      $errors[] = $resultText . 'text';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors[] = $resultTags . 'tags';
    }

    // save value

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($text)) {
      $inputValues['text'] = $text . 'text';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = explode('#', $tags);
      $inputValues['tags'] = implode('.', $inputValues['tags']);
      $inputValues['tags'] = $inputValues['tags'] . 'tags';
    }

    if (empty($errors) === false) {
      $errors = implode(', ', $errors);
      $inputValues = implode(', ', $inputValues);
      header("Location: /?add-post=1&filter=2&errors=$errors&inputValues=$inputValues");
    } else {
      $con = mysqli_connect("localhost", "root", "","readme");
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content) VALUE (NOW(), '$heading', 'post-text', '$text')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /?post-id=$id");
    }
  }

  if (isset($_POST['quote-heading'])) {
    // quote

    // inputs
    $heading = test_input($_POST["quote-heading"]);
    $text = test_input($_POST["quote-text"]);
    $author = test_input($_POST["quote-author"]);
    $tags = test_input($_POST["quote-tags"]);

    // find errors

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    $resultText = validateHeadingTextAndAuthor($textm, 30, 600);

    if ($resultText) {
      $errors[] = $resultText . 'text';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors[] = $resultTags . 'tags';
    }

    $resultAuthor = validateHeadingTextAndAuthor($author, 5, 25);

    if ($resultAuthor) {
      $errors[] = $resultAuthor . 'author';
    }

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($text)) {
      $inputValues['text'] = $textm . 'text';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = explode('#', $tags);
      $inputValues['tags'] = implode('.', $inputValues['tags']);
      $inputValues['tags'] = $inputValues['tags'] . 'tags';
    }

    if (!empty($author)) {
      $inputValues['author'] = $author . 'author';
    }

    if (!empty($errors)) {
      $errors = implode(', ', $errors);
      $inputValues = implode(', ', $inputValues);
      header("Location: /?add-post=1&filter=1&errors=$errors&inputValues=$inputValues");
    } else {
      $con = mysqli_connect("localhost", "root", "","readme");
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, text_content, quote_author) VALUE (NOW(), '$heading', 'post-quote', '$text', '$author')");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /?post-id=$id");
    }
  }

  if (isset($_POST['link-heading'])) {
    // link

    //inputs
    $heading = test_input($_POST["link-heading"]);
    $link = test_input($_POST["link-link"]);
    $tags = test_input($_POST["link-tags"]);

    //find errors

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    $resultLink = validateWebLink($link);

    if ($resultLink) {
      $errors[] = $resultLink . 'link';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors[] = $resultTags . 'tags';
    }

    // save value

    if (!empty($heading)) {
      print($heading);
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($link)) {
      $inputValues['link'] = $link . 'link';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = explode('#', $tags);
      $inputValues['tags'] = implode('.', $inputValues['tags']);
      $inputValues['tags'] = $inputValues['tags'] . 'tags';
    }

    if (empty($errors) === false) {
      $errors = implode(', ', $errors);
      $inputValues = implode(', ', $inputValues);
      header("Location: /?add-post=1&filter=4&errors=$errors&inputValues=$inputValues");
    } else {
      $con = mysqli_connect("localhost", "root", "","readme");
      $result = mysqli_query($con, "INSERT INTO posts (post_date, title, content_type, website_link, id_user) VALUE (NOW(), '$heading', 'post-link', '$link', 1)");
      $id = mysqli_insert_id($con);
      if (!empty($tags)) {
        $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
      }
      header("Location: /?post-id=$id");
    }
  }
}
?>
