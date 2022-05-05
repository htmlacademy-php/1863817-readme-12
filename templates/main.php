<section class="page__main page__main--popular">
  <div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
  </div>
  <div class="popular container">
    <div class="popular__filters-wrapper">
      <div class="popular__sorting sorting">
        <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
        <ul class="popular__sorting-list sorting__list">
          <li class="sorting__item sorting__item--popular">
            <a class="sorting__link sorting__link--active" href="#">
              <span>Популярность</span>
              <svg class="sorting__icon" width="10" height="12">
                <use xlink:href="#icon-sort"></use>
              </svg>
            </a>
          </li>
          <li class="sorting__item">
            <a class="sorting__link" href="#">
              <span>Лайки</span>
              <svg class="sorting__icon" width="10" height="12">
                <use xlink:href="#icon-sort"></use>
              </svg>
            </a>
          </li>
          <li class="sorting__item">
            <a class="sorting__link" href="#">
              <span>Дата</span>
              <svg class="sorting__icon" width="10" height="12">
                <use xlink:href="#icon-sort"></use>
              </svg>
            </a>
          </li>
        </ul>
      </div>
      <?php if ($types && is_array($types)) : ?>
        <div class="popular__filters filters">
          <b class="popular__filters-caption filters__caption">Тип контента:</b>
          <ul class="popular__filters-list filters__list">
            <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
              <a class="filters__button filters__button--ellipse filters__button--all <?= !$_GET['post'] ? 'filters__button--active' : '' ?>" href="http://readme">
                <span>Все</span>
              </a>
            </li>
            <?php foreach ($types as $key => $type) : ?>
              <? if ($type["content_type_title"] == "p") : ?>
                <li class="popular__filters-item filters__item">
                  <a class="filters__button filters__button--photo button <?= addClass('post', $type["id_type"], 'filters__button--active') ?>" href="/?post=<?= $type["id_type"]; ?>">
                    <span class="visually-hidden">Фото</span>
                    <svg class="filters__icon" width="22" height="18">
                      <use xlink:href="#icon-filter-<?= $type["content_class_type"]; ?>"></use>
                    </svg>
                  </a>
                </li>
              <? elseif ($type["content_type_title"] == "v") : ?>
                <li class="popular__filters-item filters__item">
                  <a class="filters__button filters__button--video button <?= addClass('post', $type["id_type"], 'filters__button--active') ?>" href="/?post=<?= $type["id_type"]; ?>">
                    <span class="visually-hidden">Видео</span>
                    <svg class="filters__icon" width="24" height="16">
                      <use xlink:href="#icon-filter-<?= $type["content_class_type"]; ?>"></use>
                    </svg>
                  </a>
                </li>
              <? elseif ($type["content_type_title"] == "t") : ?>
                <li class="popular__filters-item filters__item">
                  <a class="filters__button filters__button--text button <?= addClass('post', $type["id_type"], 'filters__button--active') ?>" href="/?post=<?= $type["id_type"]; ?>">
                    <span class="visually-hidden">Текст</span>
                    <svg class="filters__icon" width="20" height="21">
                      <use xlink:href="#icon-filter-<?= $type["content_class_type"]; ?>"></use>
                    </svg>
                  </a>
                </li>
              <? elseif ($type["content_type_title"] == "q") : ?>
                <li class="popular__filters-item filters__item">
                  <a class="filters__button filters__button--quote button <?= addClass('post', $type["id_type"], 'filters__button--active') ?>" href="/?post=<?= $type["id_type"]; ?>">
                    <span class="visually-hidden">Цитата</span>
                    <svg class="filters__icon" width="21" height="20">
                      <use xlink:href="#icon-filter-<?= $type["content_class_type"]; ?>"></use>
                    </svg>
                  </a>
                </li>
              <? elseif ($type["content_type_title"] == "l") : ?>
                <li class="popular__filters-item filters__item">
                  <a class="filters__button filters__button--link button <?= addClass('post', $type["id_type"], 'filters__button--active') ?>" href="/?post=<?= $type["id_type"]; ?>">
                    <span class="visually-hidden">Ссылка</span>
                    <svg class="filters__icon" width="21" height="18">
                      <use xlink:href="#icon-filter-<?= $type["content_class_type"]; ?>"></use>
                    </svg>
                  </a>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
    <div class="popular__posts">
      <?php if ($cards && is_array($cards)) : ?>
        <?php foreach ($cards as $key => $card) : ?>
          <article class="popular__post post <?= $card["content_type"]; ?>">
            <header class="post__header">
              <a href='/post.php?post-id=<?= $card["id_post"]; ?>'>
                <h2><?= $card["title"]; ?></h2>
              </a>
            </header>
            <div class="post__main">
              <?php if ($card["content_type"] == "post-quote") : ?>
                <blockquote>
                  <p>
                    <?= $card["text_content"]; ?>
                  </p>
                  <cite>Неизвестный Автор</cite>
                </blockquote>
              <?php elseif ($card["content_type"] == "post-text") :
                list($newString, $cut) = addLinkForBigText($card["text_content"]); ?>
                <p>
                  <?= $newString; ?>
                </p>
                <?php if ($cut) : ?>
                  <div class="post-text__more-link-wrapper">
                    <a class="post-text__more-link" href="#">Читать далее</a>
                  </div>
                <?php endif; ?>
              <?php elseif ($card["content_type"] == "post-photo") : ?>
                <div class="post-photo__image-wrapper">
                  <img src="<?= $card["image_link"]; ?>" alt="Фото от пользователя" width="360" height="240">
                </div>
              <?php elseif ($card["content_type"] == "post-video") : ?>
                <div class="post-video__block">
                  <div class="post-video__preview">
                    <a href='<?= $card["video_link"]; ?>'>
                      <?= embed_youtube_cover($card["video_link"]); ?>
                    </a>
                  </div>
                  <a href="post-details.html" class="post-video__play-big button">
                    <svg class="post-video__play-big-icon" width="14" height="14">
                      <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    <span class="visually-hidden">Запустить проигрыватель</span>
                  </a>
                </div>
              <?php elseif ($card["content_type"] == "post-link") : ?>
                <div class="post-link__wrapper">
                  <a class="post-link__external" href="http://<?= $card["website_link"]; ?>" title="Перейти по ссылке">
                    <div class="post-link__info-wrapper">
                      <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=<?= $card["website_link"]; ?>" alt="Иконка">
                      </div>
                      <div class="post-link__info">
                        <h3><?= $card["title"]; ?></h3>
                      </div>
                    </div>
                    <span><?= $card["website_link"]; ?></span>
                  </a>
                </div>
              <?php endif; ?>
            </div>
            <footer class="post__footer">
              <div class="post__author">
                <a class="post__author-link" href="#" title="Автор">
                  <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="img/<?= $card["avatar_link"]; ?>" alt="Аватар пользователя" width="40" height="40">
                  </div>
                  <div class="post__info">
                    <b class="post__author-name"><?= $card["user_login"]; ?></b>
                    <time class="post__time" datetime="<?= $dataForDatatime = generate_random_date($key) ?>" title="<?= strftime("%d.%m.%Y %H:%M", strtotime($dataForDatatime)); ?>"><?= createTextForDate($dataForDatatime); ?></time>
                  </div>
                </a>
              </div>
              <div class="post__indicators">
                <div class="post__buttons">
                  <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                      <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                      <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span>0</span>
                    <span class="visually-hidden">количество лайков</span>
                  </a>
                  <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                      <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span>0</span>
                    <span class="visually-hidden">количество комментариев</span>
                  </a>
                </div>
              </div>
            </footer>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
