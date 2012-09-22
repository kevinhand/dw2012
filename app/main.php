<?php
require_once(__DIR__ . '/vendor/flourish.php');
require_once(__DIR__ . '/vendor/slim.php');
require_once(__DIR__ . '/vendor/markdown.php');

require_once(__DIR__ . '/../cache-settings.php');
require_once(__DIR__ . '/init.php');

// include models here

require_once(__DIR__ . '/controllers/ApplicationController.php');

require_once(__DIR__ . '/helpers/Util.php');
require_once(__DIR__ . '/helpers/Lock.php');

require_once(__DIR__ . '/routes.php');
