<div class="page__main page__main--feed">
  <div class="container">
    <h1 class="page__title page__title--feed">Моя лента</h1>
  </div>
  <div class="page__main-wrapper container">
    <section class="feed">
      <h2 class="visually-hidden">Лента</h2>
      <div class="feed__main-wrapper">
        <div class="feed__wrapper">

          <? if (isset($posts)) : ?>
            <? foreach ($posts as $key => $post) : ?>

              <article class="feed__post post <?= $post['content_type']; ?>">
                <header class="post__header post__author">
                  <a class="post__author-link" href="profile.php?id=<?= $post['id_user']; ?>&active=posts" title="Автор">
                    <div class="post__avatar-wrapper">
                      <img class="post__author-avatar" src="<?= $post['avatar_link']; ?>" alt="<?= !empty($post["avatar_link"]) ? 'Аватар пользователя.' : ''; ?>" width="60" height="60">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name"><?= $post["user_login"]; ?></b>
                      <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($post["post_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($post["post_date"])); ?>">
                        <? if (empty(createTextForDate($dataForDatatime))) : ?>
                          только что
                        <? else : ?>
                          <?= createTextForDate($dataForDatatime); ?> назад
                        <? endif; ?>
                      </time>
                    </div>
                  </a>
                </header>
                <div class="post__main">
                  <?php if ($post["content_type"] == "post-quote") : ?>
                    <blockquote>
                      <p>
                        <?= $post["text_content"]; ?>
                      </p>
                      <cite>Неизвестный Автор</cite>
                    </blockquote>
                  <?php elseif ($post["content_type"] == "post-text") :
                    list($newString, $cut) = addLinkForBigText($post["text_content"]); ?>
                    <p>
                      <?= $newString; ?>
                    </p>
                    <?php if ($cut) : ?>
                      <div class="post-text__more-link-wrapper">
                        <a class="post-text__more-link" href="#">Читать далее</a>
                      </div>
                    <?php endif; ?>
                  <?php elseif ($post["content_type"] == "post-photo") : ?>
                    <div class="post-photo__image-wrapper">
                      <img src="<?= $post["image_link"]; ?>" alt="Фото от пользователя" width="360" height="240">
                    </div>
                  <?php elseif ($post["content_type"] == "post-video") : ?>
                    <div class="post-video__block">
                      <div class="post-video__preview">
                        <a href='<?= $post["video_link"]; ?>'>
                          <?= embed_youtube_cover($post["video_link"], 320, 120); ?>
                        </a>
                      </div>
                      <a href="post-details.html" class="post-video__play-big button">
                        <svg class="post-video__play-big-icon" width="14" height="14">
                          <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                      </a>
                    </div>
                  <?php elseif ($post["content_type"] == "post-link") : ?>
                    <div class="post-link__wrapper">
                      <a class="post-link__external" href="http://<?= $post["website_link"]; ?>" title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                          <div class="post-link__icon-wrapper">
                            <img src="https://www.google.com/s2/favicons?domain=<?= $post["website_link"]; ?>" alt="Иконка">
                          </div>
                          <div class="post-link__info">
                            <h3><?= $post["title"]; ?></h3>
                          </div>
                        </div>
                        <span><?= $post["website_link"]; ?></span>
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
                <footer class="post__footer post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="/likes.php?postId=<?= $post["id_post"]; ?>&amilike=<?= $post['amILikeThisPost'] ? 'yes' : 'no'; ?>" title="Лайк">
                      <svg class="post__indicator-icon <?= $post['amILikeThisPost'] ? 'my-like' : ''; ?>" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                      </svg>
                      <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                      </svg>
                      <span><?= $post["likes_count"]; ?></span>
                      <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button" href="/post.php?post-id=<?= $post["id_post"]; ?>" title="Комментарии">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                      </svg>
                      <span><?= $post["comments_count"]; ?></span>
                      <span class="visually-hidden">количество комментариев</span>
                    </a>
                    <a class="post__indicator post__indicator--repost button" href="/repost.php?id_post=<?= $post["id_post"]; ?>" title="Репост">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                      </svg>
                      <span><?= $post["reposts_count"]; ?></span>
                      <span class="visually-hidden">количество репостов</span>
                    </a>
                  </div>
                </footer>
              </article>
            <? endforeach; ?>
          <? endif; ?>
        </div>
      </div>
      <ul class="feed__filters filters">
        <li class="feed__filters-item filters__item">
          <a class="filters__button button <?= $_GET['filter'] === 'all' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=all">
            <span>Все</span>
          </a>
        </li>
        <li class="feed__filters-item filters__item">
          <a class="filters__button filters__button--photo button <?= $_GET['filter'] === 'photo' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=photo">
            <span class="visually-hidden">Фото</span>
            <svg class="filters__icon" width="22" height="18">
              <use xlink:href="#icon-filter-photo"></use>
            </svg>
          </a>
        </li>
        <li class="feed__filters-item filters__item">
          <a class="filters__button filters__button--video button <?= $_GET['filter'] === 'video' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=video">
            <span class="visually-hidden">Видео</span>
            <svg class="filters__icon" width="24" height="16">
              <use xlink:href="#icon-filter-video"></use>
            </svg>
          </a>
        </li>
        <li class="feed__filters-item filters__item">
          <a class="filters__button filters__button--text button <?= $_GET['filter'] === 'text' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=text">
            <span class="visually-hidden">Текст</span>
            <svg class="filters__icon" width="20" height="21">
              <use xlink:href="#icon-filter-text"></use>
            </svg>
          </a>
        </li>
        <li class="feed__filters-item filters__item">
          <a class="filters__button filters__button--quote button <?= $_GET['filter'] === 'quote' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=quote">
            <span class="visually-hidden">Цитата</span>
            <svg class="filters__icon" width="21" height="20">
              <use xlink:href="#icon-filter-quote"></use>
            </svg>
          </a>
        </li>
        <li class="feed__filters-item filters__item">
          <a class="filters__button filters__button--link button <?= $_GET['filter'] === 'link' ? 'filters__button--active' : '' ?>" href="/feed.php?filter=link">
            <span class="visually-hidden">Ссылка</span>
            <svg class="filters__icon" width="21" height="18">
              <use xlink:href="#icon-filter-link"></use>
            </svg>
          </a>
        </li>
      </ul>
    </section>
    <aside class="promo">
      <article class="promo__block promo__block--barbershop">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
          Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
        </p>
        <a class="promo__link" href="#">
          Подробнее
        </a>
      </article>
      <article class="promo__block promo__block--technomart">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
          Товары будущего уже сегодня в онлайн-сторе Техномарт!
        </p>
        <a class="promo__link" href="#">
          Перейти в магазин
        </a>
      </article>
      <article class="promo__block">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
          Здесь<br> могла быть<br> ваша реклама
        </p>
        <a class="promo__link" href="#">
          Разместить
        </a>
      </article>
    </aside>
  </div>
</div>
