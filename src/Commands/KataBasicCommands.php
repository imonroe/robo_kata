<?php

namespace imonroe\robokata\Commands;

class KataBasicCommands extends \imonroe\robokata\RoboKataPrototype
{
    /**
     * @command katabasic:env
     */
    public function getEnv(){
        $environment = var_export($_ENV, true);
        $this->say($environment);
    }
}
