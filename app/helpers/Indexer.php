<?php
class Indexer
{
  public static function buildKeywords(&$note)
  {
    $keywords = array();
    foreach ($note as $key => $value) {
      if (is_string($value)) {
        $keywords = array_merge($keywords, array_unique(array_map('strtolower', array_filter(array_map('trim', explode(' ', $value)), 'strlen'))));
      } else if (is_array($value)) {
        $keywords = array_merge($keywords, array_unique(array_map('strtolower', array_filter($value, 'strlen'))));
      }
    }
    $note['keywords'] = $keywords;
  }
}
