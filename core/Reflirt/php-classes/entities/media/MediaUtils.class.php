<?php

class MediaUtils {
	
	public static final function delete(MediaEntity $ent) {
		$file = $ent->getFile();
		@unlink($file->getPath());
		$ent->delete();
	}
}

?>