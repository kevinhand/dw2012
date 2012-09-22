<?php
class StackOverflowParser
{
  public function accepts($url)
  {
    return preg_match('@^(?:https?://)?stackoverflow\.com/questions/(?P<id>[^/]+)@', $url, $this->matches);
  }
  
  public function parse($url)
  {
    $question_id = $this->matches['id'];
    $feed = new SimpleXMLElement(file_get_contents("http://stackoverflow.com/feeds/question/{$question_id}"));
    $this->replies = array();
    foreach ($feed->entry as $entry) {
      if (!isset($this->title)) {
        $this->title = $entry->title . '';
        $this->published = $entry->published . '';
        $this->tags = array();
        foreach ($entry->category as $category) {
          $this->tags[] = $category['term'] . '';
        }
        $this->summary = $entry->summary . '';
      } else {
        $this->replies[] = $entry->summary . '';
      }
    }
    return array(
      'type' => 'question',
      'title' => $this->title,
      'published' => $this->published,
      'tags' => $this->tags,
      'summary' => $this->summary,
      'replies' => $this->replies
    );
  }
}

UrlParser::register('StackOverflowParser');
