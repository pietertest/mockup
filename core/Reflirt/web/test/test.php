<script src="http://localhost/test/test.php" ></script>

<?

include("../../config/init_smarty_config.php");

include_once(PHP_CLASS."utils/DateUtils.class.php");
echo DateUtils::getDateTime("d-m-Y H:i:s");
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
$simplesearch = "ik ben een aap";
$newName = preg_replace("/(\s+)/i", "_", $simplesearch); // Zet alle spaties om in een '_'
echo $newName;


?>