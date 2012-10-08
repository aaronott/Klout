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

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), 1);
  }

  /**
	 * Tests Klout
	 *
	 * Tests getting the score for multiple users
	 *
	 * @test
	 */
  public function testGetUsersScore()
  {
    $response = $this->Klout->getUsersScore($this->Users);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), count($this->Users));
  }

  /**
	 * Tests Klout
	 *
	 * Tests getting the information for a single user
	 *
	 * @test
	 */
  public function testGetUser()
  {
    $response = $this->Klout->getUser($this->User);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), 1);
    $this->assertSame($response->users[0]->twitter_screen_name, $this->User);
  }

  /**
	 * Tests Klout
	 *
	 * Tests getting the information for multiple users
	 *
	 * @test
	 */
  public function testGetUsers()
  {
    $response = $this->Klout->getUsers($this->Users);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), count($this->Users));
    $this->assertSame($response->users[0]->twitter_screen_name, $this->Users[0]);
  }

  /**
	 * Tests getting the Topics of expertise for a single user
	 *
	 * @test
	 */
  public function testGetUserTopics()
  {
    $response = $this->Klout->getUserTopics($this->User);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), 1);
    $this->assertSame($response->users[0]->twitter_screen_name, $this->User);
    $this->assertObjectHasAttribute('topics', $response->users[0]);
  }

  /**
	 * Tests getting the Topics of expertise for multiple users
	 *
	 * @test
	 */
  public function testGetUsersTopics()
  {
    $response = $this->Klout->getUsersTopics($this->Users);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), count($this->Users));
    $this->assertSame($response->users[0]->twitter_screen_name, $this->Users[0]);
    $this->assertObjectHasAttribute('topics', $response->users[0]);
  }

  /**
	 * Tests getting the users that influence the passed user
	 *
	 * @test
	 */
  public function testGetInfluencedBy()
  {
    $response = $this->Klout->getInfluencedBy($this->User);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), 1);
    $this->assertSame($response->users[0]->twitter_screen_name->screen_name, $this->User);
    $this->assertObjectHasAttribute('influencers', $response->users[0]);
  }

  /**
	 * Tests getting the users that influence the passed user
	 *
	 * @test
	 */
  public function testGetMultipleInfluencedBy()
  {
    $response = $this->Klout->getMultipleInfluencedBy($this->Users);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), count($this->Users));
    $this->assertSame($response->users[0]->twitter_screen_name->screen_name, $this->Users[0]);
    $this->assertObjectHasAttribute('influencers', $response->users[0]);
  }

  /**
	 * Tests getting the users that influence the passed user
	 *
	 * @test
	 */
  public function testGetInfluencerOf()
  {
    $response = $this->Klout->getInfluencerOf($this->User);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), 1);
    $this->assertSame($response->users[0]->twitter_screen_name->screen_name, $this->User);
    $this->assertObjectHasAttribute('influencers', $response->users[0]);
  }

  /**
	 * Tests getting the users that influence the passed user
	 *
	 * @test
	 */
  public function testGetMultipleInfluencerOf()
  {
    $response = $this->Klout->getMultipleInfluencerOf($this->Users);

    $this->assertTrue($response->status === 200);
    $this->assertObjectHasAttribute('users', $response);
    $this->assertEquals(count($response->users), count($this->Users));
    $this->assertSame($response->users[0]->twitter_screen_name->screen_name, $this->Users[0]);
    $this->assertObjectHasAttribute('influencers', $response->users[0]);
  }
}
?>
