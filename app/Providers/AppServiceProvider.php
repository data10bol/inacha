<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Blade::directive('helloWorld', function(){

            return "<?php echo 'Hello World'; ?>";
        });

        Blade::directive('statusvar', function ($expression) {

            // Make sure that the variable starts with $
            $value = ''.$expression.'';

            return '<?php
            if('.$value.')
                echo "<span class=\"badge badge-secondary\">Activo</span>";
            else
                echo "<span class=\"badge badge-danger\">Inativo</span>";
            ?>';
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
