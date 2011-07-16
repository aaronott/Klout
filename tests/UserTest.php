<?php
/**
 * Tests Klout Common
 *
 * @group klout
 *
 * @package    Klout
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2011 Aaron Ott
 */
require_once 'Klout.php';

class KloutTest extends PHPUnit_Framework_TestCase
{

  protected $Klout;
  protected $Users;
  protected $User;

  public function setUp()
  {
    $this->Klout = new Klout;
    $this->Users = array('aaronott','andreitr','danomanion');
    $this->User = 'aaronott';
  }

  /**
	 * Tests Klout
	 *
	 * Tests getting the score for a single user
	 *
	 * @test
	 */
  public function testGetUserScore()
  {
    $response = $this->Klout->getUserScore($this->User);

    // test a simple connect
    $this->assertTrue($response['status'] === 200);
  }

  /**
	 * Tests Connect
	 *
	 * Tests connection by sending through a call with an invalid key
	 *
	 * @test
	 */
  public function testFailedConnect()
  {
    $apikey = Klout::$apikey;
    Klout::$apikey .= 'x';
    $this->Customer->all();
    $connect = $this->Customer->callInfo;
    
    // test a simple connect
    $this->assertTrue($connect['http_code'] === 401);
    
    Klout::$apikey = $apikey;
  }
}
?>
