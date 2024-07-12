<?php

use Przper\Tribe\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    if ($context['REQUEST_URI'] === '/') {
        require_once __DIR__ . '/home.php';
        return;
    }

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
