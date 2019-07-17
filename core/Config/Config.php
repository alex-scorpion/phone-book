<?php

namespace Core\Config;

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

abstract class Config
{
    public static function make(string $path): array
    {
        $aggregator = new ConfigAggregator([
            new PhpFileProvider("{$path}/*.php"),
        ]);

        return $aggregator->getMergedConfig();
    }
}
