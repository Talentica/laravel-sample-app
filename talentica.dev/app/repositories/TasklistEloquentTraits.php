<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 27/05/14
 * Time: 4:19 PM
 */
trait TasklistEloquentTraits{


    public function findByOwnerAndId($user, $id)
    {
        return \TaskList::findByOwnerAndId($user , $id);
    }

}