<?php

/** @var string Корневая папка, ради удобства */
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
/** @var int Включен ли режим разработки */
define('DEV_MODE', getenv('IS_DEV'));
