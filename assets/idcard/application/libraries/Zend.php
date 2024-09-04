<?php
 
/* * *************************************************************************************
 *                              Zend.php
 * **************************************************************************************
 *      Author:         Topidesta as Shabiki <m.desta.fadilah@hotmail.com>
 *      Website:        http://www.twitter.com/emang_dasar
 *
 *      File:           Zend
 *      Created:    26 Sep 14 6:44:55 WIB
 *      Copyright:      (c) 2012 - desta
 *                      DON'T BE A DICK PUBLIC LICENSE
 *          Version 1, December 2009
 *          Copyright (C) 2009 Philip Sturgeon
 *      source:
 *              http://framework.zend.com/manual/1.12/en/zend.barcode.introduction.html
 *              http://mevy.wordpress.com/2014/04/10/codeigniter-barcode-generator-menggunakan-zend-barcode-library/
 *              http://stackoverflow.com/a/17465367
 * ************************************************************************************** */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class CI_Zend
{
    /*
     * Constructor..
     *
     */
    function __construct($class = null)
    {
        // Inisialisasi folder
        ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'third_party');
 
        if ($class)
        {
            require_once (string) $class . EXT;
            log_message('debug', "Zend class $class loaded");
        }
        else
        {
            log_message('debug', "Zend class initialized");
        }
 
    }
 
    /*
     * Zend class loader
     *
     */
    function load($class)
    {
        require_once (string) $class . EXT;
        log_message('debuq', "Zend class $class loaded");
    }
}
 
/* End of File: Zend.php */