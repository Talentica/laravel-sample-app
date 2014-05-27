<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 26/05/14
 * Time: 6:00 PM
 */

trait UserEloquentTraits
{

    public function get_id()
    {
        return $this->user->id;
    }

    public function get_tasklists()
    {
        return $this->user->tasklists;
    }

}