<?php

namespace app\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $models = array(
            'User',
            'Shop',
            'Role',
            'Student',
            'Branch',
            'Dep',
<<<<<<< HEAD
            'Position',
=======
            'Shift',
            'Empshift'
>>>>>>> 4289207273aa9d67b68f6295bdc9b6384e035954
        );

        foreach ($models as $model) {
            $this->app->bind("App\\Api\\Repositories\\Contracts\\{$model}Repository", "App\\Api\\Repositories\\Eloquent\\{$model}RepositoryEloquent");
        }
    }
}
