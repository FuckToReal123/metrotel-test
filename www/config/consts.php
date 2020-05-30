<?php

/** @var string Корневая папка, ради удобства */
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
/** @var int Включен ли режим разработки */
define('DEV_MODE', getenv('IS_DEV'));
/** @var string Папка со шрифтами */
define('FONTS_DIR', ROOT_DIR . 'assets/fonts/');
/** @var string Папка с фото контактов */
define('PHOTOS_DIR', ROOT_DIR . 'upload/images/');
