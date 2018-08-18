<?php
require_once "Loader.php";
use wycto\helper\HelperSpell;
$re = HelperSpell::getPinYin("我想你了");
var_dump($re);
 ?>
