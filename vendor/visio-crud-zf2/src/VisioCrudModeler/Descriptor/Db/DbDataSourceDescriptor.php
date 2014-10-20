<?php
namespace VisioCrudModeler\Descriptor\Db;

use VisioCrudModeler\Descriptor\AbstractDataSourceDescriptor;
use Zend\Db\Adapter\Adapter;
use VisioCrudModeler\Exception\DataSetNotFound;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;

/**
 * descriptor for database sources
 *
 * allows resolving structure for any database implementing information_schema (for example MySQL). 
 * Also support automatic recognition of current database for MySQL database.
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 */
class DbDataSourceDescriptor extends AbstractDataSourceDescriptor implements ListGeneratorInterface
{

    protected $tableTypes = array(
        'BASE TABLE' => 'table',
        'VIEW' => 'view'
    );

    protected $tablesDescriptionStatement = 'SELECT * FROM information_schema.TABLES it WHERE it.TABLE_SCHEMA = :database';

    protected $viewsDescriptionStatement = 'SELECT * FROM information_schema.VIEWS iv WHERE iv.TABLE_SCHEMA = :database';

    protected $columnsDescriptionStatement = 'SELECT * FROM information_schema.COLUMNS ic WHERE ic.TABLE_SCHEMA = :database';

    protected $referenceDescriptionStatement = 'SELECT * FROM information_schema.KEY_COLUMN_USAGE kcu WHERE kcu.TABLE_SCHEMA = :database and kcu.REFERENCED_COLUMN_NAME IS NOT NULL';

    /**
     * holds databse adapter
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter = null;

    /**
     * holds dataset descriptors object instances
     *
     * @var \ArrayObject
     */
    protected $dataSetDescriptors = null;

    /**
     * constructor accepting DbDataSource instance
     *
     * @param DbDataSource $dataSource            
     */
    public function __construct(Adapter $adapter, $name = null)
    {
        $this->adapter = $adapter;
        if (! is_null($name)) {
            $this->setName($name);
        }
        $this->dataSetDescriptors = new \ArrayObject(array());
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor::describe()
     */
    protected function describe()
    {
        if (! $this->definitionResolved) {
            if (empty($this->getName())) {
                throw new \RuntimeException('Missing database name');
            }
            $this->describeTables();
            $this->describeViews();
            $this->describeColumns();
            $this->describeReferences();
            $this->definitionResolved = true;
        }
        return $this;
    }

    /**
     * describes tables in database
     */
    protected function describeTables()
    {
        $result = $this->getAdapter()
            ->createStatement($this->tablesDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $tableDefinition = $this->createTableDefinition($row);
                $this->definition[$tableDefinition['name']] = $tableDefinition;
            }
        }
    }

    /**
     * creates tables definition
     *
     * @param array $informationSchemaRow            
     * @return array
     */
    protected function createTableDefinition(array $informationSchemaRow)
    {
        return array(
            'type' => $this->tableTypes[$informationSchemaRow['TABLE_TYPE']],
            'name' => $informationSchemaRow['TABLE_NAME'],
            'updateable' => true,
            'fields' => array()
        );
    }

    /**
     * describes views in database
     */
    protected function describeViews()
    {
        $result = $this->getAdapter()
            ->createStatement($this->viewsDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                if ($row['IS_UPDATABLE'] == 'NO') {
                    $this->definition[$row['TABLE_NAME']]['updateable'] = false;
                }
            }
        }
    }

    /**
     * describes columns in database
     */
    protected function describeColumns()
    {
        $result = $this->getAdapter()
            ->createStatement($this->columnsDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $fieldDescription = array(
                    'name' => $row['COLUMN_NAME'],
                    'type' => $row['DATA_TYPE'],
                    'character_maximum_length' => $row['CHARACTER_MAXIMUM_LENGTH'],
                    'numeric_precision' => $row['NUMERIC_PRECISION'],
                    'numeric_scale' => $row['NUMERIC_SCALE'],
                    'null' => ($row['IS_NULLABLE'] == 'YES') ? true : false,
                    'default' => $row['COLUMN_DEFAULT'],
                    'key' => $row['COLUMN_KEY'],
                    'reference' => false
                );
                if($row['COLUMN_KEY'] == 'PRI'){
                    $this->definition[$row['TABLE_NAME']]['primaryKey'] = $row['COLUMN_NAME'];
                }
                $this->definition[$row['TABLE_NAME']]['fields'][$row['COLUMN_NAME']] = $fieldDescription;
            }
            
        }
    }

    /**
     * describes columns in database
     */
    protected function describeReferences()
    {
        $result = $this->getAdapter()
            ->createStatement($this->referenceDescriptionStatement)
            ->execute(array(
            'database' => $this->getName()
        ));
        if ($result->isQueryResult()) {
            foreach ($result as $row) {
                $referenceDescription = array(
                    'dataset' => $row['REFERENCED_TABLE_NAME'],
                    'field' => $row['REFERENCED_COLUMN_NAME']
                );
                $this->definition[$row['TABLE_NAME']]['fields'][$row['COLUMN_NAME']]['reference'] = $referenceDescription;
            }
        }
    }

    /**
     * returns DataSet descriptor
     *
     * @throws \VisioCrudModeler\Exception\DataSetNotFound
     * @return \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor
     */
    public function getDataSetDescriptor($dataSetName)
    {
        $this->describe();
        if (! array_key_exists($dataSetName, $this->definition)) {
            throw new DataSetNotFound("dataSet '" . $dataSetName . "' don't exists in '" . $this->name . "'");
        }
        if (! $this->dataSetDescriptors->offsetExists($dataSetName)) {
            $this->dataSetDescriptors->offsetSet($dataSetName, new DbDataSetDescriptor($this, $this->definition[$dataSetName]));
        }
        return $this->dataSetDescriptors->offsetGet($dataSetName);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSourceDescriptor::getName()
     */
    public function getName()
    {
        if (is_null($this->name)) {
            if ($this->adapter->getPlatform() instanceof \Zend\Db\Adapter\Platform\Mysql) {
                $this->name = $this->currentDatabaseMysql();
            } else {
                throw new \RuntimeException('Automatic database name resolving is not supported for platform: ' . get_class($this->adapter->getPlatform()));
            }
        }
        return $this->name;
    }

    /**
     * returns currenttly selected mysql database name
     *
     * @return string
     */
    protected function currentDatabaseMysql()
    {
        $result = $this->getAdapter()
            ->createStatement('SELECT DATABASE()')
            ->execute();
        if ($result->isQueryResult()) {
            $current = $result->current();
            return array_shift($current);
        }
    }
}
