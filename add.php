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
  if (empty($file) && empty($link)) {
    return $textError = 'Хотя бы одно из полей с указанием фотографии должно быть заполненно';
  } else if (!empty($file) && !empty($link)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_name = $file['tmp_name'];
    $file_size = $file['size'];

    $file_type = finfo_file($finfo, $file_name);

    if ($file_type !== 'image/jpeg' || $file_type !== 'image/png' || $file_type !== 'image/gif') {
      return $textError = 'Файд должен быть в одном из трех форматов jpeg/png/gif';
    } else {
      return false;
    }

    if ($file_size > 200000) {
      $textError = 'Максимальный размер файла: 200Кб';
    } else {
      return false;
    }
  } else if (empty($file) && !empty($link)) {
    $flag = filter_var($link, FILTER_VALIDATE_URL);

    if($flag) {
      $result = file_get_contents($flag);
      if ($result) {
        return false;
      } else {
        return $textError = 'При загрузке изображения по ссылке произошла ошибка';
      }
    } else {
      return $textError = 'Введен некорректный адрес ресурса';
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
    $heading = test_input($_POST["photo-heading"]);
    // $userpicFilePhoto = $_FILES["userpic-file-photo"];
    $linkPhoto = test_input($_POST["photo-link"]);
    $tags = test_input($_POST["photo-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors[] = $resultHeading . 'heading';
    }

    // $resultPhoto = validateFileInputAndPhotoLink($userpicFilePhoto, $linkPhoto);

    // if ($resultPhoto) {
    //   $errors[] = $resultPhoto . 'photo';;
    // }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors['tags'] = $resultTags . 'tags';
    }

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($linkPhoto)) {
      $inputValues['link'] = $linkPhoto . 'link';
    }

    // if (isset($userpicFilePhoto)) {
    // $file_name = $userpicFilePhoto['name'];
    // $file_path = __DIR__ . '/uploads/';
    // $file_url = '/uploads/' . $file_name;

    // move_uploaded_file($userpicFilePhoto['tmp_name'], $file_path . $file_name);
    // }

    // clearstatcache();
    if (!empty($_FILES)) {
      echo('<pre>');
      print_r($_FILES);
      echo('</pre>');
      print(1);
    }

    // if (isset($_FILES['userpic-file-photo'])) {
    //   $file_name = $_FILES['userpic-file-photo']['name'];
    //   $file_path = __DIR__ . '/uploads/';
    //   $file_url = '/uploads/' . $file_name;

    //   move_uploaded_file($_FILES['userpic-file-photo']['tmp_name'], $file_path . $file_name);
    //   echo('<pre>');
    //   print_r($_FILES['userpic-file-photo']);
    //   echo('</pre>');
    // }

    // if (!empty($userpicFilePhoto)) {
    //   $inputValues['photo'] = $userpicFilePhoto . 'photo';
    // }

    if (!empty($tags)) {
      $inputValues['tags'] = $tags . 'tags';
    }

    // if (!empty($errors)) {

    //   $errors = implode(', ', $errors);
    //   if (!empty($inputValues)) {
    //     $inputValues = implode(', ', $inputValues);
    //   }
    //   // header("Location: /?add-post=1&filter=3&errors=$errors&inputValues=$inputValues");
    // } else {
    //   if (empty($userpicFilePhoto)) {
    //     $photo = $linkPhoto;
    //   } else {
    //     $photo = $userpicFilePhoto;
    //   }

    //   $con = mysqli_connect("localhost", "root", "","readme");
    //   $result = mysqli_query(connect(), "INSERT INTO posts (post_date, title, content_type, image_link) VALUE (NOW(), '$heading', 'post-photo', '$photo')");
    //   $id = mysqli_insert_id($con);
    //   if (!empty($tags)) {
    //     $tagResult = mysqli_query($con, "INSERT INTO hashtags (id_post, hashtag_title) VALUE ($id, '$tags')");
    //   }
    //   header("Location: /?post-id=$id");
    // }

  }

  if (isset($_POST['video-heading'])) {
    // video
    $heading = test_input($_POST["video-heading"]);
    $linkVideo = test_input($_POST["video-link"]);
    $tags = test_input($_POST["video-tags"]);

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
      $errors['tags'] = $resultTags . 'tags';
    }

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($linkVideo)) {
      $inputValues['link'] = $linkVideo . 'link';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = $tags . 'tags';
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
    $heading = test_input($_POST["text-heading"]);
    $text = test_input($_POST["text-text"]);
    $tags = test_input($_POST["text-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors['heading'] = $resultHeading . 'heading';
    }

    $resultText = validateHeadingTextAndAuthor($text, 30, 600);

    if ($resultText) {
      $errors['text'] = $resultText . 'text';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors['tags'] = $resultTags . 'tags';
    }

    if (!empty($heading)) {
      $inputValues['heading'] = $heading . 'heading';
    }

    if (!empty($text)) {
      $inputValues['text'] = $text . 'text';
    }

    if (!empty($tags)) {
      $inputValues['tags'] = $tags . 'tags';
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
    $heading = test_input($_POST["quote-heading"]);
    $text = test_input($_POST["quote-text"]);
    $author = test_input($_POST["quote-author"]);
    $tags = test_input($_POST["quote-tags"]);

    $resultHeading = validateHeadingTextAndAuthor($heading, 5, 20);

    if ($resultHeading) {
      $errors['heading'] = $resultHeading . 'heading';
    }

    $resultText = validateHeadingTextAndAuthor($textm, 30, 600);

    if ($resultText) {
      $errors['text'] = $resultText . 'text';
    }

    $resultTags = validateTags($tags);

    if ($resultTags) {
      $errors['tags'] = $resultTags . 'tags';
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
      $inputValues['tags'] = $tags . 'tags';
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
    $heading = test_input($_POST["link-heading"]);
    $link = test_input($_POST["link-link"]);
    $tags = test_input($_POST["link-tags"]);

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
      $errors['tags'] = $resultTags . 'tags';
    }

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
