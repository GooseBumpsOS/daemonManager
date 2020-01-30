<?php


namespace Daemon\Main;

define('filename', 'daemonList.xml');


class Daemon
{

    protected function startDaemon($daemonName, $comment)
    {
        $pid = exec("nohup php -f " . $daemonName . " > /dev/null 2>&1 & echo $!", $output);
        echo "Демон запущен" . PHP_EOL;
        echo "PID: " . $pid . PHP_EOL;

        $this->_addXmlDoc($pid, $daemonName, $comment);
    }

    protected function showAllDaemons()
    {
        $daemonXml = new \SimpleXMLElement(file_get_contents(filename));

        for ($i = 0; $i < $daemonXml->count(); $i++)
        {
            echo "------------------------------------------------" . "\n";
            echo "PID - \"" . $daemonXml->item[$i]->pid . "\" | ";
            echo "Command - \"" . $daemonXml->item[$i]->command . "\" | ";
            echo "Comment - \"" . $daemonXml->item[$i]->comment . '" |' . PHP_EOL . PHP_EOL;
        }


    }

    protected function status($pid)
    {
        echo "  PID TTY         TIME CMD" . PHP_EOL;
        echo exec("ps -p " . $pid) . PHP_EOL;
    }

    protected function killDaemon($pid)
    {
        echo exec('kill ' . $pid) . PHP_EOL;
        echo "Процесс с номером " . $pid . ' удален' . PHP_EOL;

        $this->_deleteXmlDoc($pid);
    }

    private function _addXmlDoc($pid, $daemonName, $comment)
    {
        $daemonXml = new \SimpleXMLElement(file_get_contents(filename));
        $daemonXml->addChild('item');
        $daemonXml->item[$daemonXml->count() - 1]->addChild('pid', $pid);
        $daemonXml->item[$daemonXml->count() - 1]->addChild('command', "php -f " . $daemonName);
        $daemonXml->item[$daemonXml->count() - 1]->addChild('comment', $comment);
        file_put_contents(filename, $daemonXml->asXML());

    }

    private function _deleteXmlDoc($pid)
    {
        $daemonXml = new \SimpleXMLElement(file_get_contents(filename));
        for ($i = 0; $i < $daemonXml->count(); $i++)
            if ($daemonXml->item[$i]->pid == $pid)
                unset($daemonXml->item[$i]);

        file_put_contents(filename, $daemonXml->asXML());

    }


}