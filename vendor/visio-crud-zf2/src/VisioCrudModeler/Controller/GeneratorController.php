<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\MethodGenerator;
use VisioCrudModeler\Generator\Params;
use VisioCrudModeler\Generator\Strategy\ExecuteGenerator;

use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;
use VisioCrudModeler\Generator\Dependency;
use VisioCrudModeler\Generator\Config;
use VisioCrudModeler\Filter\CustomerFilter;
use Zend\Code\Generator\FileGenerator;

class GeneratorController extends AbstractActionController
{

    public function indexAction()
    {
        
    }
     
    
    public function controllerAction()
    {
    	
    	
    	
    	$config = $this->getServiceLocator()->get('config');
    	
    	$moduleName = $config['VisioCrudModeler']['params']['moduleName'];
    	$modulePath = $config['VisioCrudModeler']['params']['modulesDirectory'];
    	
    	$db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    	$dataSourceDescriptor=new DbDataSourceDescriptor($db, 'K08_www_biedronka_pl');
    	
    	
    	
    	$listDataSets = $dataSourceDescriptor->listDataSets();
    	$filter = new \Zend\Filter\Word\UnderscoreToCamelCase();
    	
    	
    	
    	
    	foreach ($listDataSets as $d){
    		$className = $filter->filter($d).'Controller';
    
    		
			$file = new FileGenerator();
			$file->setFilename($className);
			$file->setNamespace($moduleName)
			->setUse('Zend\Mvc\Controller\AbstractActionController');
			
    		$foo      = new ClassGenerator();
    		$docblock = DocBlockGenerator::fromArray(array(
    				'shortDescription' => 'Sample generated class',
    				'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
    				'tags'             => array(
    						array(
    								'name'        => 'version',
    								'description' => '$Rev:$',
    						),
    						array(
    								'name'        => 'license',
    								'description' => 'New BSD',
    						),
    				),
    		));
    		$foo->setName($className);
    		
    	    $foo->setExtendedClass('Base'.$className);
    		$foo->setDocblock($docblock);
    		
    		$file->setClass($foo);    		
    		
    		echo '<pre>';
    		echo htmlentities($file->generate());
    		echo '</pre>';
    		
    		
    		
    		$fileView = new FileGenerator();
    		$body ="echo '$className';";
    		
    		$fileView->setBody($body);
    		
    		echo '<pre>';
    		echo htmlentities($fileView->generate());
    		echo '</pre>';
    	}
    	
    	
    	echo '<hr />';
    	
    	foreach ($listDataSets as $d){
    		$className = 'Base'.$filter->filter($d).'Controller';
    	
    	
    		$fileBase = new FileGenerator();
    		$fileBase->setFilename($className);
    		$fileBase->setNamespace($moduleName)
    		->setUse('Zend\Mvc\Controller\AbstractActionController');
    			
    		$fooBase      = new ClassGenerator();
    		$docblockBase = DocBlockGenerator::fromArray(array(
    				'shortDescription' => 'Sample generated class',
    				'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
    				'tags'             => array(
    						array(
    								'name'        => 'version',
    								'description' => '$Rev:$',
    						),
    						array(
    								'name'        => 'license',
    								'description' => 'New BSD',
    						),
    				),
    		));
    		$fooBase->setName($className);
    		
    		$index = new MethodGenerator();
    		$index->setName('indexAction');
    		
    		$create = new MethodGenerator();
    		$create->setName('createAction');
    		
    		$read = new MethodGenerator();
    		$read->setName('readAction');
    		
    		$update = new MethodGenerator();
    		$update->setName('updateAction');
    		
    		$delete = new MethodGenerator();
    		$delete->setName('deleteAction');
    		
    		
    		$fooBase->setExtendedClass('AbstractActionController');
    		//$fooBase->set
    		$fooBase->setDocblock($docblock);
    		
    		$fooBase->addMethodFromGenerator($index);
    		
    		$fooBase->addMethodFromGenerator($create);
    		$fooBase->addMethodFromGenerator($read);
    		$fooBase->addMethodFromGenerator($update);
    		$fooBase->addMethodFromGenerator($delete);
    		
    		$fileBase->setClass($fooBase);
    		
    		echo '<pre>';
    		echo htmlentities($fileBase->generate());
    		echo '</pre>';
    	
    	}
    	
    	
    	exit();
    	
    
    }
    
    
    
    
    
}
