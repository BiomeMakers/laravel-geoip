<?php

return Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests'
    ])
    ->withPhpVersion(Rector\ValueObject\PhpVersion::PHP_84)
    ->withSets([
        Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_84,
    ]);
