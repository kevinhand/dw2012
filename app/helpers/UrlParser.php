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
    global $cache;
    $cache_key = sha1($url);
    $cache_value = $cache->get($cache_key);
    if ($cache_value === NULL) {
      $cache_value = array();
      foreach (self::$parser_names as $parser_name) {
        $parser = new $parser_name;
        if ($parser->accepts($url)) {
          $cache_value = $parser->parse($url);
          break;
        }
      }
      $cache->set($cache_key, $cache_value);
    }
    return $cache_value;
  }
}
