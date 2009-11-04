<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS.'utils/PhotoResizer.class.php');
include_once(PHP_CLASS.'io/File.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class PhotoResizerTest extends TestCase {
	
	public function testResizeTo() {
		$file = new File(PHOTOS."unittest.jpg");
		$name = $file->getBasename();
		
		$url = $file->getPath();
		$resizer =  new PhotoResizer($url);
		$resizer->resizeTo(TEMP_DIR.$name, 90, 200);

		$this->assertTrue("Photo in ".TEMP_DIR.$name."bestaat niet",file_exists(TEMP_DIR.$name));
	}
} 

$test = new PhotoResizerTest();
$test->run();
?>
