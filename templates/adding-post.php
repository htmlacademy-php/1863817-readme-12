<?php
// include 'add.php';
if (empty($_GET['errors']) === false) {
  $errors = $_GET['errors'];
  $errors = explode(', ', $errors);

  foreach($errors as $key => $value) {
    $heading = stristr($value, 'heading', true);
    if ($heading) {
      $errorsWithKeys['heading'] = $heading;
    }
    $text = stristr($value, 'text', true);
    if ($text) {
      $errorsWithKeys['text'] = $text;
    }
    $tags = stristr($value, 'tags', true);
    if ($tags) {
      $errorsWithKeys['tags'] = $tags;
    }
    $author = stristr($value, 'author', true);
    if ($author) {
      $errorsWithKeys['author'] = $author;
    }
    $link = stristr($value, 'link', true);
    if ($link) {
      $errorsWithKeys['link'] = $link;
    }
    $photo = stristr($value, 'photo', true);
    if ($photo) {
      $errorsWithKeys['photo'] = $photo;
    }
  }
  echo('<pre>');
  print_r($errorsWithKeys);
  echo('</pre>');
}
if (empty($_GET['inputValues']) === false) {
  $inputValues = $_GET['inputValues'];
  $inputValues = explode(', ', $inputValues);

  foreach($inputValues as $key => $value) {
    $heading = stristr($value, 'heading', true);
    if ($heading) {
      $valuesWithKeys['heading'] = $heading;
    }
    $text = stristr($value, 'text', true);
    if ($text) {
      $valuesWithKeys['text'] = $text;
    }
    $tags = stristr($value, 'tags', true);
    if ($tags) {
      $valuesWithKeys['tags'] = $tags;
    }
    $author = stristr($value, 'author', true);
    if ($author) {
      $valuesWithKeys['author'] = $author;
    }
    $link = stristr($value, 'link', true);
    if ($link) {
      $valuesWithKeys['link'] = $link;
    }
    $photo = stristr($value, 'photo', true);
    if ($photo) {
      $valuesWithKeys['photo'] = $photo;
    }
  }
  echo('<pre>');
  print_r($valuesWithKeys);
  echo('</pre>');
}

