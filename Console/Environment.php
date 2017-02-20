<?php
namespace Hammer\ConfigManager\Console;
use Magento\Framework\App\DeploymentConfig\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\framework\Config\File\ConfigFilePool;

/**
 * Class Environment
 * @package Hammer\ConsoleCommands\Console
 */
class Environment extends Command
{
    /**
     * Name argument
     */
    const NAME_ARGUMENT = 'environment';
    /**
     * @var Writer
     */
    private $deploymentConfig;

    /**
     * Environment constructor.
     * @param Writer $deploymentConfig
     */
    public function __construct(
        Writer $deploymentConfig
    )
    {
        $this->deploymentConfig = $deploymentConfig;
        parent::__construct();
    }

 
    protected function configure()
    {
        $this->setName('hammer:environment:set')
            ->setDescription('Set up the environment which you are working on')
            ->setDefinition([
                new InputArgument(
                    self::NAME_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'environment [local, dev, stage, prepod, prod]'
                ),
            ]);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $input->getArgument(self::NAME_ARGUMENT);
        if (is_null($environment)) {
                throw new \InvalidArgumentException('Argument ' . self::NAME_ARGUMENT . ' is missing.');
        }
        if (($environment != 'local') && ($environment != 'dev') && ($environment != 'stage') && ($environment != 'preprod') && ($environment != 'prod')) {
            throw new \InvalidArgumentException('Argument ' . self::NAME_ARGUMENT . ' should be local, dev, stage, prepod or prod.');
        }

            $this->deploymentConfig->saveConfig([ConfigFilePool::APP_ENV => ['hammer_environment' => $environment]]);
        $output->writeln("<info>your environment is set as {$environment} </info>");

    }
}