<?php
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Generator\Params;
use VisioCrudModeler\Generator\Config\Config;
use VisioCrudModeler\Generator\Strategy\WebGenerator;

/**
 * Controller for handling web calls
 * 
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class WebController extends AbstractActionController
{

    /**
     * Ajax action for generating default structure
     */
    public function generateAction()
    {
        
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
            $json = $params['data'];
        }
        
        $postParams = json_decode($json, true);
        if(empty($postParams)){
            return $this->jsonResponse(array('success' => 0, 'message' => 'Json has not been properly decoded'));
        }
        
        try {
            $mergeDefaultParamsWithModeleted = array_merge($this->getServiceLocator()->get('config')['VisioCrudModeler']['params'] , $postParams['params']);
            $params = Params::factory($this->getRequest(), $mergeDefaultParamsWithModeleted);
            $params->setParam('config', new Config($this->getServiceLocator()->get('config')['VisioCrudModeler']));
            $params->setParam('modeler' , $postParams);

            $generatorStrategy = new WebGenerator($params);
            $generatorStrategy->setServiceLocator($this->getServiceLocator());
            $generatorStrategy->generate();
            
            return $this->jsonResponse(array('success' => 1, 'message' => 'Structure has been generated'));
            
        } catch (\Exception $e) {
            return $this->jsonResponse(array('success' => 0, 'message' => $e->getMessage()));
        }
    }
    
    /**
     * Set request as json response
     * 
     * @param array $data 
     * @return Response
     */
    protected function jsonResponse($data)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($data));
        return $response;
    }
    
    
}