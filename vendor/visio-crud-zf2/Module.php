<?php
/**
 * @todo add module information
 */
namespace VisioCrudModeler;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    /*
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ConsoleBannerProviderInterface::getConsoleBanner()
     */
    public function getConsoleBanner(\Zend\Console\Adapter\AdapterInterface $console)
    {
        return "HyPHPers visio crud ZF2 v.alpha";
    }
    /*
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ConsoleUsageProviderInterface::getConsoleUsage()
     */
    public function getConsoleUsage(\Zend\Console\Adapter\AdapterInterface $console)
    {
        return array(
            'Available commands',
            'list generators'=>'Lists all available generators to use',
            'generate <generator>'=>"runs given generator, you could use also 'all' and it will run all available generators",
            'optionall parameters supported by generators',
            array('--author=AUTHOR','phpdoc author','Name used in PHPDocumentor tag @author where available'),
            array('--copyright=COPYRIGHT','phpdoc copyright','Name used in PHPDocumentor tag @copyright where available'),
            array('--project=PROJECT','phpdoc project','Name used in PHPDocumentor tag @project where available'),
            array('--license=LICENSE','phpdoc license','Name used in PHPDocumentor tag @license where available'),
            array('--modulesDirectory=PATH','relative path','Relative path to ZF2 modules directory, needs to be writable. Generator will write all files to it.'),
            array('--moduleName=NAME','ZF2 module name','Name for generated module. will be used as Namespace root and module directory name'),
            array('--adapterServiceKey=NAME','service name','Name used to retrieve data adapter, used by descriptors to discover Data Source structure'),
            array('--descriptor=NAME','descriptor name','Name of descriptor used in generators')
        );
    }
}
