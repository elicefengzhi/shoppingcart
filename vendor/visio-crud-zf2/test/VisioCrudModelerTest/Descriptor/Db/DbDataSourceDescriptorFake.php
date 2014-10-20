<?php
namespace VisioCrudModelerTest\Descriptor\Db;

use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;

class DbDataSourceDescriptorFake extends DbDataSourceDescriptor
{

    /**
     * holds tables definition
     *
     * @var array
     */
    protected $definition = array(
        'actor' => array(
            'type' => 'table',
            'name' => 'actor',
            'updateable' => true,
            'fields' => array(
                'actor_id' => array(
                    'name' => 'actor_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'first_name' => array(
                    'name' => 'first_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'actor_info' => array(
            'type' => 'view',
            'name' => 'actor_info',
            'updateable' => false,
            'fields' => array(
                'actor_id' => array(
                    'name' => 'actor_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '0',
                    'key' => '',
                    'reference' => false
                ),
                'first_name' => array(
                    'name' => 'first_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'film_info' => array(
                    'name' => 'film_info',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'address' => array(
            'type' => 'table',
            'name' => 'address',
            'updateable' => true,
            'fields' => array(
                'address_id' => array(
                    'name' => 'address_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'address' => array(
                    'name' => 'address',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'address2' => array(
                    'name' => 'address2',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'district' => array(
                    'name' => 'district',
                    'type' => 'varchar',
                    'character_maximum_length' => '20',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'city_id' => array(
                    'name' => 'city_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'city',
                        'field' => 'city_id'
                    )
                ),
                'postal_code' => array(
                    'name' => 'postal_code',
                    'type' => 'varchar',
                    'character_maximum_length' => '10',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'phone' => array(
                    'name' => 'phone',
                    'type' => 'varchar',
                    'character_maximum_length' => '20',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'category' => array(
            'type' => 'table',
            'name' => 'category',
            'updateable' => true,
            'fields' => array(
                'category_id' => array(
                    'name' => 'category_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'name' => array(
                    'name' => 'name',
                    'type' => 'varchar',
                    'character_maximum_length' => '25',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'city' => array(
            'type' => 'table',
            'name' => 'city',
            'updateable' => true,
            'fields' => array(
                'city_id' => array(
                    'name' => 'city_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'city' => array(
                    'name' => 'city',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'country_id' => array(
                    'name' => 'country_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'country',
                        'field' => 'country_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'country' => array(
            'type' => 'table',
            'name' => 'country',
            'updateable' => true,
            'fields' => array(
                'country_id' => array(
                    'name' => 'country_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'country' => array(
                    'name' => 'country',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'customer' => array(
            'type' => 'table',
            'name' => 'customer',
            'updateable' => true,
            'fields' => array(
                'customer_id' => array(
                    'name' => 'customer_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'store_id' => array(
                    'name' => 'store_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'store',
                        'field' => 'store_id'
                    )
                ),
                'first_name' => array(
                    'name' => 'first_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => false
                ),
                'email' => array(
                    'name' => 'email',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'address_id' => array(
                    'name' => 'address_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'address',
                        'field' => 'address_id'
                    )
                ),
                'active' => array(
                    'name' => 'active',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '1',
                    'key' => '',
                    'reference' => false
                ),
                'create_date' => array(
                    'name' => 'create_date',
                    'type' => 'datetime',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'customer_list' => array(
            'type' => 'view',
            'name' => 'customer_list',
            'updateable' => true,
            'fields' => array(
                'ID' => array(
                    'name' => 'ID',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '0',
                    'key' => '',
                    'reference' => false
                ),
                'name' => array(
                    'name' => 'name',
                    'type' => 'varchar',
                    'character_maximum_length' => '91',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'address' => array(
                    'name' => 'address',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'zip code' => array(
                    'name' => 'zip code',
                    'type' => 'varchar',
                    'character_maximum_length' => '10',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'phone' => array(
                    'name' => 'phone',
                    'type' => 'varchar',
                    'character_maximum_length' => '20',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'city' => array(
                    'name' => 'city',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'country' => array(
                    'name' => 'country',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'notes' => array(
                    'name' => 'notes',
                    'type' => 'varchar',
                    'character_maximum_length' => '6',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => '',
                    'key' => '',
                    'reference' => false
                ),
                'SID' => array(
                    'name' => 'SID',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'film' => array(
            'type' => 'table',
            'name' => 'film',
            'updateable' => true,
            'fields' => array(
                'film_id' => array(
                    'name' => 'film_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'character_maximum_length' => '255',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => false
                ),
                'description' => array(
                    'name' => 'description',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'release_year' => array(
                    'name' => 'release_year',
                    'type' => 'year',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'language_id' => array(
                    'name' => 'language_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'language',
                        'field' => 'language_id'
                    )
                ),
                'original_language_id' => array(
                    'name' => 'original_language_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'language',
                        'field' => 'language_id'
                    )
                ),
                'rental_duration' => array(
                    'name' => 'rental_duration',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '3',
                    'key' => '',
                    'reference' => false
                ),
                'rental_rate' => array(
                    'name' => 'rental_rate',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '4',
                    'numeric_scale' => '2',
                    'null' => false,
                    'default' => '4.99',
                    'key' => '',
                    'reference' => false
                ),
                'length' => array(
                    'name' => 'length',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'replacement_cost' => array(
                    'name' => 'replacement_cost',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '2',
                    'null' => false,
                    'default' => '19.99',
                    'key' => '',
                    'reference' => false
                ),
                'rating' => array(
                    'name' => 'rating',
                    'type' => 'enum',
                    'character_maximum_length' => '5',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => 'G',
                    'key' => '',
                    'reference' => false
                ),
                'special_features' => array(
                    'name' => 'special_features',
                    'type' => 'set',
                    'character_maximum_length' => '54',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'film_actor' => array(
            'type' => 'table',
            'name' => 'film_actor',
            'updateable' => true,
            'fields' => array(
                'actor_id' => array(
                    'name' => 'actor_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => array(
                        'dataset' => 'actor',
                        'field' => 'actor_id'
                    )
                ),
                'film_id' => array(
                    'name' => 'film_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => array(
                        'dataset' => 'film',
                        'field' => 'film_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'film_category' => array(
            'type' => 'table',
            'name' => 'film_category',
            'updateable' => true,
            'fields' => array(
                'film_id' => array(
                    'name' => 'film_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => array(
                        'dataset' => 'film',
                        'field' => 'film_id'
                    )
                ),
                'category_id' => array(
                    'name' => 'category_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => array(
                        'dataset' => 'category',
                        'field' => 'category_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'film_list' => array(
            'type' => 'view',
            'name' => 'film_list',
            'updateable' => false,
            'fields' => array(
                'FID' => array(
                    'name' => 'FID',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => '0',
                    'key' => '',
                    'reference' => false
                ),
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'character_maximum_length' => '255',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'description' => array(
                    'name' => 'description',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'category' => array(
                    'name' => 'category',
                    'type' => 'varchar',
                    'character_maximum_length' => '25',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'price' => array(
                    'name' => 'price',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '4',
                    'numeric_scale' => '2',
                    'null' => true,
                    'default' => '4.99',
                    'key' => '',
                    'reference' => false
                ),
                'length' => array(
                    'name' => 'length',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'rating' => array(
                    'name' => 'rating',
                    'type' => 'enum',
                    'character_maximum_length' => '5',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => 'G',
                    'key' => '',
                    'reference' => false
                ),
                'actors' => array(
                    'name' => 'actors',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'film_text' => array(
            'type' => 'table',
            'name' => 'film_text',
            'updateable' => true,
            'fields' => array(
                'film_id' => array(
                    'name' => 'film_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'character_maximum_length' => '255',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => false
                ),
                'description' => array(
                    'name' => 'description',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'inventory' => array(
            'type' => 'table',
            'name' => 'inventory',
            'updateable' => true,
            'fields' => array(
                'inventory_id' => array(
                    'name' => 'inventory_id',
                    'type' => 'mediumint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '7',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'film_id' => array(
                    'name' => 'film_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'film',
                        'field' => 'film_id'
                    )
                ),
                'store_id' => array(
                    'name' => 'store_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'store',
                        'field' => 'store_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'language' => array(
            'type' => 'table',
            'name' => 'language',
            'updateable' => true,
            'fields' => array(
                'language_id' => array(
                    'name' => 'language_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'name' => array(
                    'name' => 'name',
                    'type' => 'char',
                    'character_maximum_length' => '20',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'nicer_but_slower_film_list' => array(
            'type' => 'view',
            'name' => 'nicer_but_slower_film_list',
            'updateable' => false,
            'fields' => array(
                'FID' => array(
                    'name' => 'FID',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => '0',
                    'key' => '',
                    'reference' => false
                ),
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'character_maximum_length' => '255',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'description' => array(
                    'name' => 'description',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'category' => array(
                    'name' => 'category',
                    'type' => 'varchar',
                    'character_maximum_length' => '25',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'price' => array(
                    'name' => 'price',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '4',
                    'numeric_scale' => '2',
                    'null' => true,
                    'default' => '4.99',
                    'key' => '',
                    'reference' => false
                ),
                'length' => array(
                    'name' => 'length',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'rating' => array(
                    'name' => 'rating',
                    'type' => 'enum',
                    'character_maximum_length' => '5',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => 'G',
                    'key' => '',
                    'reference' => false
                ),
                'actors' => array(
                    'name' => 'actors',
                    'type' => 'text',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'payment' => array(
            'type' => 'table',
            'name' => 'payment',
            'updateable' => true,
            'fields' => array(
                'payment_id' => array(
                    'name' => 'payment_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'customer_id' => array(
                    'name' => 'customer_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'customer',
                        'field' => 'customer_id'
                    )
                ),
                'staff_id' => array(
                    'name' => 'staff_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'staff',
                        'field' => 'staff_id'
                    )
                ),
                'rental_id' => array(
                    'name' => 'rental_id',
                    'type' => 'int',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '10',
                    'numeric_scale' => '0',
                    'null' => true,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'rental',
                        'field' => 'rental_id'
                    )
                ),
                'amount' => array(
                    'name' => 'amount',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '2',
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'payment_date' => array(
                    'name' => 'payment_date',
                    'type' => 'datetime',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'rental' => array(
            'type' => 'table',
            'name' => 'rental',
            'updateable' => true,
            'fields' => array(
                'rental_id' => array(
                    'name' => 'rental_id',
                    'type' => 'int',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '10',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'rental_date' => array(
                    'name' => 'rental_date',
                    'type' => 'datetime',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => false
                ),
                'inventory_id' => array(
                    'name' => 'inventory_id',
                    'type' => 'mediumint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '7',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'inventory',
                        'field' => 'inventory_id'
                    )
                ),
                'customer_id' => array(
                    'name' => 'customer_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'customer',
                        'field' => 'customer_id'
                    )
                ),
                'return_date' => array(
                    'name' => 'return_date',
                    'type' => 'datetime',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'staff_id' => array(
                    'name' => 'staff_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'staff',
                        'field' => 'staff_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'sales_by_film_category' => array(
            'type' => 'view',
            'name' => 'sales_by_film_category',
            'updateable' => false,
            'fields' => array(
                'category' => array(
                    'name' => 'category',
                    'type' => 'varchar',
                    'character_maximum_length' => '25',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'total_sales' => array(
                    'name' => 'total_sales',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '27',
                    'numeric_scale' => '2',
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'sales_by_store' => array(
            'type' => 'view',
            'name' => 'sales_by_store',
            'updateable' => false,
            'fields' => array(
                'store' => array(
                    'name' => 'store',
                    'type' => 'varchar',
                    'character_maximum_length' => '101',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'manager' => array(
                    'name' => 'manager',
                    'type' => 'varchar',
                    'character_maximum_length' => '91',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'total_sales' => array(
                    'name' => 'total_sales',
                    'type' => 'decimal',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '27',
                    'numeric_scale' => '2',
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'staff' => array(
            'type' => 'table',
            'name' => 'staff',
            'updateable' => true,
            'fields' => array(
                'staff_id' => array(
                    'name' => 'staff_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'first_name' => array(
                    'name' => 'first_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'type' => 'varchar',
                    'character_maximum_length' => '45',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'address_id' => array(
                    'name' => 'address_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'address',
                        'field' => 'address_id'
                    )
                ),
                'picture' => array(
                    'name' => 'picture',
                    'type' => 'blob',
                    'character_maximum_length' => '65535',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'email' => array(
                    'name' => 'email',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'store_id' => array(
                    'name' => 'store_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'store',
                        'field' => 'store_id'
                    )
                ),
                'active' => array(
                    'name' => 'active',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '1',
                    'key' => '',
                    'reference' => false
                ),
                'username' => array(
                    'name' => 'username',
                    'type' => 'varchar',
                    'character_maximum_length' => '16',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'password' => array(
                    'name' => 'password',
                    'type' => 'varchar',
                    'character_maximum_length' => '40',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'staff_list' => array(
            'type' => 'view',
            'name' => 'staff_list',
            'updateable' => true,
            'fields' => array(
                'ID' => array(
                    'name' => 'ID',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => '0',
                    'key' => '',
                    'reference' => false
                ),
                'name' => array(
                    'name' => 'name',
                    'type' => 'varchar',
                    'character_maximum_length' => '91',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'address' => array(
                    'name' => 'address',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'zip code' => array(
                    'name' => 'zip code',
                    'type' => 'varchar',
                    'character_maximum_length' => '10',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => true,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'phone' => array(
                    'name' => 'phone',
                    'type' => 'varchar',
                    'character_maximum_length' => '20',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'city' => array(
                    'name' => 'city',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'country' => array(
                    'name' => 'country',
                    'type' => 'varchar',
                    'character_maximum_length' => '50',
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                ),
                'SID' => array(
                    'name' => 'SID',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => '',
                    'reference' => false
                )
            )
        ),
        'store' => array(
            'type' => 'table',
            'name' => 'store',
            'updateable' => true,
            'fields' => array(
                'store_id' => array(
                    'name' => 'store_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'manager_staff_id' => array(
                    'name' => 'manager_staff_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'UNI',
                    'reference' => array(
                        'dataset' => 'staff',
                        'field' => 'staff_id'
                    )
                ),
                'address_id' => array(
                    'name' => 'address_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'address',
                        'field' => 'address_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        )
    );

    /**
     * predetermined that definition is resolved
     *
     * @var boolean
     */
    protected $definitionResolved = true;

    /**
     * mocked database name
     *
     * @var string
     */
    protected $name = 'mocked_database';

    public function __construct()
    {
        $this->dataSetDescriptors = new \ArrayObject(array());
    }
}