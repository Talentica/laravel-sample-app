<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 26/05/14
 * Time: 5:57 PM
 */

class TasklistRepository  extends CrudRepositoryEloquent
{
    private $taskList;
    public function __construct(TaskList $taskList)
    {
      $this->taskList = $taskList;
    }
    /**
     * Add custom function as necessary via traits
     */
    use TasklistEloquentTraits;
}