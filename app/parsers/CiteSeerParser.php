<?php
class CiteSeerParser
{
  public function accepts($url)
  {
    return preg_match('@^(?:https?://)?citeseerx\.ist\.psu\.edu/viewdoc/summary\?doi=(?P<doi>[^/]+)@', $url, $this->matches);
  }
  
  public function parse($url)
  {
    $doi = $this->matches['doi'];
    $html = file_get_contents("http://citeseerx.ist.psu.edu/viewdoc/summary?doi={$doi}");
    if (preg_match('@(?:<meta name="description" content=")(?P<description>.+?)"\s*/>@', $html, $matches)) {
      $this->description = $matches['description'];
    }
    if (preg_match('@(?:<meta name="citation_title" content=")(?P<title>.+?)"\s*/>@', $html, $matches)) {
      $this->title = $matches['title'];
    }
    if (preg_match('@(?:<meta name="citation_authors" content=")(?P<authors>.+?)"\s*/>@', $html, $matches)) {
      $this->authors = $matches['authors'];
    }
    if (preg_match('@(?:<meta name="citation_year" content=")(?P<year>.+?)"\s*/>@', $html, $matches)) {
      $this->year = $matches['year'];
    }
    if (preg_match('@(?:<meta name="citation_conference" content=")(?P<conference>.+?)"\s*/>@', $html, $matches)) {
      $this->conference = $matches['conference'];
    }
    return array(
      'type' => 'paper',
      'title' => $this->title,
      'authors' => $this->authors,
      'year' => $this->year,
      'conference' => $this->conference,
      'description' => $this->description
    );
  }
}

UrlParser::register('CiteSeerParser');
