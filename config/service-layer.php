<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Layer Paths
    |--------------------------------------------------------------------------
    |
    | Contracts are discovered recursively from the contracts path and mapped
    | to concrete services by convention. The default convention is:
    |
    | App\Services\Contracts\UserServiceContract => App\Services\UserService
    |
    */

    'services_path' => app_path('Services'),

    'contracts_path' => app_path('Services/Contracts'),

    'service_namespace' => 'App\\Services',

    'contract_namespace' => 'App\\Services\\Contracts',

    'contract_suffix' => 'Contract',

    'base_service' => 'App\\Services\\BaseService',

    /*
    | Cache file used by service-layer:cache.
    */
    'cache_path' => base_path('bootstrap/cache/service-layer.php'),
];
