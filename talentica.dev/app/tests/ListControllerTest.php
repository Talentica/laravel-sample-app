<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 30/05/14
 * Time: 8:05 PM
 */

namespace Talentica\Controller;
use MyProject\Proxies\__CG__\stdClass;
use Talentica\Controller\ListController;

class ListControllerTest extends \TestCase
{
    public function testIndexCallsGetTasklist()
    {
        $userServiceMock = \Mockery::mock('UserRepository');
        \App::instance('UserRepository', $userServiceMock);
        \Auth::shouldReceive('user')->once()->andReturn(new \User());
        $userServiceMock->shouldReceive('get_tasklists')->once()->andReturn(array());
        $this->call('GET', 'api/v1/lists'
        );
    }
}