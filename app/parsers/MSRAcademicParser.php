<?php
class MSRAcademicParser
{
  public function accepts($url)
  {
    return preg_match('@^(?:https?://)?academic\.research\.microsoft\.com/Publication/(?P<id>\d+)@', $url, $this->matches);
  }
  
  public function parse($url)
  {
    $id = $this->matches['id'];
    $bibtex = file_get_contents("http://academic.research.microsoft.com/{$id}.bib?type=2&format=0&download=1");
    $this->title = '';
    if (preg_match('@title\s*=\s*{+(?P<title>[^}]+)}@', $bibtex, $matches)) {
      $this->title = $matches['title'];
    }
    $this->authors = '';
    if (preg_match('@author\s*=\s*{(?P<author>[^}]+)}@', $bibtex, $matches)) {
      $this->authors = $matches['author'];
    }
    $this->year = '';
    if (preg_match('@year\s*=\s*{(?P<year>[^}]+)}@', $bibtex, $matches)) {
      $this->year = $matches['year'];
    }
    $this->conference = '';
    if (preg_match('@booktitle\s*=\s*{(?P<booktitle>[^}]+)}@', $bibtex, $matches)) {
      $this->conference = $matches['booktitle'];
    }
    if (preg_match('@journal\s*=\s*{(?P<journal>[^}]+)}@', $bibtex, $matches)) {
      $this->conference = $matches['journal'];
    }
    if (preg_match('@conference\s*=\s*{(?P<conference>[^}]+)}@', $bibtex, $matches)) {
      $this->conference = $matches['conference'];
    }
    $this->description = '';
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

UrlParser::register('MSRAcademicParser');
