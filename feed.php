<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$page_content = include_template('feed-template.php');
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: моя лента', 'avatar' => getAvatarForUser()]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
