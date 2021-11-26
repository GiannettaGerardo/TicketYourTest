<?php

namespace App\Providers;

use App\View\RegisterView\formCittadino;
use App\View\RegisterView\formDatore;
use App\View\RegisterView\formLaboratorio;
use App\View\RegisterView\formMedico;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::componentNamespace('RegisterView', 'register');
        /*Blade::componentNamespace('RegisterView', 'register');
        Blade::componentNamespace('RegisterView\\form-laboratorio', 'register');
        Blade::componentNamespace('RegisterView\\form-medico', 'register');*/
    }
}
