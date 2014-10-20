<?php
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Generator\Params;
use Zend\Console\ColorInterface;
use VisioCrudModeler\Generator\Config\Config;
use VisioCrudModeler\Generator\Strategy\ExecuteGenerator;

/**
 * controller for handling console commands
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
class ConsoleController extends AbstractActionController
{

    /**
     * handles listing
     */
    public function listAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $console = $this->getServiceLocator()->get('console');
        if (! $console instanceof \Zend\Console\Adapter\AdapterInterface) {
            throw new \RuntimeException('Cannot obtain console adapter, this action is only available from console');
        }
        $console->writeLine('Currently available generators:', ColorInterface::BLUE);
        foreach ($config['VisioCrudModeler']['generators'] as $name => $data) {
            $console->writeLine();
            $console->write($name, ColorInterface::GREEN);
            if (! empty($data['description'])) {
                $console->writeLine(" - " . $data['description']);
            }
        }
    }

    /**
     * handles running generators
     */
    public function generateAction()
    {
        $console = $this->getServiceLocator()->get('console');
        if (! $console instanceof \Zend\Console\Adapter\AdapterInterface) {
            throw new \RuntimeException('Cannot obtain console adapter, this action is only available from console');
        }
        $console->writeLine('Launching generators with params:');
        // creating params object
        $params = Params::factory($this->getRequest(), $this->getServiceLocator()->get('config')['VisioCrudModeler']['params']);
        // adding config
        $params->setParam('config', new Config($this->getServiceLocator()
            ->get('config')['VisioCrudModeler']));
        // adding console adapter
        $params->setParam('console', $console);
        // fetching strategy through ServiceLocator
        $console->writeLine('fetching strategy');
        $generatorStrategy = new ExecuteGenerator($params);
        $generatorStrategy->setServiceLocator($this->getServiceLocator());
        $console->writeLine('strategy initialized, running generators');
        $generatorStrategy->generate();
    }
}