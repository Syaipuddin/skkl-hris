<?php
 
/* * *************************************************************************************
 *                              barcode.php
 * **************************************************************************************
 *      Author:         Topidesta as Shabiki <m.desta.fadilah@hotmail.com>
 *      Website:        http://www.twitter.com/emang_dasar
 *
 *      File:           Barcode
 *      Created:    25 Sep 14 11:28:41 WIB
 *      Copyright:      (c) 2012 - desta
 *                      DON'T BE A DICK PUBLIC LICENSE
 *          Version 1, December 2009
 *          Copyright (C) 2009 Philip Sturgeon
 *
 * ************************************************************************************** */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Barcode extends CI_Controller
{
    //put your code here
    function __construct()
    {
        parent::__construct();
        $this->load->library('zend');
    }
 
    function index()
    {
        $this->load->view("barcode");
    }
 
    function generate($kode)
    {
        // we load zend barcode code39
        $this->zend->load('Zend/Barcode');
        Zend_Barcode::render('code39', 'image', array('text' => $kode, 'drawText' => False), array());
 
    }
}
 
/* End of File: zendbar.php */