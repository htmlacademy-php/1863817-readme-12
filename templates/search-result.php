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
              <article class="search__post post <?= $card["content_type"]; ?>">
                <header class="post__header post__author">
                  <a class="post__author-link" href='/post.php?post-id=<?= $card["id_post"]; ?>' title="Автор">
                    <div class="post__avatar-wrapper">
                      <img class="post__author-avatar" src="/<?= $card["avatar_link"]; ?>" alt="<?= !empty($card["avatar_link"]) ? 'Аватар пользователя.' : ''; ?>" width="60" height="60">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name"><?= $card["user_login"]; ?></b>
                      <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($card["post_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($card["post_date"])); ?>"><?= createTextForDate($dataForDatatime); ?> назад</time>
                    </div>
                  </a>
                </header>
                <div class="post__main">
                  <?php if ($card["content_type"] === "post-quote") : ?>
                    <h2><a href="/post.php?post-id=<?= $card["id_post"]; ?>"><?= $card["title"]; ?></a></h2>
                    <blockquote>
                      <p>
                        <?= $card["text_content"]; ?>
                      </p>
                      <cite>
                        <?= $card["quote_author"]; ?>
                      </cite>
                    </blockquote>
                  <?php elseif ($card["content_type"] === "post-text") :
                    list($newString, $cut) = addLinkForBigText($card["text_content"]); ?>
                    <h2><a href="/post.php?post-id=<?= $card["id_post"]; ?>"><?= $card["title"]; ?></a></h2>
                    <p>
                      <?= $newString; ?>
                    </p>
                    <?php if ($cut) : ?>
                      <div class="post-text__more-link-wrapper">
                        <a class="post-text__more-link" href="#">Читать далее</a>
                      </div>
                    <?php endif; ?>
                  <?php elseif ($card["content_type"] === "post-photo") : ?>
                    <h2><a href="/post.php?post-id=<?= $card["id_post"]; ?>"><?= $card["title"]; ?></a></h2>
                    <div class="post-photo__image-wrapper">
                      <img src="<?= $card["image_link"]; ?>" alt="Фото от пользователя" width="360" height="240">
                    </div>
                  <?php elseif ($card["content_type"] === "post-video") : ?>
                    <h2><a href="/post.php?post-id=<?= $card["id_post"]; ?>"><?= $card["title"]; ?></a></h2>
                    <div class="post__main">
                      <div class="post-video__block video_search">
                        <div class="post-video__preview">
                          <a href='<?= $card["video_link"]; ?>'>
                            <?= embed_youtube_cover($card["video_link"], 320, 120); ?>
                          </a>
                        </div>
                        <div class="post-video__control">
                          <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                          <div class="post-video__scale-wrapper">
                            <div class="post-video__scale">
                              <div class="post-video__bar">
                                <div class="post-video__toggle"></div>
                              </div>
                            </div>
                          </div>
                          <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                        </div>
                        <button class="post-video__play-big button" type="button">
                          <svg class="post-video__play-big-icon" width="27" height="28">
                            <use xlink:href="#icon-video-play-big"></use>
                          </svg>
                          <span class="visually-hidden">Запустить проигрыватель</span>
                        </button>
                      </div>
                    <?php elseif ($card["content_type"] === "post-link") : ?>
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
                    <footer class="post__footer post__indicators">
                      <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                          <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                          </svg>
                          <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                          </svg>
                          <span><?= $card["likes_amount"]; ?></span>
                          <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                          <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-comment"></use>
                          </svg>
                          <span><?= $card["comments_amount"]; ?></span>
                          <span class="visually-hidden">количество комментариев</span>
                        </a>
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
