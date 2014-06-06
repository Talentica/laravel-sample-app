<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 30/05/14
 * Time: 8:05 PM
 */

namespace Talentica\Controller;

class ListControllerTest extends \TestCase
{
    public function testIndexCallsGetTasklist()
    {
        $userServiceMock = \Mockery::mock('UserRepository');
        \App::instance('UserRepository', $userServiceMock);
        //Laravel facades support mockery like syntax in test mode.
        \Auth::shouldReceive('user')->once()->andReturn(new \User());
        $userServiceMock->shouldReceive('get_tasklists')->once()->andReturn(array());
        $this->call('GET', 'api/v1/lists'
        );
    }

    public function testIndexIntegration()
    {
        $user = new \User();
        $user->id = 1;
        $this->be($user);
        $response = $this->call('GET', 'api/v1/lists' );
        $this->assertEquals(200, $response->getStatusCode());
        $responseObj = json_decode($response->getContent());
        $this->assertNotNull($responseObj[0]);
        $list = $responseObj[0];
        $this->assertEquals(1, $list->id);
        $this->assertEquals('My first list', $list->name);
    }
}