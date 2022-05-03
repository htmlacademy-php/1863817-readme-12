<?php

if (empty($_GET['errors']) === false) {
  $errors = $_GET['errors'];
  $errors = explode('join', $errors);

  foreach($errors as $key => $value) {
    $email = stristr($value, 'email', true);
    if ($email) {
      $errorsWithKeys['email'] = $email;
    }
    $login = stristr($value, 'login', true);
    if ($login) {
      $errorsWithKeys['login'] = $login;
    }
    $password = stristr($value, 'password', true);
    if ($password) {
      $passwordErrorsArray = array_filter(explode('separator', $password));
    }
    $passwordRepeat = stristr($value, 'repeat', true);
    if ($passwordRepeat) {
      $errorsWithKeys['passwordRepeat'] = $passwordRepeat;
    }
    $photo = stristr($value, 'photo', true);
    if ($photo) {
      $errorsWithKeys['photo'] = $photo;
    }
  }
}

// echo('<pre>');
// print_r($errors);
// echo('</pre>');

?>
<div class="page__main page__main--registration">
  <div class="container">
    <h1 class="page__title page__title--registration">Регистрация</h1>
  </div>
  <section class="registration container">
    <h2 class="visually-hidden">Форма регистрации</h2>
    <form class="registration__form form" action="registration.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($errorsWithKeys['email']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-email" type="email" name="email" value="<?= $_GET['email']; ?>" placeholder="Укажите эл.почту">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $errorsWithKeys['email'] ?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($errorsWithKeys['login']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-login" type="text" name="login" value="<?= $_GET['login']; ?>" placeholder="Укажите логин">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $errorsWithKeys['login'] ?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($passwordErrorsArray) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-password" type="password" name="password" value="<?= $_GET['login']; ?>" placeholder="Придумайте пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $passwordErrorsArray[0] ?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($errorsWithKeys['passwordRepeat']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля<span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-password-repeat" type="password" value="<?= $_GET['login']; ?>" name="password-repeat" placeholder="Повторите пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $errorsWithKeys['passwordRepeat'] ?></p>
              </div>
            </div>
          </div>
        </div>
        <? if (!empty($errorsWithKeys)): ?>
          <div class="form__invalid-block">
            <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
            <ul class="form__invalid-list">
              <? if (!empty($errorsWithKeys['email'])): ?>
              <li class="form__invalid-item">Электронная почта. <br> <?= $errorsWithKeys['email'] ?></li>
              <? endif; ?>
              <? if (!empty($errorsWithKeys['login'])): ?>
              <li class="form__invalid-item">Логин. <br> <?= $errorsWithKeys['login'] ?></li>
              <? endif; ?>
              <? if (!empty($passwordErrorsArray)): ?>
                <? if (count($passwordErrorsArray) === 1): ?>
                  <li class="form__invalid-item">Пароль. <br> <?= $passwordErrorsArray[0]; ?></li>
                <? else: ?>
                    <li class="form__invalid-item">Пароль.
                    <? foreach($passwordErrorsArray as $key => $error): ?>
                      <p>
                      <?= $error . '<br>'?>
                      </p>
                    <? endforeach; ?>
                    </li>
                <? endif; ?>
              <? endif; ?>
              <? if (!empty($errorsWithKeys['passwordRepeat'])): ?>
              <li class="form__invalid-item">Повтор пароля. <br> <?= $errorsWithKeys['passwordRepeat'] ?></li>
              <? endif; ?>
              <? if (!empty($errorsWithKeys['photo'])): ?>
              <li class="form__invalid-item">Фото. <br> <?= $errorsWithKeys['photo'] ?></li>
              <? endif; ?>
            </ul>
          </div>
        <? endif; ?>
      </div>
      <div class="adding-post__input-file-container form__input-container form__input-container--file">
        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
          <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone">
            <div class="form__file-zone-text">
              <? if (!empty($_GET['photo'])): ?>
              <img class="preview__photo" src="<?= $_GET['photo']; ?>" width="100" height="100" alt="Загруженное пользователем фото.">
              <? else: ?>
              <img class="preview__photo" src="img/drag-and-drop.svg" width="43" height="43" alt="Загруженное пользователем фото.">
              <? endif; ?>
              <span>Превью для загрузки файла</span>
            </div>
          </div>
          <div class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button">
            <input class='visually-hidden' id='link-download-if-reload' name='link-download-if-reload' value='<?= $_GET['photo']; ?>' type='text'>
            <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo">
            <span>Выбрать фото</span>
            <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
              <use xlink:href="#icon-attach"></use>
            </svg>
          </div>
        </div>
        <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
        </div>
      </div>
      <button class="registration__submit button button--main" type="submit">Отправить</button>
    </form>
  </section>
</div>
