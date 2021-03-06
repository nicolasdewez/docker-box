#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';
set_time_limit(0);

define('__PATH_DATA__', realpath(__DIR__.'/../data'));
define('__PATH_CONFIGURATION__', __PATH_DATA__.'/configuration');

use App\Command\AddContainerCommand;
use App\Command\ConfigurationContainerCommand;
use App\Command\InspectContainerCommand;
use App\Command\ListContainersCommand;
use App\Command\StartContainerCommand;
use App\Command\StatusContainerCommand;
use App\Command\StopContainerCommand;
use Ndewez\ApplicationConsoleBundle\Application\Application;

$application = new Application(__DIR__.'/App/config/services.xml');
$application->addContainerCommand(new AddContainerCommand());
$application->addContainerCommand(new ConfigurationContainerCommand());
$application->addContainerCommand(new InspectContainerCommand());
$application->addContainerCommand(new ListContainersCommand());
$application->addContainerCommand(new StartContainerCommand());
$application->addContainerCommand(new StatusContainerCommand());
$application->addContainerCommand(new StopContainerCommand());
$application->run();
