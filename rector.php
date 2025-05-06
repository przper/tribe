<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPreparedSets(
        codeQuality: true,
        symfonyCodeQuality: true,
    )
    ->withSets([
//        \Rector\Symfony\Set\SymfonySetList::SYMFONY_70, // 2025-05-06
//        \Rector\Symfony\Set\SymfonySetList::SYMFONY_71, // 2025-05-06
    ])
    ->withTypeCoverageLevel(0);
