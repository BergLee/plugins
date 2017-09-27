<?php
require("vendor/core/Application.php");
$config=require("config/main.php");
(new Application($config))->run();
?>