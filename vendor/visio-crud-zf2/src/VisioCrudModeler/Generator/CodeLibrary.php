<?php
namespace VisioCrudModeler\Generator;

/**
 * registry class for retrieving code samples/templates
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class CodeLibrary
{

    /**
     * holds loaded library
     *
     * @var array
     */
    protected $library = null;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->library = require __DIR__ . '/CodeLibraryAssets/code.php';
    }

    /**
     * returns code library entry
     *
     * @param string $identifier            
     */
    public function get($identifier)
    {
        if (isset($this->library[$identifier])) {
            return $this->library[$identifier];
        }
    }

    /**
     * returns contents of file resource from code library
     *
     * @param string $filename            
     * @return string
     */
    public function getFile($filename)
    {
        return file_get_contents(__DIR__ . '/CodeLibraryAssets/' . ltrim($filename, '\\/'));
    }
}