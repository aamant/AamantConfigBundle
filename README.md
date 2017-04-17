# README

## Install

Install with composer

```composer require aamant/config-bundle```

Modify AppKernel.php

    $bundles = [
        ...
        new Aamant\ConfigBundle\AamantConfigBundle(),
        ...
    ];
    
Add routes

    aamant_config_config:
        prefix: /config
        resource: "@AamantConfigBundle/Resources/config/routing.xml"
        
        
## Use service

    $service = $this->getContainer()->get('aamant_config.config');
    
### Use add, get, has, delete
    
    $config->set('version', '0.0.1')
    // hidden attribute
    $config->set('version', '0.0.1', true)
    // get
    $config->get('version')
    // has
    $config->has('version')
    // delete
    $config->delete('version')
    
## Translate

use ```config``` domain for translate name of the attribute 
    
## Manage attributes values

    http://my-app/app_dev.php/config
    