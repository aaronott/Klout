<?php
/**
 * API Implementation for Klout
 *
 * @author      Aaron Ott <aaron.ott@gmail.com>
 * @copyright   2011 Aaron Ott
 * @link http://developer.klout.com/api_gallery
 *
 * @see http://developer.klout.com/page
 */
class Klout {

  /**
   * API-Key required to make the calls
   *
   * @access private
   */
  private $APIKEY = '';

  /**
   * URI to call for service
   *
   * @access protected
   */
  protected $uri = 'https://api.klout.com';

  /**
   * Version of the API
   *
   * @access private
   */
  private $version = 1;


  public $lastCall = '';
  public $lastResponse = '';
  public $callInfo = '';

  /**
   * Send the HTTP Request to gather the information from the API
   *
   * @access protected
   * @param
   *   String endpoint of the API Call ('klout')
   *
   * @param  
   *   An array of data to be passed to the API. Currently this is only a list
   *   of usernames
   *
   * @throws
   *   Klout_Exception
   *
   * @returns
   *   Object containing response data
   */
  protected function send_request($endpoint, array $data=array()) {
    $uri = $this->uri . '/' . $this->version . '/' . $endpoint . '.json';

    $data += array(
      'key' => $this->APIKEY,
    );

    $params = array();
    foreach ($data as $key => $val) {
      $params[] = $key . '=' . urlencode($val);
    }
    $uri .= '?' . implode('&', $params);

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
      throw new Klout_Exception('HTTP_CODE != 200: ' . $this->callInfo['http_code']);
    }

    curl_close($ch);
    return $this->_parse();
  }

  /**
   * Parse the output and return a PHP object containing the resulting data
   */
  protected function _parse() {
    return json_decode($this->lastResponse);
  }

  /**
   * Get the Klout score for a single user.
   *
   * @param
   *   username
   */
  public function getUserScore($username) {
    return $this->getUsersScore(array($username));
  }

  /**
   * Get the Klout score for multiple users.
   *
   * @param
   *   Array list of usernames. This list can only be 5 users long per the
   *   Klout API limits. Users after 5 will be ignored.
   */
  public function getUsersScore(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('klout', $users);
  }

  /**
   * Get all Klout Information for a single user.
   *
   * @param
   *   username
   */
  public function getUser($username) {
    return $this->getUsers(array($username));
  }

  /**
   * Get all Klout Information for multiple users.
   *
   * @param
   *   Array list of usernames. This list can only be 5 users long per the
   *   Klout API limits. Users after 5 will be ignored.
   */
  public function getUsers(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('users/show', $users);
  }

  /**
   * Get the Topics of influence for a single user
   *
   * @param
   *   username
   */
  public function getUserTopics($username) {
    return $this->getUsersTopics(array($username));
  }

  /**
   * Get the Topics of influence for multiple users
   *
   * @param
   *   Array list of usernames. This list can only be 5 users long per the
   *   Klout API limits. Users after 5 will be ignored.
   */
  public function getUsersTopics(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('users/topics', $users);
  }

  /**
   * Get users that influence the passed user
   *
   * @param
   *   username
   */
  public function getInfluencedBy($username) {
    return $this->getMultipleInfluencedBy(array($username));
  }

  /**
   * Get users that influence the passed users
   *
   * @param
   *   Array list of usernames. This list can only be 5 users long per the
   *   Klout API limits. Users after 5 will be ignored.
   */
  public function getMultipleInfluencedBy(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('soi/influenced_by', $users);
  }

  /**
   * Get the Klout user Relationships
   *
   * @param
   *   username
   */
  public function getInfluencerOf($username) {
    return $this->getMultipleInfluencerOf(array($username));
  }

  /**
   * Get users that influence the passed users
   *
   * @param
   *   Array list of usernames. This list can only be 5 users long per the
   *   Klout API limits. Users after 5 will be ignored.
   */
  public function getMultipleInfluencerOf(array $usernames) {
    $users = array('users' => implode(',', $usernames));
    return $this->send_request('soi/influencer_of', $users);
  }

}

/**
 * Exception class
 */
class Klout_Exception extends Exception {}
