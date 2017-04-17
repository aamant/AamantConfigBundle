<?php namespace Aamant\ConfigBundle\Twig;

use Aamant\ConfigBundle\Service\Config;

/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */
class ConfigExtension extends \Twig_Extension
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('config', [$this, 'config'])
        );
    }

    public function config($name)
    {
        return $this->config->get($name);
    }

    public function getName()
    {
        return 'aamant_configbundle_config';
    }
}