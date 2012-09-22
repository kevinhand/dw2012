<?php
$slim_root = __DIR__ . '/Slim/';

require($slim_root . 'Exception/Pass.php');
require($slim_root . 'Exception/RequestSlash.php');
require($slim_root . 'Exception/Stop.php');
       
require($slim_root . 'Http/Headers.php');
require($slim_root . 'Http/Request.php');
require($slim_root . 'Http/Response.php');
require($slim_root . 'Http/Util.php');
       
require($slim_root . 'Middleware/Interface.php');
require($slim_root . 'Middleware/ContentTypes.php');
require($slim_root . 'Middleware/Flash.php');
require($slim_root . 'Middleware/MethodOverride.php');
require($slim_root . 'Middleware/PrettyExceptions.php');
require($slim_root . 'Middleware/SessionCookie.php');
       
require($slim_root . 'Environment.php');
require($slim_root . 'Log.php');
require($slim_root . 'LogFileWriter.php');
require($slim_root . 'Route.php');
require($slim_root . 'Router.php');
require($slim_root . 'View.php');
require($slim_root . 'Slim.php');
