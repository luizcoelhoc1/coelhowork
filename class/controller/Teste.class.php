<?php
/*
 * This file is how to write a new page.
 */

/**
 * Example of a page 
 * @author luizcoelhoc1
 */
class Teste /*path of page is defined by path of this file + name of the class + method*/ {
    
    function __construct() {
        $this->required = [
            //"login" => true, /*if login is required just uncomment this line*/
            //"permission" => "ADMIN" /*if special permission is required just add the name of permission here like the example*/
        ];
        //$this->layout = "view/layoutDiferente.tpl"; /*if you need reset layout of the page, set the html file with tags here*/
        
    }
	
    function index () {
        $this->data->conteudo = "<p>hello world</p>";/*to put any html thing on to layout on place of tag *conteudo* on this example, set data like this line*/
		//console($this->data);/* console of anything like this if you want debug something*/
    }
    
}