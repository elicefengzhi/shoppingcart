<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../src/VisioCrudModeler/Public',
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'visio-crud-modeler' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/visio-crud-modeler',
                    'defaults' => array(
                        '__NAMESPACE__' => 'VisioCrudModeler\\Controller',
                        'controller' => 'Index',
                        'action' => 'modeler'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        ),
        'invokables' => array(
            'executeGenerator'=>'\VisioCrudModeler\Generator\Strategy\ExecuteGenerator'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'VisioCrudModeler\\Controller\\Index' => 'VisioCrudModeler\\Controller\\IndexController',
            'VisioCrudModeler\Controller\Console' => 'VisioCrudModeler\Controller\ConsoleController',
            'VisioCrudModeler\\Controller\\Web' => 'VisioCrudModeler\\Controller\\WebController'
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'partial/form-partial' => __DIR__ . '/../view/visio-crud-modeler/partial/form-partial.phtml',
            'visiocrudmodeler/index/index' => __DIR__ . '/../view/visio-crud-modeler/index/index.phtml',
            
            'validator/digits' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/digits.phtml',
            'validator/required' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/required.phtml',
            'validator/email' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/email.phtml',
            'validator/string-length' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/string-length.phtml',
            'validator/greater-than' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/greater-than.phtml',
            'validator/less-than' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/less-than.phtml',
            'validator/between' => __DIR__ . '/../view/visio-crud-modeler/partial/validator/between.phtml',
            
            'filter/string-trim' => __DIR__ . '/../view/visio-crud-modeler/partial/filter/string-trim.phtml',
            'filter/strip-tags' => __DIR__ . '/../view/visio-crud-modeler/partial/filter/strip-tags.phtml',
            
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'crud-list-generators' => array(
                    'options' => array(
                        'route' => 'list generators',
                        'defaults' => array(
                            'controller' => '\VisioCrudModeler\Controller\Console',
                            'action' => 'list'
                        )
                    )
                ),
                'crud-generate' => array(
                    'options' => array(
                        'route' => 'generate <generator> [--author=] [--copyright=] [--project=] [--license=] [--modulesDirectory=] [--moduleName=] [--adapterServiceKey=] [--descriptor=]',
                        'defaults' => array(
                            'controller' => '\VisioCrudModeler\Controller\Console',
                            'action' => 'generate'
                        )
                    )
                )
            )
        )
    ),
    'VisioCrudModeler' => array(
        'generators' => array(
            'module' => array(
                'adapter' => 'VisioCrudModeler\Generator\ModuleGenerator',
                'description' => 'Generates Module directory structure with necessary config files'
            ),
            'inputFilter' => array(
                'adapter' => 'VisioCrudModeler\Generator\InputFilterGenerator',
                'description' => 'Generates InputFilter classes for given model/form classes'
            ),
            'model' => array(
                'adapter' => 'VisioCrudModeler\Generator\ModelGenerator',
                'description' => 'Generates DataSource model files'
            ),
            'form' => array(
                'adapter' => 'VisioCrudModeler\Generator\FormGenerator',
                'description' => 'Generates Form classes for DataSets'
            ),
            'controller' => array(
                'adapter' => 'VisioCrudModeler\Generator\ControllerGenerator',
                'description' => 'Generates Controller classes to handle CRUD operations'
            ),
            'view' => array(
                'adapter' => 'VisioCrudModeler\Generator\ViewGenerator',
                'description' => 'Generates standrar PhpViewStrategy view template files for CRUD controllers'
            ),
            'all' => array(
                'adapter' => 'VisioCrudModeler\Generator\AllGenerator',
                'description' => 'Runs all generators according to specified dependencies'
            )
        ),
        'descriptors'=>array(
            'db'=>array(
                'adapter'=>'VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor'
            ),
            'web'=>array(
                'adapter'=>'VisioCrudModeler\Descriptor\Web\WebDataSourceDescriptor'
            )
        ),
        'dependency' => array(
            'view' => array(
                'controller'
            ),
            'controller' => array(
                'inputFilter',
                'form',
                'model'
            ),
            'form' => array(
                'module',
                'inputFilter'
            ),
            'model' => array(
                'module',
                'inputFilter'
            ),
            'inputFilter' => array(
                'module'
            ),
            'all' => array(
                'module',
                'inputFilter',
                'model',
                'form',
                'controller',
                'view'
            )
        ),
        'params' => array(
            'author' => 'VisioCrudModeler',
            'copyright' => 'HyPHPers',
            'project' => 'VisioCrudModeler generated models',
            'license' => 'MIT',
            'modulesDirectory' => getcwd() . '/module',
            'moduleName' => 'Crud',
            'adapterServiceKey' => '\Zend\Db\Adapter\Adapter',
            'descriptor' => 'db'
        )
        
    )
);
