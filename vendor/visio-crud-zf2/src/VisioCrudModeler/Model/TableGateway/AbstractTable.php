<?php
namespace VisioCrudModeler\Model\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Db\Sql\Where;

/**
 * Extended implementation of TableGateway pattern which allows you
 * to work with You entity model (/VisioCrudModeler/Model/TableGateway/Entity/AbstractEntity).
 * It allows to simple add, update and delete objects from database
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class AbstractTable extends AbstractTableGateway implements EventManagerAwareInterface
{

    /**
     * EventManagerInterface object (inject from MVC)
     *
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Type of model set as prototype (default ArrayObject)
     *
     * @var string
     */
    protected $arrayObjectPrototypeClass = 'ArrayObject';

    /**
     * For searching table by key.
     * Usually it's primary key of table.
     * Default id
     *
     * @var string
     */
    protected $keyName = 'id';

    /**
     * Definition of events action
     *
     * @var array
     */
    protected $eventActions = array(
        self::PRE_INSERT => 'preInsert',
        self::POST_INSERT => 'postInsert',
        self::PRE_UPDATE => 'preUpdate',
        self::POST_UPDATE => 'postUpdate',
        self::PRE_DELETE => 'preDelete',
        self::POST_DELETE => 'postDelete'
    );

    /**
     * Key of preInsert event action
     */
    CONST PRE_INSERT = 'preInsert';

    /**
     * Key of postInsert event action
     */
    CONST POST_INSERT = 'postInsert';

    /**
     * Key of preUpdate event action
     */
    CONST PRE_UPDATE = 'preUpdate';

    /**
     * Key of postUpdate event action
     */
    CONST POST_UPDATE = 'postUpdate';

    /**
     * Key of preDelete event action
     */
    CONST PRE_DELETE = 'preDelete';

    /**
     * Key of postDelete event action
     */
    CONST POST_DELETE = 'postDelete';

    /**
     * Construct
     *
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @param string $table
     */
    public function __construct(Adapter $adapter, $table = null)
    {
        if ($table) {
            $this->table = $table;
        }
        $this->adapter = $adapter;
        $this->initialize();
    }

    /**
     * Get sql string of $query
     *
     * @param Sql $query
     * @return string
     */
    protected function getSqlString($query)
    {
        return $query->getSqlString($this->adapter->getPlatform());
    }

    /**
     * Query Debug.
     * Echo string of query and die;
     *
     * @param type $query
     */
    protected function debug($query)
    {
        echo $query->getSqlString($this->adapter->getPlatform());
        die();
    }

    /**
     * Return new sql object
     *
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        return new Sql($this->getAdapter());
    }

    /**
     * Shortcut of begin transaction (from connection)
     */
    public function beginTransaction()
    {
        $this->getAdapter()
            ->getDriver()
            ->getConnection()
            ->beginTransaction();
    }

    /**
     * Shortcut of commit
     */
    public function commit()
    {
        $this->getAdapter()
            ->getDriver()
            ->getConnection()
            ->commit();
    }

    /**
     * shortcut of rollback
     */
    public function rollback()
    {
        $this->getAdapter()
            ->getDriver()
            ->getConnection()
            ->rollback();
    }

    /**
     * Get one row for given key
     *
     * @param
     *            mixed | string | int $id
     * @param
     *            null | string $protypeClass
     * @return mixed Return model object based on prototypeClass
     */
    public function findRow($id, $prototypeClass = null)
    {
        $id = (int) $id;

        $rowset = $this->select(array(
            $this->keyName => $id
        ));

        $arrayObjectPrototypeClass = ($prototypeClass) ? $prototypeClass : $this->arrayObjectPrototypeClass;
        $rowset->setArrayObjectPrototype(new $arrayObjectPrototypeClass());

        $row = $rowset->current();
        return $row;
    }

    /**
     * Get one row for given key
     *
     * @param
     *            mixed | string | int $id
     * @return mixed Return model object based on prototypeClass
     */
    public function getRow($id)
    {
        return $this->findRow($id);
    }

    /**
     * Get one row of table for given $query
     *
     * @param \Zend\Db\Sql\Select $query
     * @param
     *            string | $prototypeClass
     * @return mixed
     */
    public function fetchRow(\Zend\Db\Sql\Select $query = null, $prototypeClass = null)
    {
        if (! $query) {
            $query = $this->getBaseQuery()->limit(1);
        }

        $stmt = $this->getSql()->prepareStatementForSqlObject($query);
        $res = $stmt->execute();

        $resultSet = new ResultSet();
        $arrayObjectPrototypeClass = ($prototypeClass) ? $prototypeClass : $this->arrayObjectPrototypeClass;
        $resultSet->setArrayObjectPrototype(new $arrayObjectPrototypeClass());

        $resultSet->initialize($res);

        return $resultSet->current();
    }

    /**
     * fetches first row from this table that meet where statement
     *
     * @param Where $where
     * @param mixed $order
     * @return mixed
     */
    public function fetchRowWhere(Where $where, $order = null)
    {
        $query = $this->getBaseQuery();
        $query->where($where);
        if (! is_null($order)) {
            $query->order($order);
        }
        return $this->fetchRow($query);
    }

    /**
     * Get records for given query
     *
     * @param \Zend\Db\Sql\Select $query
     * @param
     *            string | null $prototypeClass
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(\Zend\Db\Sql\Select $query = null, $prototypeClass = null)
    {
        if (! $query) {
            $query = $this->getBaseQuery();
        }

        $stmt = $this->getSql()->prepareStatementForSqlObject($query);
        $res = $stmt->execute();

        $resultSet = new ResultSet();
        $arrayObjectPrototypeClass = (strlen($prototypeClass) > 0) ? $prototypeClass : $this->arrayObjectPrototypeClass;
        $resultSet->setArrayObjectPrototype(new $arrayObjectPrototypeClass());

        $resultSet->initialize($res);

        return $resultSet;
    }

    /**
     * Get records from this table mathcing where conditions
     *
     * @param Where $where
     * @param mixed $order
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllWhere(Where $where, $order = null)
    {
        $query = $this->getBaseQuery();
        $query->where($where);
        if (! is_null($order)) {
            $query->order($order);
        }
        return $this->fetchAll($query);
    }

    /**
     * get sql select query already setup with this instance adapter and table name
     *
     * @return Zend\Db\Sql\Select
     */
    public function getBaseQuery()
    {
        $sql = new Sql($this->getAdapter());
        $query = $sql->select();
        $query->from($this->table)->columns(array(
            '*'
        ));
        return $query;
    }

    /**
     * Array object protype class
     *
     * @return string
     */
    public function getArrayObjectPrototypeClass()
    {
        return $this->arrayObjectPrototypeClass;
    }

    /**
     * set arrayobject prototype
     *
     * @param string $arrayObjectPrototypeClass
     */
    public function setArrayObjectPrototypeClass($arrayObjectPrototypeClass)
    {
        $this->arrayObjectPrototypeClass = $arrayObjectPrototypeClass;
    }

    /**
     * For searching table by key.
     * Usually it's primary key of table.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * Set KeyName.
     * For searching table by key. Usually it's primary key of table.
     *
     * @return string
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * set eventmanager instance
     *
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return type
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class()
        ));
        $this->events = $events;
        return $this;
    }

    /**
     * returns eventmanager
     *
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    /**
     * Insert new row to database
     *
     * @param mixed $object
     * @return int
     * @throws \Exception\BadMethodCallException
     */
    public function insert($object)
    {
        if (is_array($object)) {
            return parent::insert($object);
        }

        if (! is_callable(array(
            $object,
            'getArrayCopy'
        ))) {
            throw new \BadMethodCallException(sprintf('%s expects the provided object to implement getArrayCopy()', __METHOD__));
        }

        $preInsertMethodName = $this->eventActions[self::PRE_INSERT];
        $postInsertMethodName = $this->eventActions[self::POST_INSERT];

        if (method_exists($object, $preInsertMethodName)) {
            $object->$preInsertMethodName($this->getEventManager());
        }

        $set = $object->getArrayCopy();

        if (isset($set[$this->keyName])) {
            unset($set[$this->keyName]);
        }

        $res = parent::insert($set);

        if (method_exists($object, $postInsertMethodName)) {
            $object->$postInsertMethodName($this->getEventManager());
        }

        return $res;
    }

    /**
     * Update exists row
     *
     * @param mixed $object
     * @return int
     * @throws \Exception\BadMethodCallException
     */
    public function update($object, $where = null)
    {
        $issetKey = (is_array($object)) ? isset($object[$this->keyName]) : isset($object->{$this->keyName});

        if (! $where && ! $issetKey) {
            throw new \Exception(sprintf('%s expects the provided object has key defined', __METHOD__));
        }
        if (is_array($object)) {
            $id = $object[$this->keyName];
            unset($object[$this->keyName]);
            $where = ($where) ? $where : array(
                $this->keyName => $id
            );

            return parent::update($object, $where);
        }

        if (! is_callable(array(
            $object,
            'getArrayCopy'
        ))) {
            throw new \BadMethodCallException(sprintf('%s expects the provided object to implement getArrayCopy()', __METHOD__));
        }

        $preUpdateMethodName = $this->eventActions[self::PRE_UPDATE];
        $postUpdateMethodName = $this->eventActions[self::POST_UPDATE];

        if (method_exists($object, $preUpdateMethodName)) {
            $object->$preUpdateMethodName($this->getEventManager());
        }
        $set = $object->getArrayCopy();
        $id = $set[$this->keyName];
        unset($set[$this->keyName]);
        $where = ($where) ? $where : array(
            $this->keyName => $id
        );

        $res = parent::update($set, $where);

        if (method_exists($object, $postUpdateMethodName)) {
            $object->$postUpdateMethodName($this->getEventManager());
        }

        return $res;
    }

    /**
     * Delete object from database
     *
     * @param mixed $objectOrWhere
     */
    public function delete($objectOrWhere)
    {
        $isEntityObjectDelete = $objectOrWhere instanceof \VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity;

        if ($isEntityObjectDelete) {
            $object = $objectOrWhere;
            $id = (is_array($object)) ? $object[$this->keyName] : $object->{$this->keyName};
            $where = array(
                $this->keyName => $id
            );
        } else {
            $where = $objectOrWhere;
        }

        $preDeleteMethodName = $this->eventActions[self::PRE_DELETE];
        $postDeleteMethodName = $this->eventActions[self::POST_DELETE];

        if ($isEntityObjectDelete && method_exists($object, $preDeleteMethodName)) {
            $object->$preDeleteMethodName($this->getEventManager());
        }

        parent::delete($where);

        if ($isEntityObjectDelete && method_exists($object, $postDeleteMethodName)) {
            $object->$postDeleteMethodName($this->getEventManager());
        }
    }
}