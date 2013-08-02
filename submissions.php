<?php
require 'config.inc.php';

header('Content-Type: application/json');

$url = "https://{$subdomain}.wufoo.com/api/v3/forms/{$formHash}/entries.json";

$entries = array("entries" => array());

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_USERPWD, $apiKey .':footastic');
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT, 'Banana Script');

$response = curl_exec($curl);
$resultStatus = curl_getinfo($curl);

if($resultStatus['http_code'] == 200) {
  $_entries = json_decode($response, TRUE);

  foreach ($_entries['Entries'] as $entry) {
    $_entry = array(
      "name" => $entry['Field9'],
      "title" => $entry['Field226'],
      "eventPreference" => eventPreferences($entry),
      "summary" => nl2br($entry['Field113'])
    );
    $entries['entries'][] = $_entry;
  }
  echo json_encode($entries);
} else {
  echo json_encode(array('error' => TRUE));
}


function eventPreferences($entry=null){
  $_prefs = array();

  # March
  if ($entry['Field12']) {
    $_prefs[] = $entry['Field12'];
  }

  # June
  if ($entry['Field13']) {
    $_prefs[] = $entry['Field13'];
  }

  # September SPARKcon
  if ($entry['Field14']) {
    $_prefs[] = $entry['Field14'];
  }

  # PKN Kids SPARKcon
  if ($entry['Field17']) {
    $_prefs[] = $entry['Field17'];
  }

  # December
  if ($entry['Field15']) {
    $_prefs[] = $entry['Field15'];
  }

  # No Preference
  if ($entry['Field16']) {
    $_prefs[] = $entry['Field16'];
  }

  return implode(', ', $_prefs);
}