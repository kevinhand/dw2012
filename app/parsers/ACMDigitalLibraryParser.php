<?php
class ACMDigitalLibraryParser
{
  public function accepts($url)
  {
    return preg_match('@^(?:https?://)?dl\.acm\.org/citation\.cfm\?doid=(?P<parent>\d+)\.(?P<id>\d+)@', $url, $this->matches);
  }
  
  public function parse($url)
  {
    $parent = $this->matches['parent'];
    $id = $this->matches['id'];
    $bibtex = file_get_contents("http://dl.acm.org/downformats.cfm?id={$id}&parent_id={$parent}&expformat=bibtex");
    if (preg_match('@title\s*=\s*{(?P<title>[^}]+)}@', $bibtex, $matches)) {
      $this->title = $matches['title'];
    }
    if (preg_match('@author\s*=\s*{(?P<author>[^}]+)}@', $bibtex, $matches)) {
      $this->authors = $matches['author'];
    }
    if (preg_match('@year\s*=\s*{(?P<year>[^}]+)}@', $bibtex, $matches)) {
      $this->year = $matches['year'];
    }
    if (preg_match('@booktitle\s*=\s*{(?P<booktitle>[^}]+)}@', $bibtex, $matches)) {
      $this->conference = $matches['booktitle'];
    }
    if (preg_match('@abstract\s*=\s*{(?P<abstract>[^}]+)}@', $bibtex, $matches)) {
      $this->description = $matches['abstract'];
    }
    if (preg_match('@keywords\s*=\s*{(?P<keywords>[^}]+)}@', $bibtex, $matches)) {
      $this->description = $matches['keywords'];
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

UrlParser::register('ACMDigitalLibraryParser');
