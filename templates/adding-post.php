<div class="page__main page__main--adding-post">
  <div class="page__main-section">
    <div class="container">
      <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>
    <div class="adding-post container">
      <div class="adding-post__tabs-wrapper tabs">
        <div class="adding-post__tabs filters">
          <ul class="adding-post__tabs-list filters__list tabs__list">
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--photo tabs__item button <?= $_GET['filter'] === 'photo' ? 'tabs__item--active filters__button--active' : ''; ?>" href="/add.php?filter=photo">
                <svg class="filters__icon" width="22" height="18">
                  <use xlink:href="#icon-filter-photo"></use>
                </svg>
                <span>Фото</span>
              </a>
            </li>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--video tabs__item button <?= $_GET['filter'] === 'video' ? 'tabs__item--active filters__button--active' : ''; ?>" href="/add.php?filter=video">
                <svg class="filters__icon" width="24" height="16">
                  <use xlink:href="#icon-filter-video"></use>
                </svg>
                <span>Видео</span>
              </a>
            </li>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--text tabs__item button <?= $_GET['filter'] === 'text' ? 'tabs__item--active filters__button--active' : ''; ?>" href="/add.php?filter=text">
                <svg class="filters__icon" width="20" height="21">
                  <use xlink:href="#icon-filter-text"></use>
                </svg>
                <span>Текст</span>
              </a>
            </li>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--quote tabs__item button <?= $_GET['filter'] === 'quote' ? 'tabs__item--active filters__button--active' : ''; ?>" href="/add.php?filter=quote">
                <svg class="filters__icon" width="21" height="20">
                  <use xlink:href="#icon-filter-quote"></use>
                </svg>
                <span>Цитата</span>
              </a>
            </li>
            <li class="adding-post__tabs-item filters__item">
              <a class="adding-post__tabs-link filters__button filters__button--link tabs__item button <?= $_GET['filter'] === 'link' ? 'tabs__item--active filters__button--active' : ''; ?>" href="/add.php?filter=link">
                <svg class="filters__icon" width="21" height="18">
                  <use xlink:href="#icon-filter-link"></use>
                </svg>
                <span>Ссылка</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="adding-post__tab-content">
          <!-- photo -->
          <?php if ($_GET['filter'] === 'photo') : ?>
            <section class="adding-post__photo tabs__content tabs__content--active">
              <h2 class="visually-hidden">Форма добавления фото</h2>
              <form class="adding-post__form form dropzone" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form__text-inputs-wrapper">
                  <div class="form__text-inputs">
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultHeading']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="photo-heading" type="text" name="photo-heading" value="<?= isset($_GET['photo-heading']) ? $_GET['photo-heading'] : ''; ?>" placeholder="Введите заголовок">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultHeading'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultLink']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-link" value="<?= isset($_GET['photo-link']) ? $_GET['photo-link'] : ''; ?>" placeholder="Введите ссылку">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultLink'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultTags']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="photo-tags" type="text" name="photo-tags" value="<?= isset($_GET['photo-tags']) ? $_GET['photo-tags'] : ''; ?>" placeholder="Введите теги">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultTags'] ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <? if (count($_GET) > 1) : ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <? if (!empty($_GET['resultHeading'])) : ?>
                          <li class="form__invalid-item">Заголовок. <?= $_GET['resultHeading']; ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultFile'])) : ?>
                          <li class="form__invalid-item">Добавление файла. <?= $_GET['resultFile']; ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultLink'])) : ?>
                          <li class="form__invalid-item">Фотография. <?= $_GET['resultLink']; ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultTags'])) : ?>
                          <li class="form__invalid-item">Теги. <?= $_GET['resultTags']; ?></li>
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
                <div class="adding-post__buttons">
                  <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                  <a class="adding-post__close" href="#">Закрыть</a>
                </div>
              </form>
            </section>
            <!-- video -->
          <?php elseif ($_GET['filter'] === 'video') : ?>
            <section class="adding-post__video tabs__content tabs__content--active">
              <h2 class="visually-hidden">Форма добавления видео</h2>
              <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form__text-inputs-wrapper">
                  <div class="form__text-inputs">
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultHeading']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="video-heading" type="text" value="<?= isset($_GET['video-heading']) ? $_GET['video-heading'] : ''; ?>" name="video-heading" placeholder="Введите заголовок">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultHeading'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultLink']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="video-url" type="text" value="<?= isset($_GET['video-link']) ? $_GET['video-link'] : ''; ?>" name="video-link" placeholder="Введите ссылку">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultLink'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultTags']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="video-tags">Теги</label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="video-tags" type="text" value="<?= isset($_GET['video-tags']) ? $_GET['video-tags'] : ''; ?>" name="video-tags" placeholder="Введите теги">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultTags'] ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <? if (count($_GET) > 1) : ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <? if (!empty($_GET['resultHeading'])) : ?>
                          <li class="form__invalid-item">Заголовок. <?= $_GET['resultHeading'] ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultLink'])) : ?>
                          <li class="form__invalid-item">Ссылка на видео. <?= $_GET['resultLink'] ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultTags'])) : ?>
                          <li class="form__invalid-item">Теги. <?= $_GET['resultTags'] ?></li>
                        <? endif; ?>
                      </ul>
                    </div>
                  <? endif; ?>
                </div>
                <div class="adding-post__buttons">
                  <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                  <a class="adding-post__close" href="#">Закрыть</a>
                </div>
              </form>
            </section>
            <!-- text -->
          <?php elseif ($_GET['filter'] === 'text') : ?>
            <section class="adding-post__text tabs__content tabs__content--active">
              <h2 class="visually-hidden">Форма добавления текста</h2>
              <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form__text-inputs-wrapper">
                  <div class="form__text-inputs">
                    <div class="adding-post__input-wrapper form__input-wrapper <?= empty($_GET['resultHeading']) === false ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="text-heading" value="<?= isset($_GET['text-heading']) ? $_GET['text-heading'] : ''; ?>" type="text" name="text-heading" placeholder="Введите заголовок">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultHeading'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__textarea-wrapper form__textarea-wrapper <?= empty($_GET['resultText']) === false ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="text-text" placeholder="Введите текст публикации"><?= isset($_GET['text-text']) ? $_GET['text-text'] : ''; ?></textarea>
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultText'] ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= empty($_GET['resultTags']) === false ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="text-tags">Теги</label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="text-tags" type="text" value="<?= isset($_GET['text-tags']) ? $_GET['text-tags'] : ''; ?>" name="text-tags" placeholder="Введите теги">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultTags'] ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <? if (count($_GET) > 1) : ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <? if (!empty($_GET['resultHeading'])) : ?>
                          <li class="form__invalid-item">Заголовок. <?= $_GET['resultHeading']; ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultText'])) : ?>
                          <li class="form__invalid-item">Текст поста. <?= $_GET['resultText']; ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultTags'])) : ?>
                          <li class="form__invalid-item">Теги. <?= $_GET['resultTags']; ?></li>
                        <? endif; ?>
                      </ul>
                    </div>
                  <? endif; ?>
                </div>
                <div class="adding-post__buttons">
                  <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                  <a class="adding-post__close" href="#">Закрыть</a>
                </div>
              </form>
            </section>
            <!-- quote -->
          <?php elseif ($_GET['filter'] === 'quote') : ?>
            <section class="adding-post__quote tabs__content tabs__content--active">
              <h2 class="visually-hidden">Форма добавления цитаты</h2>
              <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form__text-inputs-wrapper">
                  <div class="form__text-inputs">
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultHeading']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="quote-heading" type="text" name="quote-heading" value="<?= isset($_GET['quote-heading']) ? $_GET['quote-heading'] : ''; ?>" placeholder="Введите заголовок">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultHeading']; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__textarea-wrapper <?= !empty($_GET['resultText']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" name="quote-text" placeholder="Текст цитаты"><?= isset($_GET['quote-text']) ? $_GET['quote-text'] : ''; ?></textarea>
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultText']; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__textarea-wrapper form__input-wrapper <?= !empty($_GET['resultAuthor']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="quote-author" type="text" value="<?= isset($_GET['quote-author']) ? $_GET['quote-author'] : ''; ?>" name="quote-author" placeholder="Автор">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultAuthor']; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="adding-post__input-wrapper form__input-wrapper <?= !empty($_GET['resultTags']) ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="cite-tags">Теги</label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="cite-tags" type="text" name="quote-tags" value="<?= isset($_GET['quote-tags']) ? $_GET['quote-tags'] : ''; ?>" placeholder="Введите теги">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $_GET['resultTags']; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <? if (count($_GET) > 1) : ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <? if (!empty($_GET['resultHeading'])) : ?>
                          <li class="form__invalid-item">Заголовок. <?= $_GET['resultHeading'] ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultText'])) : ?>
                          <li class="form__invalid-item">Текст поста. <?= $_GET['resultText'] ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultAuthor'])) : ?>
                          <li class="form__invalid-item">Автор. <?= $_GET['resultAuthor'] ?></li>
                        <? endif; ?>
                        <? if (!empty($_GET['resultTags'])) : ?>
                          <li class="form__invalid-item">Теги. <?= $_GET['resultTags'] ?></li>
                        <? endif; ?>
                      </ul>
                    </div>
                  <? endif; ?>
                </div>
                <div class="adding-post__buttons">
                  <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                  <a class="adding-post__close" href="#">Закрыть</a>
                </div>
              </form>
            </section>
            <!-- link -->
          <?php elseif ($_GET['filter'] === 'link') : ?>
            <section class="adding-post__link tabs__content tabs__content--active">
              <h2 class="visually-hidden">Форма добавления ссылки</h2>
              <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form__text-inputs-wrapper">
                  <div class="form__text-inputs">
                    <div class="adding-post__input-wrapper form__input-wrapper <?= empty($errorsWithKeys['heading']) === false ? 'form__input-section--error' : null; ?>">
                      <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
                      <div class="form__input-section">
                        <input class="adding-post__input form__input" id="link-heading" type="text" name="link-heading" value="<?= isset($_GET['link-heading']) ? $_GET['link-heading'] : ''; ?>" placeholder="Введите заголовок">
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
                        <input class="adding-post__input form__input" id="post-link" type="text" name="link-link" value="<?= isset($_GET['link-link']) ? $_GET['link-link'] : ''; ?>" placeholder="Введите ссылку">
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
                        <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tags" value="<?= isset($_GET['link-tags']) ? $_GET['link-tags'] : ''; ?>" placeholder="Введите теги">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Ошибка при заполнении поля</h3>
                          <p class="form__error-desc"><?= $errorsWithKeys['tags'] ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <? if (empty($errorsWithKeys) === false) : ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <? if (empty($errorsWithKeys['heading']) === false) : ?>
                          <li class="form__invalid-item">Заголовок. <?= $errorsWithKeys['heading'] ?></li>
                        <? endif; ?>
                        <? if (empty($errorsWithKeys['link']) === false) : ?>
                          <li class="form__invalid-item">Ссылка. <?= $errorsWithKeys['link'] ?></li>
                        <? endif; ?>
                        <? if (empty($errorsWithKeys['tags']) === false) : ?>
                          <li class="form__invalid-item">Теги. <?= $errorsWithKeys['tags'] ?></li>
                        <? endif; ?>
                      </ul>
                    </div>
                  <? endif; ?>
                </div>
                <div class="adding-post__buttons">
                  <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                  <a class="adding-post__close" href="#">Закрыть</a>
                </div>
              </form>
            </section>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
