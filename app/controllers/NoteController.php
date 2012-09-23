<?php
class NoteController extends ApplicationController
{
  public function authenticate($user_id, $token)
  {
    global $mongodb;
    $collection = $mongodb->users;
    $this->user = $collection->findOne(array('_id' => new MongoId($user_id), 'token' => $token));
    if ($this->user === NULL) {
      $this->failure("authentication failed");
      exit(1);
    }
  }
  
  public function parseTitle($title)
  {
    $parser = new TitleParser();
    $this->success($parser->parse($title));
  }
  
  public function parseUrl($url)
  {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $parser = new UrlParser();
      $this->success($parser->parse($url));
    } else {
      $this->failure('invalid url');
    }
  }
  
  public function createNote($note)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $note['user_email'] = $this->user['email'];
    Indexer::buildKeywords($note);
    $collection->insert($note);
    $this->success(array('id' => $note['_id']->{'$id'}));
  }
  
  public function searchNote($q, $me)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $collection->ensureIndex('keywords');
    $keywords = array_unique(array_map('strtolower', array_filter(array_map('trim', explode(' ', $q)), 'strlen')));
    $cursor = $collection->find(array('keywords' => array('$all' => $keywords)));
    $notes = array();
    foreach (iterator_to_array($cursor) as $id => $note) {
      $note['id'] = $id;
      $note['note'] = Markdown($note['note']);
      $notes[] = $note;
    }
    $this->success(array('notes' => $notes));
  }
  
  public function updateNote($id, $note)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $note['user_email'] = $this->user['email'];
    Indexer::buildKeywords($note);
    $collection->update(array('_id' => new MongoId($id)), $note);
    $this->success();
  }
  
  public function deleteNote($id)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $collection->remove(array('_id' => new MongoId($id)));
    $this->success();
  }
}
