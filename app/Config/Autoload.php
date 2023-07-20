<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'         => APPPATH
     *   ];
     *
     * @var array<string, array<int, string>|string>
     * @phpstan-var array<string, string|list<string>>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *
     * @var array<string, string>
     */
    public $classmap = [
        'AuxOutput'  =>  APPPATH.'Libraries/AuxOutput.php',
        'CirciutType1'  =>  APPPATH.'Libraries/CirciutType1.php',
        'DBSession'  =>  APPPATH.'Libraries/DBSession.php',
        'EventCode'  =>  APPPATH.'Libraries/EventCode.php',
        'GroupTable'  =>  APPPATH.'Libraries/GroupTable.php',
        'Input'  =>  APPPATH.'Libraries/Input.php',
        'Language'  =>  APPPATH.'Libraries/Language.php',
        'Pagination'  =>  APPPATH.'Libraries/Pagination.php',
        'R_G_toggle1'  =>  APPPATH.'Libraries/R_G_toggle1.php',
        'Reverse'  =>  APPPATH.'Libraries/Reverse.php',
        'Uploader'  =>  APPPATH.'Libraries/Uploader.php',
        'upload'  =>  APPPATH.'Libraries/upload.php',
        'CirciutType'  =>  APPPATH.'Libraries/CirciutType.php',
        'ConstTable'  =>  APPPATH.'Libraries/ConstTable.php',
        'EnumTable'  =>  APPPATH.'Libraries/EnumTable.php',
        'Form'  =>  APPPATH.'Libraries/Form.php',
        'HolidayType'  =>  APPPATH.'Libraries/HolidayType.php',
        'Lang'  =>  APPPATH.'Libraries/Lang.php',
        'Log'  =>  APPPATH.'Libraries/Log.php',
        'R_G_toggle'  =>  APPPATH.'Libraries/R_G_toggle.php',
        'ReaderType'  =>  APPPATH.'Libraries/ReaderType.php',
        'State'  =>  APPPATH.'Libraries/State.php',
        'Util'  =>  APPPATH.'Libraries/Util.php',
    ];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $helpers = [ 'custom' ];
}
