<?php
require("../../config/config.php");
//require("../../phpincl/init_smarty_config.php");
$servletdir = $_GET["servlet"];

$servletname = ucfirst($servletdir."Servlet");

require(SERVLETS_DIR.$servletdir."/".$servletname.".class.php");

$ds = new DataSource();
$ds->putAll($_REQUEST);

$servlet = new $servletname($ds);
$servlet->go();
?>
