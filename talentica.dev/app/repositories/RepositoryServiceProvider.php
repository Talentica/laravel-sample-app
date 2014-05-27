<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 27/05/14
 * Time: 12:52 PM
 */

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'UserRepository'
        );
    }

}