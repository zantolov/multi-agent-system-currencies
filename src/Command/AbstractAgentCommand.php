<?php

namespace Command;


use GearmanClient;
use GearmanWorker;
use Symfony\Component\Console\Command\Command;

abstract class AbstractAgentCommand extends Command
{
    protected $client = null;

    protected $worker = null;

    /**
     * @return GearmanClient
     */
    public function getClient()
    {
        if (empty($this->client)) {
            $this->client = new GearmanClient();
            $this->client->addServer('gearman');
        }

        return $this->client;
    }

    /**
     * @return GearmanWorker
     */
    public function getWorker()
    {
        if (empty($this->worker)) {
            $this->worker = new GearmanWorker();
            $this->worker->addServer('gearman');
        }

        return $this->worker;
    }

}