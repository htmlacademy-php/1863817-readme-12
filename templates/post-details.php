<div class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?= $card['title'] ?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-photo">
        <div class="post-details__main-block post post--details">
          <!-- пост-цитата -->
          <?php if ($card["content_type"] === "post-quote") : ?>
            <div class="post-details__image-wrapper post-quote">
              <div class="post__main">
                <blockquote>
                  <p>
                    <?= $card['text_content']; ?>
                  </p>
                  <cite><?= $card['quote_author']; ?></cite>
                </blockquote>
              </div>
            </div>
            <!-- пост-текст -->
          <?php elseif ($card["content_type"] === "post-text") : ?>
            <div class="post-details__image-wrapper post-text">
              <div class="post__main">
                <p>
                  <?= $card['text_content']; ?>
                </p>
              </div>
            </div>
            <!-- пост-ссылка -->
          <?php elseif ($card["content_type"] === "post-link") : ?>
            <div class="post__main">
              <div class="post-link__wrapper">
                <a class="post-link__external" href="http://<?= $card["website_link"]; ?>" title="Перейти по ссылке">
                  <div class="post-link__info-wrapper">
                    <div class="post-link__icon-wrapper">
                      <img src="https://www.google.com/s2/favicons?domain=<?= $card["website_link"]; ?>" alt="Иконка">
                    </div>
                    <div class="post-link__info">
                      <h3><?= $card['title']; ?></h3>
                    </div>
                  </div>
                  <span><?= $card["website_link"]; ?></span>
                </a>
              </div>
            </div>
            <!-- пост-изображение -->
          <?php elseif ($card["content_type"] === "post-photo") : ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
              <img src="<?= $card["image_link"]; ?>" alt="Фото от пользователя" width="760" height="507">
            </div>
            <!-- пост-видео -->
          <?php elseif ($card["content_type"] === "post-video") : ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
              <?= embed_youtube_video($card["video_link"]); ?>
            </div>
          <?php endif; ?>
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="/likes.php?postId=<?= $card["id_post"]; ?>&amilike=<?= $card['amILikeThisPost'] ? 'yes' : 'no'; ?>" title="Лайк">
                <svg class="post__indicator-icon <?= $card['amILikeThisPost'] ? 'my-like' : ''; ?>" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= $card['likes_amount']; ?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?= $card['comments_amount']; ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="/repost.php?id_post=<?= $card["id_post"]; ?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?= $card['reposts_amount']; ?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?= $card["number_of_views"]; ?></span>
          </div>
          <ul class="post__tags">
            <?php
            $tags = ($card["hashtag_title"]);
            $tags = explode(' ', $tags);
            ?>
            <?php foreach ($tags as $key => $tag) : ?>
              <li><a href="/search.php?search=<?= urlencode($tag); ?>"><?= $tag ?></a></li>
            <?php endforeach; ?>
          </ul>
          <div class="comments">
            <form class="comments__form form" action="/comment.php" method="post">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="<?= $avatar[0]["avatar_link"]; ?>" width="40" height="40" alt="<?= !empty($avatar[0]["avatar_link"]) ? 'Аватар профиля.' : ''; ?>">
              </div>
              <div class="form__input-section <?= isset($_GET['error']) ? 'form__input-section--error' : ''; ?>">
                <textarea class="comments__textarea form__textarea form__input" name="comment" placeholder="Ваш комментарий"><?= isset($_GET['value']) ? $_GET['value'] : ''; ?></textarea>
                <input class="visually-hidden" name="id" value="<?= $_GET['post-id']; ?>">
                <label class="visually-hidden">Ваш комментарий</label>

                <?php if (isset($_GET['error'])) : ?>
                  <button class="form__error-button button" type="button">!</button>
                  <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?= $_GET['error']; ?></p>
                  </div>
                <?php endif; ?>
              </div>
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php if (isset($comments)) : ?>
                  <?php foreach ($comments as $key => $comment) : ?>
                    <li class="comments__item user">
                      <div class="comments__avatar">
                        <a class="user__avatar-link" href="/profile.php?id=<?= $comment["id_user"]; ?>&active=posts">
                          <img class="comments__picture" src="<?= $comment['avatar_link']; ?>" alt="<?= !empty($comment["avatar_link"]) ? 'Аватар пользователя.' : ''; ?>" width="40" height="40">
                        </a>
                      </div>
                      <div class="comments__info">
                        <div class="comments__name-wrapper">
                          <a class="comments__user-name" href="#">
                            <span><?= $comment['user_login']; ?></span>
                          </a>
                          <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($comment["comment_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($comment["comment_date"])); ?>">
                            <?php if (empty(createTextForDate($dataForDatatime))) : ?>
                              только что
                            <?php else : ?>
                              <?= createTextForDate($dataForDatatime); ?> назад
                            <?php endif; ?>
                          </time>
                        </div>
                        <p class="comments__text">
                          <?= $comment['comment_text']; ?>
                        </p>
                      </div>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
              </ul>
              <?php if (isset($moreCommentsExist)) : ?>
                <a class="comments__more-link" href="<?= $_SERVER['REQUEST_URI'] . '&comments=all'; ?>">
                  <span>Показать все комментарии</span>
                  <sup class="comments__amount"><?= $moreCommentsExist; ?></sup>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="/profile.php?id=<?= $card["id_user"]; ?>&active=posts">
                <img class="post-details__picture user__picture" src="/<?= $card["avatar_link"]; ?>" alt="<?= !empty($card["avatar_link"]) ? 'Аватар пользователя.' : ''; ?>" width="60" height="60">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="/profile.php?id=<?= $card["id_user"]; ?>&active=posts">
                <span><?= $card["user_login"]; ?></span>
              </a>
              <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($card["registration_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($card["registration_date"])); ?>"><?= createTextForDate($dataForDatatime); ?> на сайте</time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?= $card["subs_amount"]; ?></span>
              <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($card["subs_amount"], 'подписчик', 'подписчика', 'подписчиков') ?></span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?= $postsAmount ?></span>
              <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($postsAmount, 'публикация', 'публикации', 'публикаций') ?></span>
            </p>
          </div>
          <?php if (!$isMyProfile) : ?>
            <div class="post-details__user-buttons user__buttons">
              <?php if (isset($amISubOnMainProfile) && $amISubOnMainProfile === 0) : ?>
                <a class="user__button user__button--subscription button button--main" href="/sub.php?sub=sub&id=<?= $card["id_user"]; ?>">Подписаться</a>
              <?php else : ?>
                <a class="user__button user__button--subscription button button--main button--quartz" href="/sub.php?sub=onsub&id=<?= $card["id_user"]; ?>">Отписаться</a>
              <?php endif; ?>
              <a class="user__button user__button--writing button button--green" href="/messages.php?dialogWithUser=<?= $card['id_user']; ?>">Сообщение</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </div>
</div>
