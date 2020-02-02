<?php

namespace imonroe\robokata;

use \Dotenv\Dotenv;

class RoboKataPrototype extends \Robo\Tasks {

    protected $dotenv;

    public function __construct()
    {

    }

    public function env($key)
    {
        return getenv($key);
    }


    /**
     * Ask a question to the user.
     *
     * @param string $question
     *   The question to ask.
     * @param string $default
     *   Default value.
     * @param bool $required
     *   If a response is required.
     *
     * @return string
     *   Response to the question.
     */
    protected function askQuestion($question, $default = '', $required = FALSE) {
        if ($default) {
        $response = $this->askDefault($question, $default);
        }
        else {
        $response = $this->ask($question);
        }
        if ($required && !$response) {
        return $this->askQuestion($question, $default, $required);
        }
        return $response;
    }

    /**
     * Return a copy of this application to execute another command. This makes it stackable.
     * Usage: $this->robokata()->rawArg('list')
     *
     *
     */
    protected function robokata(){
        return $this->taskExec('php robokata.php')
            ->option('verbose')
            ->option('no-interaction');
    }

    /**
     * Provides a simple wrapper around exec() which returns the full output of the shell command
     * as a single string.  Will throw an exception if something goes wrong.
     *
     * @param String $cmd The shell command to execute.
     * @return String The output of the shell command execution
     */
    protected function execWithMessage($cmd) {
        $output = '';
        $diff_msg = [];
        exec($cmd, $diff_msg, $status);
        if ( (int)$status == 0 ){
            foreach ($diff_msg as $line){
                $output .= $line . PHP_EOL;
            }
            return $output;
        } else {
            throw new \Exception('execWithMessage() returned a status of '.$status.' when it tried to execute a command.');
        }
    }

}
