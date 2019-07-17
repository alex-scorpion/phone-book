<?php

use Core\Application;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';
require 'core/Support/helpers.php';

/** @var Application $app */
$app = require "bootstrap/app.php";

(new SapiEmitter())->emit($app->process(ServerRequestFactory::fromGlobals()));