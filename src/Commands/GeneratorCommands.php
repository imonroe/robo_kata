<?php

namespace imonroe\robokata\Commands;

class GeneratorCommands extends \imonroe\robokata\RoboKataPrototype
{

    /**
     * @command generator:robokata-command
     */
    public function robokataCommandGenerate(){
        $this->say("Dude, let's make a new Command class.");
        $class_name_lower = trim(strtolower($this->askQuestion('What is the name of the command class? (e.g., \'repo\')', 'default', true)));
        $class_name_capitalized = ucfirst($class_name_lower);
        $file_name = $_ENV['APP_BASE_DIR'] . '/src/Commands/' .$class_name_capitalized . 'Commands.php';
        $template_file = $_ENV['TEMPLATE_BASE_DIR'] . '/generators/robokata.php.tpl';


        $this->taskExec('touch')->rawArg($file_name)->run();

        $collection = $this->collectionBuilder();
        $collection->taskWriteToFile($file_name)
            ->textFromFile($template_file)
            ->replace('{{ClassNameCapitalized}}', $class_name_capitalized)
            ->replace('{{ClassNameLower}}', $class_name_lower);

        if ($collection->run()->wasSuccessful()){
            $this->say("It's all good in the hood, bro.");
        } else {
            $this->say("Yo, something went wrong, bro.");
        }
    }

}
