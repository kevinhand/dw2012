<?php
require(__DIR__ . '/vendor/flourish.php');
require(__DIR__ . '/vendor/slim.php');
require(__DIR__ . '/vendor/markdown.php');

require(__DIR__ . '/../cache-settings.php');
require(__DIR__ . '/init.php');

require(__DIR__ . '/models/User.php');
require(__DIR__ . '/models/Note.php');

require(__DIR__ . '/controllers/ApplicationController.php');
require(__DIR__ . '/controllers/NoteController.php');

require(__DIR__ . '/helpers/Util.php');
require(__DIR__ . '/helpers/UrlParser.php');
require(__DIR__ . '/helpers/TitleParser.php');
require(__DIR__ . '/helpers/Lock.php');

require(__DIR__ . '/parsers/GitHubParser.php');
require(__DIR__ . '/parsers/StackOverflowParser.php');
require(__DIR__ . '/parsers/CiteSeerParser.php');
require(__DIR__ . '/parsers/ACMDigitalLibraryParser.php');
require(__DIR__ . '/parsers/MSRAcademicParser.php');
require(__DIR__ . '/parsers/LinkParser.php');

try {
  require(__DIR__ . '/routes.php');
} catch (Exception $e) {
  header('Content-type: application/json');
  echo json_encode(array('status' => 'failure', 'reason' => $e->getMessage()));
}
