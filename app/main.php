<?php
require(__DIR__ . '/vendor/flourish.php');
require(__DIR__ . '/vendor/slim.php');
require(__DIR__ . '/vendor/markdown.php');

require(__DIR__ . '/../cache-settings.php');
require(__DIR__ . '/init.php');

require(__DIR__ . '/models/User.php');
require(__DIR__ . '/models/Note.php');

require(__DIR__ . '/controllers/ApplicationController.php');

require(__DIR__ . '/helpers/Util.php');
require(__DIR__ . '/helpers/Lock.php');

require(__DIR__ . '/routes.php');
