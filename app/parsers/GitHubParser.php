<?php
class GitHubParser
{
  public function accepts($url)
  {
    return preg_match('@^(?:https?://)?github\.com/(?P<user>[^/]+)/(?P<repo>[^/]+)@', $url, $this->matches);
  }
  
  public function parse($url)
  {
    $repo = json_decode(file_get_contents("https://api.github.com/repos/{$this->matches['user']}/{$this->matches['repo']}"), TRUE);
    return array(
      'type' => 'project',
      'description' => $repo['description'],
      'homepage' => Util::repairUrl($repo['homepage'])
    );
  }
}

UrlParser::register('GitHubParser');
