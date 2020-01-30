<?php

namespace Daemon\Cli;

require_once 'Daemon.php';

use Daemon\Main\Daemon;

class CliInterface extends Daemon
{

    public function __construct()
    {
        $this->_showChoicePar();
    }

    private function _showChoicePar()
    {
        echo "1. Показать активные демоны" . PHP_EOL;
        echo "2. Добавить демона" . PHP_EOL;
        echo "3. Удалить демона" . PHP_EOL;

    }

    private function _userChoiceError($userChoice)
    {
        if ($userChoice == 0) {
            system("clear");
            echo "Необходимо ввести число" . PHP_EOL;
            sleep(2);
            system("clear");
            $this->_showChoicePar();
            return false;
        } else {

            return true;

        }

    }

    private function _waitUser()
    {
        echo "\nНажмите любую кнопку для продолжения..." . PHP_EOL;
        readline();
        $this->_showChoicePar();

    }

    public function execUserChoice($userChoice)
    {
        if (!$this->_userChoiceError($userChoice))
            return false;

        switch ($userChoice) {

            case 1:
                $this->showAllDaemons();
                $this->_waitUser();
                return true;

            case 2:
                $this->startDaemon(readline('Введите имя файла: '), readline('Комментарий: '));
                $this->_waitUser();
                return true;

            case 3:
                $this->killDaemon(readline('Введите pid демона: '));
                $this->_waitUser();
                return true;

        }

    }


}

$cli = new CliInterface();
while (true) {

    $cli->execUserChoice((int)readline("Введите пунк меню: "));

}
