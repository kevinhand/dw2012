<?php
fSession::setBackend($cache, 'MYSESS');
fSession::setLength('1 day');
fSession::open(); // it clears all headers and will be destroyed if not necessary

$mongo = new Mongo();
$mongodb = $mongo->dw2012;
