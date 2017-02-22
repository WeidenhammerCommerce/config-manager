#Hammer Config Manager

##How to install?

###Install via composer

1. composer config repositories.hammer-config-manager git git@git.assembla.com:weidenhammer/internal-projects.config-manager.git
2. composer require hammer/config-manager:dev-master
3. enabled the module: bin/magento module:enable Hammer_ConfigManager 
4. bin/magento setup:upgrade

###How to use it?

Set the environment you are working on (example: local, dev, stage, preprod, prod) using the following command

bin magento hammer:environment:set [local, dev, stage, preprod, prod]

This one create a new entry to env.php (app/etc/env.php)

`'hammer_environment' => 'local'`

that it is used by the Config Manager in order to know where to find the correct file with the information that correspond to each environment local configuration should be a config file in app/etc/custom_config/local/config.json

For other environments (dev, stage, preprod and prod) the file should be located in app/etc/custom_config/config.json.

All these files contains the data for the values that change in each environment.

For example:

`
{
  "Hammer_PaymentSetup::paypal/wpp/api_username": "direct-facilitator_api1.geoshack.com",
  "Hammer_PaymentSetup::paypal/wpp/api_password": "FRWE5TD5JPGX8TDG",
  "Hammer_PaymentSetup::paypal/wpp/api_signature": "AFcWxV21C7fd0v3bYYYRCpSSRl31AOsbMi0t56rdj7bZ7m9EkAVQQ1gr",
  "Hammer_PaymentSetup::paypal/wpp/sandbox_flag": 1
}
`

To use this we should inject the dependencies like

`
public function __construct(
    EncryptorInterface $encryptor,
    Config $config,
    \Praxisis\ConfigManager\Helper\ConfigManager $configManager)
    {
    //var initialization
}
`

after that we will be able to get the value like so:

`$sanboxFlag = $this->configManager->getProperty('Praxisis_PaymentSetup::paypal/wpp/sandbox_flag');`
