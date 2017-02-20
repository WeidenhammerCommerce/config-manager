<?php

namespace Hammer\ConfigManager\Helper;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

/**
 * Singleton class
 *
 */
class ConfigManager
{
    const FILE_NAME = 'config.json';
    const LOCAL_FILE_PATH = '/etc/custom_config/local/';
    const FILE_PATH = '/etc/custom_config/';
    const ENVIRONMENT_PATH = 'hammer_environment';

    protected $path;
    /**
     * @var Io
     */
    protected $fileManager;
    private $data;
    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * ConfigManager constructor.
     * @param File $fileManager
     * @param DeploymentConfig $deploymentConfig
     * @param DirectoryList $directoryList
     */
    public function __construct(
        File $fileManager,
        DeploymentConfig $deploymentConfig,
        DirectoryList $directoryList
    )
    {
        $this->fileManager = $fileManager;
        $this->deploymentConfig = $deploymentConfig;
        $this->directoryList = $directoryList;
        $baseDir = $this->directoryList->getPath('app');

        if ($this->deploymentConfig->get(self::ENVIRONMENT_PATH) == 'local') {
            $this->path = $baseDir . self::LOCAL_FILE_PATH;
        } else {
            $this->path = $baseDir . self::FILE_PATH;
        }

        $fileContent = $this->fileManager->read($this->path . self::FILE_NAME);

        if (empty($fileContent)) {
            $this->data = array();
        } else {
            $this->data = json_decode($fileContent, true);
        }
    }

    public function setProperty($key, $value)
    {
        $this->data[$key] = $value;
        $this->fileManager->write($this->path . self::FILE_NAME, json_encode($this->data));
    }

    public function getProperty($key)
    {
        if (!isset($this->data[$key])) {
            throw new \Exception("There is no config for the key passed {$key}");
        }
        return $this->data[$key];
    }
}