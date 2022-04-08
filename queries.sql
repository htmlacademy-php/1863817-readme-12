-- список типов контента для поста;
INSERT INTO contentTypes (content_type_title, content_class_type) VALUE ('q', 'post-quote');
INSERT INTO contentTypes (content_type_title, content_class_type) VALUE ('t', 'post-text');
INSERT INTO contentTypes (content_type_title, content_class_type) VALUE ('p', 'post-photo');
INSERT INTO contentTypes (content_type_title, content_class_type) VALUE ('l', 'post-link');
INSERT INTO contentTypes (content_type_title, content_class_type) VALUE ('v', 'post-video');

-- придумайте пару пользователей;
INSERT INTO users (email, password, registration_date, login) VALUE ('ALINA@MAIL.RU', 'MID890', NOW(), 'Лариса');
INSERT INTO users (email, password, registration_date, login) VALUE ('MAX@YANDEX.RU', '12345', NOW(), 'Владик');
INSERT INTO users (email, password, registration_date, login) VALUE ('lola@YANDEX.RU', '12345qwert', NOW(), 'Виктор');

-- придумайте пару комментариев к разным постам;
INSERT INTO comments (comment_date, comment_text, id_user, id_post) VALUE (NOW(), 'НУ И ЧТО ЭТО ТАКОЕ', 1, 2);
INSERT INTO comments (comment_date, comment_text, id_user, id_post) VALUE (NOW(), 'ОЧЕНЬ КЛАССНО, МНЕ НРАВИТСЯ', 2, 1);

-- существующий список постов.
INSERT INTO posts (post_date, title, user_name, number_of_views, content_type, text_content, id_user)
VALUE (NOW(), 'Цитата', 'Лариса', 56, 'q', 'Мы в жизни любим только раз, а после ищем лишь похожих', 1);
INSERT INTO posts (post_date, title, user_name, number_of_views, content_type, text_content, id_user)
VALUE (NOW(), 'Игра престолов', 'Владик', 98, 't', 'Не могу дождаться начала финального сезона своего любимого сериала!', 2);
INSERT INTO posts (post_date, title, user_name, number_of_views, content_type, image_link, id_user)
VALUE (NOW(), 'Наконец, обработал фотки!', 'Виктор', 54, 'p', 'rock-medium.jpg', 2);
INSERT INTO posts (post_date, title, user_name, number_of_views, content_type, image_link, id_user)
VALUE (NOW(), 'Моя мечта', 'Лариса', 3, 'p', 'coast-medium.jpg', 1);
INSERT INTO posts (post_date, title, user_name, number_of_views, content_type, website_link, id_user)
VALUE (NOW(), 'Лучшие курсы', 'Владик', 15, 'l', 'www.htmlacademy.ru', 3);

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT number_of_views, content_type, user_name FROM posts ORDER BY number_of_views ASC;

-- получить список постов для конкретного пользователя;
SELECT * FROM posts WHERE id_user = 1;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT comment_date, comment_text, comments.id_user, id_post, login FROM comments JOIN users ON comments.id_user = users.id_user AND id_post = 1;

-- добавить лайк к посту;
INSERT INTO likes (id_user, id_post) VALUE (2, 2);

-- подписаться на пользователя.
INSERT INTO subscriptions (id_subscriber, id_receiver_sub) VALUE (1, 2);
