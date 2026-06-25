<?php
return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Models\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
        'int' => \Middlewares\OnlyNumInParameter::class,
        'admin' => \Middlewares\OnlyAdministratorMiddleware::class,
    ],
    'routeAppMiddleware' => [
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
    ],
    'validators' => [
        'required' => \Validators\RequireValidator::class,
        'min' => \Validators\MinSymbolsValidator::class,
        'max' => \Validators\MaxSymbolsValidator::class,
        'unique' => \Validators\UniqueValidator::class,
        'exists' => \Validators\ValueExistsValidator::class,
        'regex' => \Validators\RawRegexValidator::class,
    ],
    'devLog' => __DIR__ . '/../logs/dev.log',
    'createdPagesPrefix' => 'site.created.'
];