<?php

namespace AcePedigree;

use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function getModuleDependencies()
    {
        return [
            'AssetManager',
            'DoctrineORMModule',
            'TwbBundle',
            'AceDbTools',
            'AceDatagrid',
        ];
    }

    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);

        unset($config['zenddevelopertools']);

        $configListener->setMergedConfig($config);
    }
}
