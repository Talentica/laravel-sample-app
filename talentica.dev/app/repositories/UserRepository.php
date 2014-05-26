<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 26/05/14
 * Time: 5:57 PM
 */

class UserRepository  extends CrudRepositoryEloquent
{
    public function __construct(User $user)
    {
        parent::__construct($user);

    }
    /**
     * Add custom function as necessary via traits
     */
    use UserEloquentTraits;
}