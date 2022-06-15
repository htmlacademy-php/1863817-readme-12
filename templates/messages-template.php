<div class="page__main page__main--messages">
  <h1 class="visually-hidden">Личные сообщения</h1>
  <section class="messages tabs">
    <h2 class="visually-hidden">Сообщения</h2>
    <div class="messages__contacts">
      <ul class="messages__contacts-list tabs__list">
        <? if (isset($dialogs) && !empty($dialogs)) : ?>
          <? foreach ($dialogs as $key => $value) : ?>
            <li class="messages__contacts-item">
              <a class="messages__contacts-tab tabs__item <?= $_GET['dialogWithUser'] == $value[0]['id_user'] ? 'messages__contacts-tab--active tabs__item--active' : ''; ?>" href="/messages.php?dialogWithUser=<?= $value[0]['id_user']; ?>">
                <div class="messages__avatar-wrapper">
                  <img class="messages__avatar" src="<?= $value[0]["avatar_link"]; ?>" width="60" height="60" alt="<?= !empty($value[0]["avatar_link"]) ? 'Аватар профиля.' : ''; ?>">
                  <? if (isset($value[0]["newMessagesCount"]) && !empty($value[0]["newMessagesCount"])) : ?>
                    <i class="messages__indicator"><?= $value[0]["newMessagesCount"]; ?></i>
                  <? endif; ?>
                </div>
                <div class="messages__info">
                  <span class="messages__contact-name">
                    <?= $key ?>
                  </span>
                  <div class="messages__preview">
                    <p class="messages__preview-text">
                      <? if (iconv_strlen($value[count($value) - 1]['message_text']) > 13) :
                        list($newString, $cut) = addLinkForBigText($value[count($value) - 1]['message_text'], 13); ?>
                        <?= $newString; ?>
                      <? else : ?>
                        <?= $value[count($value) - 1]['message_text']; ?>
                      <? endif; ?>
                    </p>
                    <? if (isset($value[count($value) - 1]['message_date']) && !empty($value[count($value) - 1]['message_date'])) : ?>
                      <time class="messages__preview-time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($value[count($value) - 1]['message_date'])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($value[count($value) - 1]['message_date'])); ?>">
                        <? if ((time() - strtotime($value[count($value) - 1]['message_date'])) > 86400) : ?>
                          <?= date('d-M', strtotime($value[count($value) - 1]['message_date'])); ?>
                        <? else : ?>
                          <?= date('H:i', strtotime($value[count($value) - 1]['message_date'])); ?>
                        <? endif; ?>
                      </time>
                    <? endif; ?>
                  </div>
                </div>
              </a>
            </li>
          <? endforeach; ?>
        <? endif; ?>
        <? if (isset($newUser) && !empty($newUser)) : ?>
          <li class="messages__contacts-item">
            <a class="messages__contacts-tab tabs__item messages__contacts-tab--active tabs__item--active" href="/messages.php?dialogWithUser=<?= $value[0]['id_user']; ?>">
              <div class="messages__avatar-wrapper">
                <img class="messages__avatar" src="<?= $newUser['avatar'] ?>" alt="<?= !empty($newUser["avatar"]) ? 'Аватар пользователя.' : ''; ?>" width="60" height="60">
              </div>
              <div class="messages__info">
                <span class="messages__contact-name">
                  <?= $newUser['login']; ?>
                </span>
              </div>
            </a>
          </li>
        <? endif; ?>
      </ul>
    </div>
    <div class="messages__chat">
      <div class="messages__chat-wrapper">
        <? if (isset($dialogs) && !empty($dialogs)) : ?>
          <? foreach ($dialogs as $key => $dialog) : ?>
            <ul class="messages__list tabs__content <?= $_GET['dialogWithUser'] == $dialog[0]['id_user'] ? 'tabs__content--active' : ''; ?>">
              <? foreach ($dialog as $key => $value) : ?>
                <? if ($value['id_who_writed'] !== $_SESSION['userId']) : ?>
                  <li class="messages__item">
                    <div class="messages__info-wrapper">
                      <div class="messages__item-avatar">
                        <a class="messages__author-link" href="/profile.php?id=<?= $dialog[0]['id_user']; ?>&active=posts">
                          <? if (!empty($value["avatar_link"])) : ?>
                            <img class="messages__avatar" src="<?= $value["avatar_link"]; ?>" width="40" height="40" alt="<?= !empty($value["avatar_link"]) ? 'Аватар профиля.' : ''; ?>">
                          <? else : ?>
                            <img class="messages__avatar" src="" width="40" height="40">
                          <? endif; ?>
                        </a>
                      </div>
                      <div class="messages__item-info">
                        <a class="messages__author" href="#">
                          <?= $value['user_login']; ?>
                        </a>
                        <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($value["message_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($value["message_date"])); ?>">
                          <? if (empty(createTextForDate($dataForDatatime))) : ?>
                            только что
                          <? else : ?>
                            <?= createTextForDate($dataForDatatime); ?> назад
                          <? endif; ?>
                        </time>
                      </div>
                    </div>
                    <p class="messages__text">
                      <?= $value['message_text']; ?>
                    </p>
                  </li>
                <? else : ?>
                  <li class="messages__item messages__item--my">
                    <div class="messages__info-wrapper">
                      <div class="messages__item-avatar">
                        <a class="messages__author-link" href="/profile.php?id=<?= $_SESSION['userId']; ?>&active=posts">
                          <img class="messages__avatar" src="<?= $avatar[0]["avatar_link"]; ?>" width="40" height="40" alt="<?= !empty($avatar[0]["avatar_link"]) ? 'Аватар профиля.' : ''; ?>">
                        </a>
                      </div>
                      <div class="messages__item-info">
                        <a class="messages__author" href="#">
                          <?= $_SESSION['username']; ?>
                        </a>
                        <time class="post__time" datetime="<?= $dataForDatatime = date('Y-m-d H:i:s', strtotime($value["message_date"])); ?>" title="<?= date('%d.%m.%Y %H:%M', strtotime($value["message_date"])); ?>">
                          <? if (empty(createTextForDate($dataForDatatime))) : ?>
                            только что
                          <? else : ?>
                            <?= createTextForDate($dataForDatatime); ?> назад
                          <? endif; ?>
                        </time>
                      </div>
                    </div>
                    <p class="messages__text">
                      <?= $value['message_text']; ?>
                    </p>
                  </li>
                <? endif; ?>
              <? endforeach; ?>
            </ul>
          <? endforeach; ?>
        <? endif; ?>
      </div>
      <div class="comments">
        <form class="comments__form form" action="/messages.php?dialogWithUser=<?= $_GET['dialog']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="comments__my-avatar">
            <? if (!empty($avatar[0]["avatar_link"])) : ?>
              <img class="comments__picture" src="<?= $avatar[0]["avatar_link"]; ?>" width="40" height="40" alt="<?= !empty($avatar[0]["avatar_link"]) ? 'Аватар профиля.' : ''; ?>">
            <? else : ?>
              <img class="comments__picture" src="" width="40" height="40">
            <? endif; ?>
          </div>
          <div class="form__input-section <?= isset($_GET['error']) ? 'form__input-section--error' : ''; ?>">
            <textarea class="comments__textarea form__textarea form__input" name="message" id="message" placeholder="Ваше сообщение"></textarea>
            <input class="visually-hidden" name="dialog" value="<?= $_GET['dialogWithUser']; ?>">
            <label class="visually-hidden">Ваше сообщение</label>
            <button class="form__error-button button" type="button">!</button>
            <? if (isset($_GET['error'])) : ?>
              <div class="form__error-text">
                <h3 class="form__error-title">Ошибка валидации</h3>
                <p class="form__error-desc"><?= $_GET['error']; ?></p>
              </div>
            <? endif; ?>
          </div>
          <button class="comments__submit button button--green" type="submit">Отправить</button>
        </form>
      </div>
    </div>
  </section>
</div>
