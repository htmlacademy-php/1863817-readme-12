<div class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--default">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <img class="profile__picture user__picture" src="<?= $profileAvatar[0]['avatar_link']; ?>" width="100" height="100" alt="Аватар пользователя">
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?= $login; ?></span>
            <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($registrationDate)); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($registrationDate)); ?>"><?= createTextForDate($dataForDatatime); ?> на сайте</time>
          </div>
        </div>
        <div class="profile__rating user__rating">
          <p class="profile__rating-item user__rating-item user__rating-item--publications">
            <span class="user__rating-amount"><?= $postsAmount; ?></span>
            <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($postsAmount, 'публикация', 'публикации', 'публикаций'); ?></span>
          </p>
          <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
            <span class="user__rating-amount"><?= $subscriptionsAmount; ?></span>
            <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($subscriptionsAmount, 'подписчик', 'подписчика', 'подписчиков'); ?></span>
          </p>
        </div>
        <? if (!$isMyProfile) : ?>
          <div class="profile__user-buttons user__buttons">
            <? if ($amISubOnMainProfile === 0) : ?>
              <div class="profile__user-button user__button user__button--subscription button button--main">
                <a class="" href="/sub.php?sub=sub&id=<?= $_GET['id']; ?>">Подписаться</a>
              </div>
            <? else : ?>
              <div class="profile__user-button user__button user__button--subscription button button--quartz">
                <a class="" href="/sub.php?sub=onsub&id=<?= $_GET['id']; ?>">Отписаться</a>
              </div>
            <? endif; ?>
            <a class="profile__user-button user__button user__button--writing button button--green" href="/messages.php?dialogWithUser=<?= $_GET['id']; ?>">Сообщение</a>
          </div>
        <? endif; ?>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button <?= $_GET['active'] === 'posts' ? 'filters__button--active tabs__item tabs__item--active' : ''; ?> tabs__item button" href="/profile.php?id=<?= $_GET["id"]; ?>&active=posts">Посты</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button <?= $_GET['active'] === 'likes' ? 'filters__button--active tabs__item tabs__item--active' : ''; ?> tabs__item button" href="/profile.php?id=<?= $_GET["id"]; ?>&active=likes">Лайки</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button <?= $_GET['active'] === 'subs' ? 'filters__button--active tabs__item tabs__item--active' : ''; ?> tabs__item button" href="/profile.php?id=<?= $_GET["id"]; ?>&active=subs">Подписки</a>
            </li>
          </ul>
        </div>
        <div class="profile__tab-content">
          <? if ($_GET['active'] === 'posts') : ?>
            <section class="profile__posts tabs__content <?= $_GET['active'] === 'posts' ? 'tabs__content--active' : ''; ?>">
              <h2 class="visually-hidden">Публикации</h2>
              <? if (isset($postsByUser)) : ?>
                <?php foreach ($postsByUser as $key => $post) : ?>
                  <article class="profile__post post <?= $post['content_type']; ?>">
                    <header class="post__header">
                      <? if ($post['repost'] === '1') : ?>
                        <div class="post__author">
                          <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                              <img class="post__author-avatar" src="<?= $post['author_avatar_link']; ?>" alt="Аватар пользователя" width="60" height="60">
                            </div>
                            <div class="post__info">
                              <b class="post__author-name">Репост: <?= $post['author_login']; ?></b>
                            </div>
                          </a>
                        </div>
                        <h2><a href="/post.php?post-id=<?= $post["id_post"]; ?>"><?= $post['title']; ?></a></h2>
                      <? else : ?>
                        <h2><a href="/post.php?post-id=<?= $post["id_post"]; ?>"><?= $post['title']; ?></a></h2>
                      <? endif; ?>
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
                              <?= embed_youtube_cover($post["video_link"], 760, 396); ?>
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
                    <footer class="post__footer">
                      <div class="post__indicators">
                        <div class="post__buttons">
                          <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                            <svg class="post__indicator-icon" width="20" height="17">
                              <use xlink:href="#icon-heart"></use>
                            </svg>
                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                              <use xlink:href="#icon-heart-active"></use>
                            </svg>
                            <span><?= $post['likesAmount']; ?></span>
                            <span class="visually-hidden">количество лайков</span>
                          </a>
                          <a class="post__indicator post__indicator--repost button" href="/repost.php?id_post=<?= $post["id_post"]; ?>" title="Репост">
                            <svg class="post__indicator-icon" width="19" height="17">
                              <use xlink:href="#icon-repost"></use>
                            </svg>
                            <span><?= $post['reposts_amount']; ?></span>
                            <span class="visually-hidden">количество репостов</span>
                          </a>
                        </div>
                        <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($post["post_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($post["post_date"])); ?>"><?= createTextForDate($dataForDatatime); ?> назад</time>
                      </div>
                      <ul class="post__tags">
                        <?php
                        $tags = ($post["tags"]);
                        $tags = explode(' ', $tags);
                        ?>
                        <? foreach ($tags as $key => $tag) : ?>
                          <li><a href="/search.php?search=<?= urlencode($tag); ?>"><?= $tag ?></a></li>
                        <? endforeach; ?>
                      </ul>
                    </footer>
                    <? if (!empty($post['comments']) && !isset($_GET['showcomments'])) : ?>
                      <div class="comments">
                        <a class="comments__button button" href="/profile.php?id=<?= $_GET['id']; ?>&active=posts&showcomments=1">Показать комментарии</a>
                      </div>
                    <? endif; ?>
                    <div class="comments">
                      <div class="comments__list-wrapper">
                        <ul class="comments__list">
                          <? if (isset($_GET['showcomments']) && !empty($post['comments'])) : ?>
                            <? foreach ($post['comments'] as $key => $comment) : ?>
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
                        <? if ($post['moreCommentsExist'] && isset($_GET['showcomments'])) : ?>
                          <a class="comments__more-link" href="/profile.php?id=<?= $_GET['id']; ?>&active=posts&showcomments=1&comments=1">
                            <span>Показать все комментарии</span>
                            <sup class="comments__amount"><?= $post['moreCommentsExist']; ?></sup>
                          </a>
                        <? endif; ?>
                      </div>
                    </div>
                    <? if (isset($_GET['showcomments'])) : ?>

                      <form class="comments__form form" action="/comment.php" method="post">

                        <? if (!empty($avatarFotCommentIcon[0]["avatar_link"])) : ?>
                          <div class="comments__my-avatar">
                            <img class="comments__picture" src="<?= $avatarFotCommentIcon[0]["avatar_link"]; ?>" width="40" height="40" alt="Аватар профиля">
                          </div>
                        <? else : ?>
                          <div class="comments__my-avatar">
                            <img class="comments__picture" src="" width="40" height="40" alt="Аватар профиля">
                          </div>
                        <? endif; ?>

                        <div class="form__input-section <?= isset($_GET['error']) ? 'form__input-section--error' : ''; ?>">
                          <textarea class="comments__textarea form__textarea form__input" name="comment" placeholder="Ваш комментарий"><?= isset($_GET['value']) ? $_GET['value'] : ''; ?></textarea>
                          <input class="visually-hidden" name="id" value="<?= $post['id_post']; ?>">
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

                    <? endif; ?>
                  </article>
                <? endforeach; ?>
              <? endif; ?>

            </section>

          <? elseif ($_GET['active'] === 'likes') : ?>
            <section class="profile__likes tabs__content <?= $_GET['active'] === 'likes' ? 'tabs__content--active' : ''; ?>">
              <h2 class="visually-hidden">Лайки</h2>
              <ul class="profile__likes-list">
                <? if (isset($likes)) : ?>
                  <?php foreach ($likes as $key => $like) : ?>
                    <li class="post-mini post-mini--photo post user">
                      <div class="post-mini__user-info user__info">
                        <div class="post-mini__avatar user__avatar">
                          <a class="user__avatar-link" href="/profile.php?id=<?= $like['user_like']; ?>&active=posts">
                            <img class="post-mini__picture user__picture" src="<?= $like['avatar_link']; ?>" alt="Аватар пользователя" width="60" height="60">
                          </a>
                        </div>
                        <div class="post-mini__name-wrapper user__name-wrapper">
                          <a class="post-mini__name user__name" href="/profile.php?id=<?= $like['user_like']; ?>&active=posts">
                            <span><?= $like['user_login']; ?></span>
                          </a>
                          <div class="post-mini__action">
                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                            <time class="post-mini__time user__additional" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($like['likes_date'])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($like['likes_date'])); ?>"><?= createTextForDate($dataForDatatime); ?> назад</time>
                          </div>
                        </div>
                      </div>
                      <div class="post-mini__preview">
                        <a class="post-mini__link" href="/post.php?post-id=<?= $like['id_post']; ?>" title="Перейти на публикацию">
                          <? if ($like['content_type'] === 'post-quote') : ?>
                            <span class="visually-hidden">Цитата</span>
                            <svg class="post-mini__preview-icon" width="21" height="20">
                              <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                          <? elseif ($like['content_type'] === 'post-text') : ?>
                            <span class="visually-hidden">Текст</span>
                            <svg class="post-mini__preview-icon" width="20" height="21">
                              <use xlink:href="#icon-filter-text"></use>
                            </svg>
                            <span class="visually-hidden">Текст</span>
                          <? elseif ($like['content_type'] === 'post-link') : ?>
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="post-mini__preview-icon" width="21" height="18">
                              <use xlink:href="#icon-filter-link"></use>
                            </svg>
                          <? elseif ($like['content_type'] === 'post-photo') : ?>
                            <div class="post-mini__image-wrapper">
                              <img class="post-mini__image" src="<?= $like['image_link']; ?>" width="109" height="109" alt="Превью публикации">
                            </div>
                            <span class="visually-hidden">Фото</span>
                          <? elseif ($like['content_type'] === 'post-video') : ?>
                            <div class="post-mini__image-wrapper">
                              <?= embed_youtube_cover($like["video_link"], 109, 109, 'post-mini__image'); ?>
                              <span class="post-mini__play-big">
                                <svg class="post-mini__play-big-icon" width="12" height="13">
                                  <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                              </span>
                            </div>
                            <span class="visually-hidden">Видео</span>
                          <? endif; ?>
                        </a>
                      </div>
                    </li>
                  <? endforeach; ?>
                <? endif; ?>
              </ul>
            </section>

          <? elseif ($_GET['active'] === 'subs') : ?>
            <section class="profile__subscriptions tabs__content <?= $_GET['active'] === 'subs' ? 'tabs__content--active' : ''; ?>">
              <h2 class="visually-hidden">Подписки</h2>
              <ul class="profile__subscriptions-list">
                <? if (isset($subs)) : ?>
                  <?php foreach ($subs as $key => $sub) : ?>
                    <li class="post-mini post-mini--photo post user">
                      <div class="post-mini__user-info user__info">
                        <div class="post-mini__avatar user__avatar">
                          <a class="user__avatar-link" href="/profile.php?id=<?= $sub['id_user']; ?>&active=posts">
                            <img class="post-mini__picture user__picture" src="<?= $sub['avatar_link']; ?>" alt="Аватар пользователя" width="60" height="60">
                          </a>
                        </div>
                        <div class="post-mini__name-wrapper user__name-wrapper">
                          <a class="post-mini__name user__name" href="/profile.php?id=<?= $sub['id_user']; ?>&active=posts">
                            <span><?= $sub['user_login']; ?></span>
                          </a>
                          <br>
                          <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($sub['registration_date'])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($sub['registration_date'])); ?>"><?= createTextForDate($dataForDatatime); ?> на сайте</time>
                        </div>
                      </div>
                      <div class="post-mini__rating user__rating">
                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                          <span class="post-mini__rating-amount user__rating-amount"><?= $sub['postsAmount']; ?></span>
                          <span class="post-mini__rating-text user__rating-text"><?= get_noun_plural_form($sub['postsAmount'], 'публикация', 'публикации', 'публикаций') ?></span>
                        </p>
                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                          <span class="post-mini__rating-amount user__rating-amount"><?= $sub['subsAmount']; ?></span>
                          <span class="post-mini__rating-text user__rating-text"><?= get_noun_plural_form($sub['subsAmount'], 'подписчик', 'подписчика', 'подписчиков') ?></span>
                        </p>
                      </div>
                      <? if ($sub['amISub'] === '0' && $sub['id_user'] !== $_SESSION['userId']) : ?>
                        <div class="post-mini__user-buttons user__buttons">
                          <a class="post-mini__user-button user__button user__button--subscription button button--main" href="/sub.php?sub=sub&id=<?= $sub['id_receiver_sub']; ?>">Подписаться</a>
                        </div>
                      <? elseif ($sub['amISub'] !== '0' && $sub['id_user'] !== $_SESSION['userId']) : ?>
                        <div class="post-mini__user-buttons user__buttons">
                          <a class="post-mini__user-button user__button user__button--subscription button button--quartz" href="/sub.php?sub=onsub&id=<?= $sub['id_receiver_sub']; ?>">Отписаться</a>
                        </div>
                      <? endif; ?>
                    </li>
                  <? endforeach; ?>
                <? endif; ?>
              </ul>
            </section>
          <? endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
