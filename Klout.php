<?php
/**
 * API Implementation for Klout
 *
 * @author      Aaron Ott <aaron.ott@gmail.com>
 * @copyright   2010 Aaron Ott
 * @link http://developer.klout.com/api_gallery
 *
 * @see http://developer.klout.com/page
 */
class Klout {

  private $APIKEY = '';

  protected $uri = 'https://api.klout.com';
  protected $version = 1;


  public $lastCall = '';
  public $lastResponse = '';
  public $callInfo = '';

  protected function send_request($endpoint, array $data=array(), $method='GET') {
    $uri = $this->uri . '/' . $this->version . '/' . $endpoint . '.json';

    $data += array(
      'key' => $this->APIKEY,
    );

    if (strtoupper($method) == 'GET') {
      $params = array();
      foreach ($data as $key => $val) {
        $params[] = $key . '=' . urlencode($val);
      }
      $uri .= '?' . implode('&', $params);
    }

    $this->lastCall = $uri;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    //curl_setopt($ch, CURLOPT_VERBOSE, TRUE);}

    $this->lastResponse = curl_exec($ch);
    $this->callInfo = curl_getinfo($ch);

    if ($this->lastResponse === FALSE) {
      throw new Klout_Exception('Curl error: ' . curl_error($ch), curl_errno($ch));
    }

    if ($this->callInfo['http_code'] != 200) {
      throw new Klout_Response_Exception('HTTP_CODE != 200: ' . $this->callInfo['http_code']);
    }

    curl_close($ch);
    return $this->_parse();
  }

  protected function _parse() {
    return json_decode($this->lastResponse);
  }

  private function _responseMessage($code) {
    $messages = array(
      200 => "OK: Success",
      202 => "Accepted:"
    );
  }

  /**
   * Get the Klout score for a single user
   */
  public function getUserScore($username) {
    return $this->getUsersScore(array($username));
  }

  public function getUsersScore(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('klout', $users);
  }

  /**
   * Get the Klout user info
   */
  public function getUser($username) {
    return $this->getUsers(array($username));
  }

  public function getUsers(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('users/show', $users);
  }

  /**
   * Get the Klout user Topics
   */
  public function getUserTopics($username) {
    return $this->getUsersTopics(array($username));
  }

  public function getUsersTopics(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('users/topics', $users);
  }

  /**
   * Get the Klout user Relationships
   */
  public function getInfluencedBy($username) {
    return $this->getMultipleInfluencedBy(array($username));
  }

  public function getMultipleInfluencedBy(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('soi/influenced_by', $users);
  }

  /**
   * Get the Klout user Relationships
   */
  public function getInfluencerOf($username) {
    return $this->getMultipleInfluencerOf(array($username));
  }

  public function getMultipleInfluencerOf(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('soi/influencer_of', $users);
  }

}

class Klout_Exception extends Exception {}
class Klout_Response_Exception extends Exception {}

$klout = new Klout;

/**
$users = array('andreitr','andreitr','TechGirlGeek','webchick','sirkitree');
$result = $klout->getMultipleInfluencerOf($users);
print_r($result);
**/

$users = 'aaronott';
$result = $klout->getInfluencerOf($users);
print_r($result);

