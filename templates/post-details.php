<div class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?= $card[0]['title'] ?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-photo">
        <div class="post-details__main-block post post--details">
          <!-- пост-цитата -->
          <?php if ($card[0]["content_type"] === "post-quote") : ?>
            <div class="post-details__image-wrapper post-quote">
              <div class="post__main">
                <blockquote>
                  <p>
                    <?= $card[0]['text_content']; ?>
                  </p>
                  <cite><?= $card[0]['quote_author']; ?></cite>
                </blockquote>
              </div>
            </div>
            <!-- пост-текст -->
          <?php elseif ($card[0]["content_type"] === "post-text") : ?>
            <div class="post-details__image-wrapper post-text">
              <div class="post__main">
                <p>
                  <?= $card[0]['text_content']; ?>
                </p>
              </div>
            </div>
            <!-- пост-ссылка -->
          <?php elseif ($card[0]["content_type"] === "post-link") : ?>
            <div class="post__main">
              <div class="post-link__wrapper">
                <a class="post-link__external" href="http://<?= $card[0]["website_link"]; ?>" title="Перейти по ссылке">
                  <div class="post-link__info-wrapper">
                    <div class="post-link__icon-wrapper">
                      <img src="https://www.google.com/s2/favicons?domain=<?= $card[0]["website_link"]; ?>" alt="Иконка">
                    </div>
                    <div class="post-link__info">
                      <h3><?= $card[0]['title']; ?></h3>
                    </div>
                  </div>
                  <span><?= $card[0]["website_link"]; ?></span>
                </a>
              </div>
            </div>
            <!-- пост-изображение -->
          <?php elseif ($card[0]["content_type"] === "post-photo") : ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
              <img src="<?= $card[0]["image_link"]; ?>" alt="Фото от пользователя" width="760" height="507">
            </div>
            <!-- пост-видео -->
          <?php elseif ($card[0]["content_type"] === "post-video") : ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
              <?= embed_youtube_video($card[0]["video_link"]); ?>
            </div>
          <?php endif; ?>
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="/likes.php?postId=<?= $card[0]["id_post"]; ?>&amilike=<?= $card[0]['amILikeThisPost'] ? 'yes' : 'no'; ?>" title="Лайк">
                <svg class="post__indicator-icon <?= $card[0]['amILikeThisPost'] ? 'my-like' : ''; ?>" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= count($likes); ?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?= count($comments); ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span>5</span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?= $card[0]["number_of_views"]; ?></span>
          </div>
          <ul class="post__tags">
            <?php
            $tags = ($tags[0]["hashtag_title"]);
            $tags = explode(' ', $tags);
            ?>
            <? foreach ($tags as $key => $tag) : ?>
              <li><a href="/search.php?search=<?= urlencode($tag); ?>"><?= $tag ?></a></li>
            <? endforeach; ?>
          </ul>
          <div class="comments">
            <form class="comments__form form" action="/comment.php" method="post">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
              </div>
              <div class="form__input-section <?= isset($_GET['error']) ? 'form__input-section--error' : ''; ?>">
                <textarea class="comments__textarea form__textarea form__input" name="comment" placeholder="Ваш комментарий"><?= isset($_GET['value']) ? $_GET['value'] : ''; ?></textarea>
                <input class="visually-hidden" name="id" value="<?= $_GET['post-id']; ?>">
                <label class="visually-hidden">Ваш комментарий</label>

                <? if (isset($_GET['error'])) : ?>
                  <button class="form__error-button button" type="button">!</button>
                  <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?= $_GET['error']; ?></p>
                  </div>
                <? endif; ?>
              </div>
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <? if (isset($comments)) : ?>
                  <? foreach ($comments as $key => $comment) : ?>
                    <li class="comments__item user">
                      <div class="comments__avatar">
                        <a class="user__avatar-link" href="/profile.php?id=<?= $comment["id_user"]; ?>&active=posts">
                          <img class="comments__picture" src="<?= $comment['avatar_link']; ?>" alt="Аватар пользователя" width="40" height="40">
                        </a>
                      </div>
                      <div class="comments__info">
                        <div class="comments__name-wrapper">
                          <a class="comments__user-name" href="#">
                            <span><?= $comment['user_login']; ?></span>
                          </a>
                          <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($comment["comment_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($comment["comment_date"])); ?>"><?= createTextForDate($dataForDatatime); ?> назад</time>
                        </div>
                        <p class="comments__text">
                          <?= $comment['comment_text']; ?>
                        </p>
                      </div>
                    </li>
                  <? endforeach; ?>
                <? endif; ?>
              </ul>
              <? if ($moreCommentsExist) : ?>
                <a class="comments__more-link" href="<?= $_SERVER['REQUEST_URI'] . '&comments=all'; ?>">
                  <span>Показать все комментарии</span>
                  <sup class="comments__amount"><?= $moreCommentsExist; ?></sup>
                </a>
              <? endif; ?>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="/profile.php?id=<?= $card[0]["id_user"]; ?>&active=posts">
                <img class="post-details__picture user__picture" src="/<?= $card[0]["avatar_link"]; ?>" alt="Аватар пользователя" width="60" height="60">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="/profile.php?id=<?= $card[0]["id_user"]; ?>&active=posts">
                <span><?= $card[0]["user_login"]; ?></span>
              </a>
              <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($registrationDate)); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($registrationDate)); ?>"><?= createTextForDate($dataForDatatime); ?> на сайте</time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?= count($subscriptions) ?></span>
              <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form(count($subscriptions), 'подписчик', 'подписчика', 'подписчиков') ?></span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?= count($posts) ?></span>
              <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form(count($posts), 'публикация', 'публикации', 'публикаций') ?></span>
            </p>
          </div>
          <div class="post-details__user-buttons user__buttons">
            <? if ($amISubOnMainProfile === 0) : ?>
              <a class="user__button user__button--subscription button button--main" href="/sub.php?sub=sub&id=<?= $card[0]["id_user"]; ?>">Подписаться</a>
            <? else : ?>
              <a class="user__button user__button--subscription button button--main button--quartz" href="/sub.php?sub=onsub&id=<?= $card[0]["id_user"]; ?>">Отписаться</a>
            <? endif; ?>
            <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
