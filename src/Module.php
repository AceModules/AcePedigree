<?php

namespace AcePedigree;

class Module
{
    public function getModuleDependencies()
    {
        return [
            'AssetManager',
            'DoctrineORMModule',
            'TwbsHelper',
            'AceDbTools',
            'AceDatagrid',
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
