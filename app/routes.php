<?php
$app = new Slim();

// Login with Google
$app->get('/auth/google', function () {
  require(__DIR__ . '/auth/google.php');
});

// Get paper info from title
$app->get('/parse/title', function () {
  $controller = new NoteController();
  $controller->parseTitle(trim(fRequest::get('title')));
});

// Parse url
$app->get('/parse/url', function () {
  $controller = new NoteController();
  $controller->parseUrl(trim(fRequest::get('url')));
});

// Create note
$app->post('/notes', function () {
  $controller = new NoteController();
  $controller->authenticate(fRequest::get('user_id'), fRequest::get('token'));
  $controller->createNote(fRequest::get('data'));
});

// Random notes
$app->get('/notes/random', function () {
  $controller = new NoteController();
  $controller->randomNotes();
});

// Search notes
$app->get('/notes', function () {
  $controller = new NoteController();
  $controller->searchNote(trim(fRequest::get('q')), fRequest::get('me'));
});

// Tags
$app->get('/tags', function () {
  $controller = new NoteController();
  $controller->listMyTags(fRequest::get('me'));
});

// Edit and update note
$app->post('/note/:id/_update', function ($id) {
  $controller = new NoteController();
  $controller->authenticate(fRequest::get('user_id'), fRequest::get('token'));
  $controller->updateNote($id, json_decode(fRequest::get('data')));
});

// Remove note
$app->post('/note/:id/_delete', function ($id) {
  $controller = new NoteController();
  $controller->authenticate(fRequest::get('user_id'), fRequest::get('token'));
  $controller->deleteNote($id);
});

$app->run();
