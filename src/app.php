#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';
set_time_limit(0);

define('__PATH_DATA__', realpath(__DIR__.'/../data'));
define('__PATH_CONFIGURATION__', __PATH_DATA__.'/configuration');

use App\Application\Application;
use App\Command\AddContainerCommand;
use App\Command\LaunchContainerCommand;
use App\Command\ListContainersCommand;
use App\Command\StopContainerCommand;

$application = new Application(__DIR__.'/App/config/services.xml');
$application->addContainerCommand(new ListContainersCommand());
$application->addContainerCommand(new AddContainerCommand());
$application->addContainerCommand(new LaunchContainerCommand());
$application->addContainerCommand(new StopContainerCommand());
$application->run();
