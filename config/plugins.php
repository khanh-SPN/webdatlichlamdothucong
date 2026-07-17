<?php

return [
    // DebugKit is loaded from Application::bootstrap() for web only.
    // Loading it in CLI causes command discovery to hang (autoload loop) with debug on.
    'Bake' => [
        'onlyCli' => true,
        'optional' => true,
    ],
    'Migrations' => [
        'onlyCli' => true,
    ],
    'Authentication' => [],
];
