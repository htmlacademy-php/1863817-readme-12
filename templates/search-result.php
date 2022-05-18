<div class="page__main page__main--search-results">
  <h1 class="visually-hidden">Страница результатов поиска</h1>
  <section class="search">
    <h2 class="visually-hidden">Результаты поиска</h2>
    <div class="search__query-wrapper">
      <div class="search__query container">
        <span>Вы искали:</span>
        <span class="search__query-text"><?= $query; ?></span>
      </div>
    </div>
    <div class="search__results-wrapper">
      <div class="container">
        <div class="search__content">
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
    </div>
  </section>
</div>
