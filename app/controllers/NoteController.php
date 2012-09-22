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
    // TODO
  }
  
  public function parseUrl($url)
  {
    // TODO
  }
  
  public function createNote($note)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $note['user_email'] = $this->user['email'];
    $collection->insert($note);
    $this->success(array('id' => $note['_id']->{'$id'}));
  }
  
  public function searchNote($q, $me)
  {
    // TODO
  }
  
  public function updateNote($id, $note)
  {
    global $mongodb;
    $collection = $mongodb->notes;
    $note['user_email'] = $this->user['email'];
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
