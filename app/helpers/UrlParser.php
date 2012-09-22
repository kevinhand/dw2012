<?php
class UrlParser
{
  private static $parser_names = array();
  
  public static function register($parser_name)
  {
    self::$parser_names[] = $parser_name;
  }
  
  public function parse($url)
  {
    foreach (self::$parser_names as $parser_name) {
      $parser = new $parser_name;
      if ($parser->accepts($url)) {
        return $parser->parse($url);
      }
    }
    return array();
  }
}
