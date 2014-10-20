<?php
return array(
    'module.generatedConfigDescription'=>'This file is generated automatically for module "%s". Do not change its contents as it will be overwritten in next pass of generator run. instead use standard module config with array_merge_recursive to overwrite any generated values.',
    'module.standardConfigDescription'=>'This file is generated automatically for module "%s". If you want to overwrite any generated configuration for this module, this file is the place to do it.',
    'module.standardConfigBody'=>"return array_merge_recursive(require __DIR__ . 'config.generated.php',array(\n    // place your override here\n));",
    'module.moduleClassDescription'=>"Standard Module class",
    'module.onBootstrap.body'=>'$eventManager = $e->getApplication()->getEventManager();'."\n".'$moduleRouteListener = new \Zend\Mvc\ModuleRouteListener();'."\n".'$moduleRouteListener->attach($eventManager);',
    'module.onBootstrap.shortdescription'=>'standard bootstrap method',
    'module.onBootstrap.longdescription'=>'used to configure additional features, not available through module.config.php',
    'module.config.body'=>'$config = require __DIR__ . \'/config/module.config.php\';'."\n".'return $config;',
    'module.config.shortdescription'=>'loads module config',
    'module.config.longdescription'=>'standard method for loading module related configuration',
    'module.getAutoloaderConfig.body' =>'return array('."\n".
                                       '     \'Zend\Loader\StandardAutoloader\' => array('."\n".
                                       '         \'namespaces\' => array('."\n".
                                       '             __NAMESPACE__ => __DIR__ . \'/src/\' . __NAMESPACE__'."\n".
                                       '         )'."\n".
                                       '     )'."\n".
                                        ');',
    'module.getAutoloaderConfig.shortdescription'=>'autoloader configuration',
    'module.getAutoloaderConfig.longdescription'=>'used to load module related class',
    'standard.returnArray'=>'return array();',

    'model.getMethod.description'=>'Gets value for %s',
    'model.getMethodInt.body'=>'return intval($this->%s);',
    'model.getMethodFloat.body'=>'return floatval($this->%s);',
    'model.getMethodString.body'=>'return strval($this->%s);',
    'model.setMethod.description'=>'Sets value for %s',
    'model.setMethodInt.body'=>'$this->%s = $value;',
    'model.setMethodFloat.body'=>'$this->%s = $value;',
    'model.setMethodString.body'=>'$this->%s = $value;',
    'model.preInsert.description'=>'Override this method in child class if You need pre insert events',
    'model.postInsert.description'=>'Override this method in child class if You need post insert events',
    'model.preUpdate.description'=>'Override this method in child class if You need pre update events',
    'model.postUpdate.description'=>'Override this method in child class if You need post update events',
    'model.preDelete.description'=>'Override this method in child class if You need pre delete events',
    'model.postDelete.description'=>'Override this method in child class if You need post delete events',
    'model.generatedConfigDescription'=>'This file is generated automatically for model "%s". Do not change its contents as it will be overwritten in next pass of generator run. instead use standard model config with array_merge_recursive to overwrite any generated values.',
    'model.standardConfigDescription'=>'This file is generated automatically for model "%s". If you want to overwrite any generated configuration for this model, this file is the place to do it.',
    'table.generatedConfigDescription'=>'This file is generated automatically for table "%s". Do not change its contents as it will be overwritten in next pass of generator run.',
    'table.standardConfigDescription'=>'This file is generated automatically for table "%s". If you want to overwrite any generated configuration for this table, this file is the place to do it.',

    'filter.generatedConfigDescription'=>'This file is generated automatically for table "%s". Do not change its contents as it will be overwritten in next pass of generator run.',
    'filter.standardConfigDescription'=>'This file is generated automatically for table "%s". If you want to overwrite any generated configuration for this filters and validators, this file is the place to do it.',
    'filter.constructor.body.begin'=>"\$inputFilter = \$this->getInputFilter();\n".
                                    "\$factory = \$this->getInputFactory();\n\n",
    'filter.constructor.body.input'=>"\$inputFilter->add(\$factory->createInput(array(\n".
                                    "       'name' => '%s',\n".
                                    "       'required' => %s,\n".
                                    "       'filters' => array(%s),\n".
                                    "       %s\n".
                                    ")));\n\n",
    'filter.constructor.fieldFilterInt'=>"\n".
                                    "           array('name'=>'Int')\n       ",
    'filter.constructor.fieldFilterString'=>"\n".
                                    "           array('name' => 'StripTags'),\n".
                                    "           array('name' => 'StringTrim')\n       ",
    'filter.constructor.fieldFilterFloat'=>"\n".
                                    "           array('name' => 'Float')\n       ",
    'filter.constructor.validators'=>"'validators' => array(%s\n       )",
    'filter.constructor.validators.stringLenght'=>"\n".
                                    "           array(\n".
                                    "               'name' => 'StringLength',\n".
                                    "               'options' => array(\n".
                                    "                   'encoding' => 'UTF-8',\n".
                                    "                   'min' => '%s',\n".
                                    "                   'max' => '%s'\n".
                                    "               )\n".
                                    "           ),",
    'filter.constructor.validators.between'=>"\n".
                                    "           array(\n".
                                    "               'name' => 'Between',\n".
                                    "               'options' => array(\n".
                                    "                   'min' => '%s',\n".
                                    "                   'max' => '%s'\n".
                                    "               )\n".
                                    "           ),",
    'filter.constructor.validators.digits'=>"\n".
                                    "           array(\n".
                                    "               'name' => 'Digits'\n".
                                    "           ),",

    'grid.generatedConfigDescription'=>'This file is generated automatically for table "%s". Do not change its contents as it will be overwritten in next pass of generator run.',
    'grid.standardConfigDescription'=>'This file is generated automatically for table "%s". If you want to overwrite any generated configuration for this grid, this file is the place to do it.',
    'grid.init.body'=>'$this->getHeader("edit")->getCell()->addDecorator("callable", array('."\n".
                                    '    "callable" => function($context, $record){'."\n".
                                    '        return sprintf("<a href=\"%s\">Edit</a>", $record["%s"]);'."\n".
                                    '    }'."\n".
                                    '));'."\n\n".

                                    '$this->getHeader("delete")->getCell()->addDecorator("callable", array('."\n".
                                    '    "callable" => function($context, $record){'."\n".
                                    '        return sprintf("<a href=\"%s\">Delete</a>", $record["%s"]);'."\n".
                                    '    }'."\n".
                                    '));',
    'grid.initFilters.string'=>'$value = $this->getParamAdapter()->getValueOfFilter(\'%s\');'."\n".
                                    'if ($value != null) {'."\n".
                                    '    $query->where("%s like \'%%".$value."%%\' ");'."\n".
                                    '}'."\n\n",
    'grid.initFilters.int'=>'$value = $this->getParamAdapter()->getValueOfFilter(\'%s\');'."\n".
                                    'if ($value != null) {'."\n".
                                    '    $query->where("%s = \'".$value."\' ");'."\n".
                                    '}'."\n\n",
    'grid.initFilters.float'=>'$value = $this->getParamAdapter()->getValueOfFilter(\'%s\');'."\n".
                                    'if ($value != null) {'."\n".
                                    '    $query->where("%s = \'".$value."\' ");'."\n".
                                    '}'."\n\n",
    'grid.initFilters.bool'=>'$value = $this->getParamAdapter()->getValueOfFilter(\'%s\');'."\n".
                                    'if ($value != null) {'."\n".
                                    '    $query->where("%s = ".$value);'."\n".
                                    '}'."\n\n",
    'form.generatedConfigDescription'=>'This file is generated automatically for table "%s". Do not change its contents as it will be overwritten in next pass of generator run.',
    'form.standardConfigDescription'=>'This file is generated automatically for table "%s". If you want to overwrite any generated configuration for this form, this file is the place to do it.',
    'form.constructor.parentCall'=>"parent::__construct('%s');\n".
                                    '$this->setAttribute(\'method\', \'post\');'."\n\n",
    'form.constructor.field.primary'=>"\$this->add(array(\n".
                                    "   'name' => '%s',\n".
                                    "   'attributes' => array(\n".
                                    "       'type'  => 'hidden',\n".
                                    "   ),\n".
                                    "));\n\n",
    'form.constructor.field.int'=>"\$this->add(array(\n".
                                    "    'name' => '%s',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'text',\n".
                                    "        'class' => 'form-control'\n".
                                    "    ),\n".
                                    "    'options' => array(\n".
                                    "        'label' => '%s',\n".
                                    "    ),\n".
                                    "));\n\n",
    'form.constructor.field.float'=>"\$this->add(array(\n".
                                    "    'name' => '%s',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'text',\n".
                                    "        'class' => 'form-control'\n".
                                    "    ),\n".
                                    "    'options' => array(\n".
                                    "        'label' => '%s',\n".
                                    "    ),\n".
                                    "));\n\n",
    'form.constructor.field.string'=>"\$this->add(array(\n".
                                    "    'name' => '%s',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'text',\n".
                                    "        'class' => 'form-control'\n".
                                    "    ),\n".
                                    "    'options' => array(\n".
                                    "        'label' => '%s',\n".
                                    "    ),\n".
                                    "));\n\n",
    'form.constructor.field.bool'=>"\$this->add(array(\n".
                                    "    'name' => '%s',\n".
                                    "    'type' => 'Zend\Form\Element\Checkbox',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'checkbox',\n".
                                    "        'class' => ''\n".
                                    "    ),\n".
                                    "    'options' => array(\n".
                                    "        'label' => '%s',\n".
                                    "    ),\n".
                                    "));\n\n",
    'form.constructor.field.submit'=>"\$this->add(array(\n".
                                    "    'name' => 'submit',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'submit',\n".
                                    "        'value' => 'Go',\n".
                                    "        'id' => 'submitbutton',\n".
                                    "        'class' => 'form-control btn-success',\n".
                                    "        'style' => 'width: 50%'\n".
                                    "    ),\n".
                                    "));\n",
    'form.constructor.field.fromWeb'=>"\$this->add(array(\n".
                                    "    'name' => '%s',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => '%s',\n".
                                    "        'class' => 'form-control'\n".
                                    "    ),\n".
                                    "    'options' => array(\n".
                                    "        'label' => '%s',\n".
                                    "    ),\n".
                                    "));\n\n",
    'controller.standardBaseControllerDescription'=>'automatically generated controller for handling CRUD operation on DataSet "%s". Do not edit this file as it will be overwriten in next pass of controller generator.',
    'controller.standardControllerDescription'=>'automatically generated controller for adding custom actions, beside base handling CRUD operation on DataSet "%s".',
    'controller.getAdapter.description' => 'returns adapter associated with model',
    'controller.getAdapter.body' => 'return $this->getServiceLocator()->get(\'%adapterKey%\');',
    
    'controller.htmlResponse.description' => 'Return response as html',
    'controller.htmlResponse.body' => '$response = $this->getResponse()
->setStatusCode(200)
->setContent($html);
return $response;',
    
    'controller.readAction.description' => 'handles read operation for single entity',
    'controller.readAction.body' => '$id = (int) $this->params()->fromRoute(\'id\', 0);
  
    


if (!$id) {
    return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');

}
$table = $this->getTable();
$row = $table->findRow($id);

$form = new %form%();
$form->bind($row);

$request = $this->getRequest();
if ($request->isPost()) {
    $filter = new %filter%();
    $row->setInputFilter($filter->getInputFilter());
    $form->setInputFilter($filter->getInputFilter());
    $form->setData($request->getPost());

    if ($form->isValid()) {
        $row->exchangeArray($form->getData());
        $table->update($row);
        return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
    }
}

return array(
    \'id\' => $id,
    \'form\' => $form,
);',
    'controller.updateAction.description' => 'handles updating entity data',
    'controller.updateAction.body' => '$id = (int) $this->params()->fromRoute(\'id\', 0);
if (!$id) {
    return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');

}
$row = $this->getTable()->findRow($id);

$form = new %form%();
$form->bind($row);

$request = $this->getRequest();
if ($request->isPost()) {
    $filter = new %filter%();
    $row->setInputFilter($filter->getInputFilter());
    $form->setInputFilter($filter->getInputFilter());
    $form->setData($request->getPost());

    if ($form->isValid()) {
        $this->getTable()->update($row);
        return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
    }
}

return array(
    \'id\' => $id,
    \'form\' => $form,
);',
    'controller.deleteAction.description' => 'handles deleting single entity',
    'controller.deleteAction.body' => '$id = (int) $this->params()->fromRoute(\'id\', 0);
if (!$id) {
    return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
}
$table = $this->getTable();
$row = $table->findRow($id);
$table->delete($row);

return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');',
    'controller.getTable.description' => 'returns table instance',
    'controller.getTable.body' => '$table = new %table%($this->getAdapter());
$table->setEventManager($this->getEventManager());
return $table;',
    'controller.createAction.description' => 'handles adding new entry',
    'controller.createAction.body' => '$form = new %form%();
$request = $this->getRequest();

$filter = new %filter%();
$form->setInputFilter($filter->getInputFilter());

if ($request->isPost()) {
    $row = new %model%();
    
    $form->setData($request->getPost());

    if ($form->isValid()) {
        $row->exchangeArray($form->getData());
        $this->getTable()->insert($row);

        // Redirect to list of albums
        return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
    }
}
return array(\'form\' => $form);',
    'controller.ajaxListAction.description'=>'handles sending results to ajax table',
    'controller.ajaxListAction.body'=>'$table = new %grid%();
$table->setAdapter($this->getAdapter())
        ->setSource($this->getTable()->getBaseQuery())
        ->setParamAdapter($this->getRequest()->getPost())
;
return $this->htmlResponse($table->render());',
    'controller.listAction.description'=>'handles sending results to ajax table'
);
