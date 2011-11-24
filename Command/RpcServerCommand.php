<?php

namespace OldSound\RabbitMqBundle\Command;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class RpcServerCommand extends BaseRabbitMqCommand
{

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('rabbitmq:rpc-server')
            ->addArgument('name', InputArgument::REQUIRED, 'Server Name')
            ->addOption('debug', 'd', InputOption::VALUE_OPTIONAL, 'Debug mode', false)
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        define('AMQP_DEBUG', (bool) $input->getOption('debug'));

        $server = $this->getContainer()
                       ->get(sprintf('old_sound_rabbit_mq.%s_server', $input->getArgument('name')));


        if(!($server instanceof ContainerAwareInterface)) {
           throw new Exception("The consumer callback has to implement the ContainerAwareInterface");
        }

        $server->setContainer($this->getContainer());
        $server->start();
    }
}