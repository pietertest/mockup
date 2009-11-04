<?php

class PhotoUploader extends DataSource{

    function PhotoUploader() {
    }
    
    function uploadFotos() {
		$nick = "pieter";
		foreach($_FILES as $file) {
			if(empty($file['name'])){
				continue;
			}
			$foto = str_replace("'", "_", $file['tmp_name']);
			$type = explode('/', strtolower($file['type']));
			$type = $type[1];
			$foto_naam =  str_replace("'", "_", $file['name']);
			if(count(explode(".", $foto_naam))==2){
				$foto_naam = explode(".", $foto_naam);
				$foto_naam = $foto_naam[0]."_".$nick.".".$foto_naam[1];
			}
			$grootte = $file['size'];
			$grootte = explode('.', $grootte/1000);
			$grootte = $grootte[0];
			$foto_naam_temp = $foto_naam;
			while(file_exists(PHOTOS.'/'.$foto_naam)){
				$foto_naam = $foto_naam_temp.mktime();
			}
			if(copy($foto, PHOTOS.'/'.$foto_naam)) {
				$phe = new Photo();
				$phe->put("ORG_FILENAME", $foto_naam);
				$phe->put("NICK", "pieter");
				$phe->put("ZOEK_ID", $this->get('ZOEK_ID'));
				$phe->put("public", $this->get('public'));
				$phe->put("descr", $this->get('descr'));
				$phe->insert();
			}
		}
    }
}
?>