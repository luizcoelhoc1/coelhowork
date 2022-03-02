<?php

/*
 * Data user and database
 */
const __DB_USER = "id564090_db";
const __DB_USER_PASS = "";
const __DB_NAME = "id564090_db";
const __DB_HOST = "localhost";
const __DB_PORT = "3306";


/*
 * 
 */
const __EMAIL = [
];

/* Informações login */
const __LOGINPATHFILECLASS = "login/Login";


/* Layout Padrão das páginas */
const __LAYOUTDEFAULT = __DIR__ . "/view/layout.tpl";
const __TEMPLATEDEFAULT = __DIR__ . "/view/layout.tpl";

/* Class of first page */
const __FIRSTPAGE = "Index";

/*defaults url path*/
const __DEFAULTCLASSDIR = "Index";
const __METHODNAME = "index";

/* paths */
const __ROOT = __DIR__;
const __PATHFILESCLASS = __ROOT . "/class/controller/";
const __EXTENSIONFILESCLASS = ".class.php";

/* Language default */
const __LANGDEFAULT = "pt_br";

/* hash */
const __HASH = "sha256";

//setDisplay Error
error_reporting(E_ALL); //E_ALL
ini_set('display_errors', 1); //1

//setMemoryLimit
ini_set('memory_limit', '-1');
