#!/usr/bin/php
<?php
define('CURRENT_DIR', dirname(__FILE__) . "/");

$consoleDirPath = CURRENT_DIR . "console";
$interfaceDirPath = CURRENT_DIR . "interface";

include_once($interfaceDirPath . "/" . "ConsoleCommand.php");
include_once($consoleDirPath . "/" . "MainCommand.php");

$result = new MainCommand(CURRENT_DIR, $argv);
echo $result->result();
