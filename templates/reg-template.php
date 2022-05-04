<div class="page__main page__main--registration">
  <div class="container">
    <h1 class="page__title page__title--registration">Регистрация</h1>
  </div>
  <section class="registration container">
    <h2 class="visually-hidden">Форма регистрации</h2>
    <form class="registration__form form" action="registration.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($_GET['resultEmail']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-email" type="email" name="email" value="<?= $_GET['email']; ?>" placeholder="Укажите эл.почту">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $_GET['resultEmail'] ?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($_GET['resultLogin']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-login" type="text" name="login" value="<?= $_GET['login']; ?>" placeholder="Укажите логин">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $_GET['resultLogin'] ?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($_GET['resultPassword']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-password" type="password" name="password" value="<?= $_GET['password']; ?>" placeholder="Придумайте пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $_GET['resultPassword']; ?></p>
              </div>
              <button class="registration__show-pass registration__show-pass--norepeat" type="button"></button>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper <?= !empty($_GET['resultRepeatPassword']) ? 'form__input-section--error' : null; ?>">
            <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля<span class="form__input-required">*</span></label>
            <div class="form__input-section">
              <input class="registration__input form__input" id="registration-password-repeat" type="password" value="<?= $_GET['password-repeat']; ?>" name="password-repeat" placeholder="Повторите пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                <p class="form__error-desc"><?= $_GET['resultRepeatPassword'] ?></p>
              </div>
              <button class="registration__show-pass registration__show-pass--repeat" type="button"></button>
            </div>
          </div>
        </div>
        <? if (count($_GET) > 1) : ?>
          <div class="form__invalid-block">
            <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
            <ul class="form__invalid-list">
              <? if (!empty($_GET['resultEmail'])) : ?>
                <li class="form__invalid-item">Электронная почта. <br> <?= $_GET['resultEmail'] ?></li>
              <? endif; ?>
              <? if (!empty($_GET['resultLogin'])) : ?>
                <li class="form__invalid-item">Логин. <br> <?= $_GET['resultLogin'] ?></li>
              <? endif; ?>
              <? if (!empty($_GET['resultPassword'])) : ?>
                <li class="form__invalid-item">Пароль. <br> <?= $_GET['resultPassword'] ?></li>
              <? endif; ?>
              <? if (!empty($_GET['resultRepeatPassword'])) : ?>
                <li class="form__invalid-item">Повтор пароля. <br> <?= $_GET['resultRepeatPassword'] ?></li>
              <? endif; ?>
              <? if (!empty($_GET['resultFile'])) : ?>
                <li class="form__invalid-item">Фото. <br> <?= $_GET['resultFile'] ?></li>
              <? endif; ?>
            </ul>
          </div>
        <? endif; ?>
      </div>
      <div class="adding-post__input-file-container form__input-container form__input-container--file">
        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
          <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone">
            <div class="form__file-zone-text">
              <? if (!empty($_GET['photo'])) : ?>
                <img class="preview__photo" src="<?= $_GET['photo']; ?>" width="100" height="100" alt="Загруженное пользователем фото.">
              <? else : ?>
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
