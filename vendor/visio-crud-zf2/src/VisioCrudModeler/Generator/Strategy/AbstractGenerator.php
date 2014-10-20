<?php
namespace VisioCrudModeler\Generator\Strategy;

use VisioCrudModeler\Generator\ParamsInterface;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use VisioCrudModeler\Generator\Dependency;
use VisioCrudModeler\Generator\Config\Config;

/**
 * abstract strategy class implementing common features required to execute generators
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
abstract class AbstractGenerator implements ServiceLocatorAwareInterface, GeneratorInterface
{

    /**
     * holds service locator instance
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * holds dependency injection container
     *
     * @var \Zend\Di\Di
     */
    protected $di = null;

    /**
     * holds passed params object
     *
     * @var ParamsInterface
     */
    protected $params = null;

    /**
     * constructor, takes generator params interface
     *
     * @param ParamsInterface $params
     */
    public function __construct(ParamsInterface $params = null)
    {
        $this->setParams($params);
    }

    /**
     * returns generator dependency resolver
     *
     * @return \VisioCrudModeler\Generator\Dependency
     */
    public function dependency()
    {
        return new Dependency($this->params->getParam('config')['dependency']);
    }

    /**
     * sets Params
     *
     * @param ParamsInterface $params
     * @return ExecuteGenerator
     */
    public function setParams(ParamsInterface $params)
    {
        if ($params->getParam('di') instanceof \Zend\Di\Di) {
            $this->di = $params->getParam('di');
        }
        $this->params = $params;
        return $this;
    }

    /**
     * gets Params
     *
     * @return ParamsInterface
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * returns Dependency Injection container
     *
     * @return \Zend\Di\Di
     */
    public function getDi()
    {
        if (! $this->di instanceof Di) {
            $this->di = new Di();
        }
        return $this->di;
    }

    /**
     * sets Dependency Injection container
     *
     * @param Di $di
     * @return ExecuteGenerator
     */
    public function setDi(Di $di)
    {
        $this->di = $di;
        return $this;
    }
    /*
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /*
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * prints startup params
     */
    protected function printParams()
    {
        $this->console('Launch parameters');
        $displayKeys = [
            'author',
            'copyright',
            'project',
            'license',
            'modulesDirectory',
            'moduleName',
            'adapterServiceKey',
            'descriptor',
            'generator'
        ];
        foreach ($this->params->getParams() as $name => $value) {
            if (! empty($name) && in_array($name, $displayKeys)) {
                if (is_object($value)) {
                    $value = get_class($value);
                }
                $this->console($name . ': ' . $value);
            }
        }
    }

    /**
     * reads runtime config
     *
     * @return \VisioCrudModeler\Generator\Config\Config
     */
    protected function readRuntimeConfig()
    {
        if (file_exists($this->runtimeConfigPath())) {
            $config = new Config(require $this->runtimeConfigPath());
        } else {
            $config = new Config();
        }
        return $config;
    }

    /**
     * writes runtime config
     *
     * @param Config $config
     */
    protected function writeRuntimeConfig(Config $config)
    {
        $stringCode = var_export($config->toArray(), true);
        $this->console('Writing runtime configuration...');
        file_put_contents($this->runtimeConfigPath(), "<?php\nreturn " . $stringCode . ";");
        $this->console('configuration written to: ' . $this->runtimeConfigPath());
    }

    /**
     * returns runtimeConfig path according to params
     *
     * @return string
     */
    protected function runtimeConfigPath()
    {
        $path = $this->params->getParam('modulesDirectory');
        $path .= DIRECTORY_SEPARATOR . $this->params->getParam('moduleName');
        $path .= DIRECTORY_SEPARATOR . 'generatorRuntimeConfig.php';
        return $path;
    }

    /**
     * proxy method for writing to console
     *
     * @param string $message
     */
    protected function console($message)
    {
        if ($this->params->getParam('console') instanceof \Zend\Console\Adapter\AdapterInterface) {
            $this->params->getParam('console')->writeLine($message);
        }
    }

    /**
     * returns generator instance
     *
     * @param string $generatorName
     * @return \VisioCrudModeler\Generator\GeneratorInterface
     */
    public function getGenerator($generatorName)
    {
        $generators = $this->params->getParam('config')->get('generators');
        return $this->getDi()->get($generators[$generatorName]['adapter']);
    }

    /**
     * returns descriptor instance
     *
     * @return \VisioCrudModeler\Descriptor\DataSourceDescriptorInterface
     */
    public function getDescriptor()
    {
        $adapter = $this->getServiceLocator()->get($this->params->getParam('adapterServiceKey'));
        $descriptors = $this->params->getParam('config')->get('descriptors');

        $descriptorAdapterName = $this->params->getParam('descriptor');

        if (! isset($descriptors[$descriptorAdapterName])) {
            throw new \VisioCrudModeler\Exception\DescriptorAdapterNotFound("Descriptor adapter name " . $descriptorAdapterName . " doesnt exists ");
        }

        $descriptor = $descriptors[$this->params->getParam('descriptor')]['adapter'];

        return $this->getDi()->get($descriptor, array(
            'adapter' => $adapter,
            'params' => $this->params
        ));
    }
}