<?php
define("TEST",true);
define("USING_BOOTSTRAP4",true);
define("USING_BOOTSTRAP3",false);
$ATK14_GLOBAL = Atk14Global::GetInstance();
$HTTP_REQUEST = new HTTPRequest();

require(__DIR__ . "/../vendor/autoload.php");
