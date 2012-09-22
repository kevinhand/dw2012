<?php
class LinkParser
{
  public function accepts($url)
  {
    return TRUE;
  }
  
  public function parse($url)
  {
    global $cache;
    
    $uuid = sha1($url);
    $snapshot_path = SNAPSHOTS_DIR . "/{$uuid}.png";
    if (file_exists($snapshot_path)) {
      $this->title = $cache->get($uuid, $url);
    } else {
      $this->title = exec(PHANTOMJS_BIN . ' ' . RASTERIZE_JS . ' ' . $url . ' ' . $snapshot_path);
      $cache->set($uuid, $this->title);
    }
    $this->snapshot_url = SNAPSHOTS_URL . "/{$uuid}.png";
    return array(
      'type' => 'link',
      'title' => $this->title,
      'snapshot_url' => $this->snapshot_url
    );
  }
}

UrlParser::register('LinkParser');
