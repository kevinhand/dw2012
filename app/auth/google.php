<?php
define('GOOGLE_STATE', 'google_state');

function generate_state()
{
  $state = md5(uniqid(GOOGLE_STATE, TRUE));
  fSession::set(GOOGLE_STATE, $state);
  return $state;
}

function validate_state($state)
{
  return $state == fSession::get(GOOGLE_STATE);
}

function goto_google()
{
  $params = http_build_query(array(
    'response_type' => 'code',
    'client_id' => CLIENT_ID,
    'redirect_uri' => REDIRECT_URI,
    'scope' => 'https://www.googleapis.com/auth/userinfo.email',
    'state' => generate_state(),
  ));
  header("Location: https://accounts.google.com/o/oauth2/auth?{$params}");
  exit();
}

if (!array_key_exists('code', $_GET)) goto_google();
if (!array_key_exists('state', $_GET)) goto_google();
if (!validate_state($_GET['state'])) goto_google();

$url = 'https://accounts.google.com/o/oauth2/token';
$data = http_build_query(array(
  'code' => $_GET['code'],
  'client_id' => CLIENT_ID,
  'client_secret' => CLIENT_SECRET,
  'redirect_uri' => REDIRECT_URI,
  'grant_type' => 'authorization_code',
));
$context = stream_context_create(array('http' => array(
  'method' => 'POST',
  'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
              'Content-Length: ' . strlen($data) . "\r\n" .
              'Accept: application/json',
  'content' => $data,
)));
$json = json_decode(file_get_contents($url, FALSE, $context), TRUE);
$access_token = $json['access_token'];

$url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token={$access_token}";
$userinfo = json_decode(file_get_contents($url), TRUE);
$email = $userinfo['email'];

print $email;