echo('<pre>');
print_r($_FILES['userpic-file-photo']);
echo('</pre>');
?>
<div class="page__main page__main--adding-post">
  <div class="page__main-section">
    <div class="container">
      <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>
    <div class="adding-post container">
      <div class="adding-post__tabs-wrapper tabs">
        <div class="adding-post__tabs filters">
          <?php foreach ($types as $key => $type): ?>
          <ul class="adding-post__tabs-list filters__list tabs__list">
            <? if ($type["content_type_title"] == "p"): ?>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button <?= addClass('filter', $type["id_type"], 'tabs__item--active filters__button--active') ?> filters__button--photo tabs__item button" href="/?add-post=1&filter=<?= $type["id_type"]; ?>">
                <svg class="filters__icon" width="22" height="18">
                  <use xlink:href="#icon-filter-<?=$type["content_class_type"]; ?>"></use>
                </svg>
                <span>Фото</span>
              </a>
            </li>
            <? elseif ($type["content_type_title"] == "v"): ?>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--video tabs__item button <?= addClass('filter', $type["id_type"], 'tabs__item--active filters__button--active') ?>" href="/?add-post=1&filter=<?= $type["id_type"]; ?>">
                <svg class="filters__icon" width="24" height="16">
                  <use xlink:href="#icon-filter-<?=$type["content_class_type"]; ?>"></use>
                </svg>
                <span>Видео</span>
              </a>
            </li>
            <? elseif ($type["content_type_title"] == "t"): ?>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--text tabs__item button <?= addClass('filter', $type["id_type"], 'tabs__item--active filters__button--active') ?>" href="/?add-post=1&filter=<?= $type["id_type"]; ?>">
                <svg class="filters__icon" width="20" height="21">
                  <use xlink:href="#icon-filter-<?=$type["content_class_type"]; ?>"></use>
                </svg>
                <span>Текст</span>
              </a>
            </li>
            <? elseif ($type["content_type_title"] == "q"): ?>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--quote tabs__item button <?= addClass('filter', $type["id_type"], 'tabs__item--active filters__button--active') ?>" href="/?add-post=1&filter=<?= $type["id_type"]; ?>">
                <svg class="filters__icon" width="21" height="20">
                  <use xlink:href="#icon-filter-<?=$type["content_class_type"]; ?>"></use>
                </svg>
                <span>Цитата</span>
              </a>
            </li>
            <? elseif ($type["content_type_title"] == "l"): ?>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--link tabs__item butto <?= addClass('filter', $type["id_type"], 'tabs__item--active filters__button--active') ?>" href="/?add-post=1&filter=<?= $type["id_type"]; ?>">
                <svg class="filters__icon" width="21" height="18">
                  <use xlink:href="#icon-filter-<?=$type["content_class_type"]; ?>"></use>
                </svg>
                <span>Ссылка</span>
              </a>
            </li>
          </ul>
          <? endif; ?>
          <? endforeach; ?>
        </div>
        <div class="adding-post__tab-content">
          <!-- photo -->
          <?php if ($_GET['filter'] === '3'): ?>
          <section class="adding-post__photo tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления фото</h2>
            <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                   <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                   <div class="form__input-section">
                     <input class="adding-post__input form__input" id="photo-heading" type="text" name="photo-heading" placeholder="Введите заголовок">
                     <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                     <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['heading'] ?></p>
                      </div>
                   </div>
                 </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['photo']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-link" placeholder="Введите ссылку">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['photo'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['tags']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="photo-tags" type="text" name="photo-tags" placeholder="Введите теги">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <? if (empty($errorsWithKeys) === false): ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <? if (empty($errorsWithKeys['heading']) === false): ?>
                      <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['photo']) === false): ?>
                      <li class="form__invalid-item">Ссылка на видео. <?= $errorsWithKeys['photo'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['tags']) === false): ?>
                      <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                      <? endif; ?>
                    </ul>
                  </div>
                <? endif; ?>
              </div>
              <div class="adding-post__input-file-container form__input-container form__input-container--file">
                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                  <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                    <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
                    <div class="form__file-zone-text">
                      <span>Перетащите фото сюда</span>
                    </div>
                  </div>
                  <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                    <span>Выбрать фото</span>
                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                      <use xlink:href="#icon-attach"></use>
                    </svg>
                  </button>
                </div>
                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                </div>
              </div>
              <?= file_get_contents('templates/blocks/buttons.php'); ?>
            </form>
          </section>
          <!-- video -->
          <?php elseif ($_GET['filter'] === '5'): ?>
          <section class="adding-post__video tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления видео</h2>
            <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="video-heading" type="text" value="<?= $valuesWithKeys['heading']; ?>" name="video-heading" placeholder="Введите заголовок">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['heading'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['link']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="video-url" type="text" value="<?= $valuesWithKeys['link']; ?>" name="video-link" placeholder="Введите ссылку">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['link'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['tags']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="video-tags">Теги</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="video-tags" type="text" value="<?= $valuesWithKeys['tags']; ?>" name="video-tags" placeholder="Введите теги">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <? if (empty($errorsWithKeys) === false): ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <? if (empty($errorsWithKeys['heading']) === false): ?>
                      <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['link']) === false): ?>
                      <li class="form__invalid-item">Ссылка на видео. <?= $errorsWithKeys['link'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['tags']) === false): ?>
                      <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                      <? endif; ?>
                    </ul>
                  </div>
                <? endif; ?>
              </div>
              <?= file_get_contents('templates/blocks/buttons.php'); ?>
            </form>
          </section>
          <!-- text -->
          <?php elseif ($_GET['filter'] === '2' || (empty($_GET['add-post']) === false && empty($_GET['filter']))): ?>
          <section class="adding-post__text tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления текста</h2>
            <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="text-heading" type="text" name="text-heading" value = "<?= getPostVal('text-heading'); ?>" placeholder="Введите заголовок">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['heading'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__textarea-wrapper form__textarea-wrapper <?= empty($errorsWithKeys['text']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="text-text" placeholder="Введите текст публикации"></textarea>
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['text'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['tags']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="post-tags">Теги</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="post-tags" type="text" name="text-tags" placeholder="Введите теги">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <? if (empty($errorsWithKeys) === false): ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <? if (empty($errorsWithKeys['heading']) === false): ?>
                      <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['text']) === false): ?>
                      <li class="form__invalid-item">Текст поста. <?= $errorsWithKeys['text'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['tags']) === false): ?>
                      <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                      <? endif; ?>
                    </ul>
                  </div>
                <? endif; ?>
              </div>
              <?= file_get_contents('templates/blocks/buttons.php'); ?>
            </form>
          </section>
          <!-- quote -->
          <?php elseif ($_GET['filter'] === '1'): ?>
          <section class="adding-post__quote tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления цитаты</h2>
            <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="quote-heading" type="text" name="quote-heading" value="<?= $valuesWithKeys['heading']; ?>" placeholder="Введите заголовок">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['heading'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__textarea-wrapper <?= empty($errorsWithKeys['text']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" name="quote-text" placeholder="Текст цитаты"><?= $valuesWithKeys['text']; ?></textarea>
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['text'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__textarea-wrapper form__input-wrapper <?= empty($errorsWithKeys['author']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="quote-author" type="text" value="<?= $valuesWithKeys['author']; ?>" name="quote-author"placeholder="Автор">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['author'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['tags']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="cite-tags">Теги</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="cite-tags" type="text" name="quote-tags" value="<?= $valuesWithKeys['tags']; ?>" placeholder="Введите теги">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <? if (empty($errorsWithKeys) === false): ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <? if (empty($errorsWithKeys['heading']) === false): ?>
                      <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['text']) === false): ?>
                      <li class="form__invalid-item">Текст поста. <?= $errorsWithKeys['text'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['author']) === false): ?>
                      <li class="form__invalid-item">Автор. <?= $errorsWithKeys['author'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['tags']) === false): ?>
                      <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                      <? endif; ?>
                    </ul>
                  </div>
                <? endif; ?>
              </div>
              <?= file_get_contents('templates/blocks/buttons.php'); ?>
            </form>
          </section>
          <!-- link -->
          <?php elseif ($_GET['filter'] === '4'): ?>
          <section class="adding-post__link tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления ссылки</h2>
            <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="link-heading" type="text" name="link-heading" value="<?= $valuesWithKeys['heading']; ?>" placeholder="Введите заголовок">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['heading'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__textarea-wrapper form__input-wrapper <?= empty($errorsWithKeys['link']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="post-link" type="text" name="link-link" value="<?= $valuesWithKeys['link']; ?>" placeholder="Введите ссылку">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['link'] ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['tags']) === false ? 'form__input-section--error' : null; ?>">
                    <label class="adding-post__label form__label" for="link-tags">Теги</label>
                    <div class="form__input-section">
                      <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tags" value="<?= $valuesWithKeys['tags']; ?>" placeholder="Введите теги">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                        <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <? if (empty($errorsWithKeys) === false): ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <? if (empty($errorsWithKeys['heading']) === false): ?>
                      <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['link']) === false): ?>
                      <li class="form__invalid-item">Ссылка. <?= $errorsWithKeys['link'] ?></li>
                      <? endif; ?>
                      <? if (empty($errorsWithKeys['tags']) === false): ?>
                      <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                      <? endif; ?>
                    </ul>
                  </div>
                <? endif; ?>
              </div>
              <?= file_get_contents('templates/blocks/buttons.php'); ?>
            </form>
          </section>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
