<?php
class LinkParser
{
  public function accepts($url)
  {
    return TRUE;
  }
  
  public function parse($url)
  {
    $uuid = sha1($url);
    $snapshot_path = SNAPSHOTS_DIR . "/{$uuid}.png";
    $this->title = exec(PHANTOMJS_BIN . ' ' . RASTERIZE_JS . ' ' . $url . ' ' . $snapshot_path);
    $this->snapshot_url = SNAPSHOTS_URL . "/{$uuid}.png";
    return array(
      'type' => 'link',
      'title' => $this->title,
      'snapshot_url' => $this->snapshot_url
    );
  }
}

UrlParser::register('LinkParser');
