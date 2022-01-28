<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'form_prenotazione_visualizzabile' => [
            \App\Http\Middleware\CittadinoDatoreMedicoMid::class,
            \App\Http\Middleware\LaboratorioScelto::class
        ],

        'form_prenotazione_dipendenti_visualizzabile' => [
            \App\Http\Middleware\DatoreLavoroMid::class,
            \App\Http\Middleware\LaboratorioScelto::class
        ],

        'questionario_compilato_visualizzabile' => [
            \App\Http\Middleware\QuestionarioCompilatoMid::class,
            \App\Http\Middleware\LaboratorioMid::class
        ],

        'form_checkout_visualizzabile' => [
            \App\Http\Middleware\CittadinoDatoreMedicoMid::class,
            \App\Http\Middleware\FormCheckoutMid::class

        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'utente_registrato' => \App\Http\Middleware\AutenticazioneBase::class,
        'admin_registrato' => \App\Http\Middleware\AdminMid::class,
        'datore_registrato' => \App\Http\Middleware\DatoreLavoroMid::class,
        'medico_registrato' => \App\Http\Middleware\MedicoMedicinaGeneraleMid::class,
        'cittadino_registrato' => \App\Http\Middleware\CittadinoMid::class,
        'laboratorio_registrato' => \App\Http\Middleware\LaboratorioMid::class,
        'cittadino_datore_medico_registrato' => \App\Http\Middleware\CittadinoDatoreMedicoMid::class,
        'login_effettuato' => \App\Http\Middleware\LoginMid::class,
        'laboratorio_scelto' => \App\Http\Middleware\LaboratorioScelto::class,
        'form_questionario_anamnesi_visualizzabile' => \App\Http\Middleware\FormQuestionarioAnamnesiMid::class,
        'api_tyt' => \App\Http\Middleware\APImid::class
    ];
}
