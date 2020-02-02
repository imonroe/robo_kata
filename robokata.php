#!/usr/bin/env php
<?php

// If we're running from phar load the phar autoload file.
$pharPath = \Phar::running(true);
if ($pharPath) {
    $autoloaderPath = "$pharPath/vendor/autoload.php";
} else {
    if (file_exists(__DIR__.'/vendor/autoload.php')) {
        $autoloaderPath = __DIR__.'/vendor/autoload.php';
    } elseif (file_exists(__DIR__.'/../../autoload.php')) {
        $autoloaderPath = __DIR__ . '/../../autoload.php';
    } else {
        die("Could not find autoloader. Run 'composer install'.");
    }
}
$classLoader = require $autoloaderPath;

$_ENV['APP_BASE_DIR'] = __DIR__;
$_ENV['TEMPLATE_BASE_DIR'] = $_ENV['APP_BASE_DIR'] . '/templates';
$_ENV['CUSTOM_CLASSES_LOADER'] = $_ENV['APP_BASE_DIR'] .'/src/RobokataCustomClasses.php';

// From whence shall we autoload commands?
// We want to dynamically load everything in the ./src/Commands/ directory, and register each of the classes with the app.
$commandClasses = [];
$classesDir = scandir($_ENV['APP_BASE_DIR'] . '/src/Commands');
foreach( $classesDir as $class){
    if (!($class == '.') && !($class == '..')){
        $classname = str_replace('.php', '', $class);
        $classname = trim('imonroe\robokata\Commands\ ') . $classname;
        $newClass = new $classname;
        $commandClasses[] = get_class($newClass);
    }
}

// Load environment
$dotenv = \Dotenv\Dotenv::createImmutable( $_ENV['APP_BASE_DIR'] );
$dotenv->load();

// Customization variables
$appName = "robokata";
$appVersion = trim(file_get_contents(__DIR__ . '/VERSION'));

// How shall we configure ourselves?
$selfUpdateRepository = 'imonroe/robokata';
$configurationFilename = 'robokata_config.yml';


// Define our Runner, and pass it the command classes we provide.
$runner = new \Robo\Runner($commandClasses);
$runner
  ->setSelfUpdateRepository($selfUpdateRepository)
  ->setConfigurationFilename($configurationFilename)
  ->setClassLoader($classLoader);

// Execute the command and return the result.
$output = new \Symfony\Component\Console\Output\ConsoleOutput();
$statusCode = $runner->execute($argv, $appName, $appVersion, $output);
exit($statusCode);
