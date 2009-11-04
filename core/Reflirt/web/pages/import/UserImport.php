<?php
include_once PHP_CLASS . 'entities/location/CityFactory.class.php';
class UserImport {
	
	private $systemUser;
	
	private function deleteAll() {
		$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setDelete("oproepreaction");
		$pq->execute();
		$pq->setDelete("photoalbum");
		$pq->execute();
		$pq->setDelete("spot");
		$pq->execute();
		$pq->setDelete("photo");
		$pq->execute();
		$pq->setDelete("oproep");
		$pq->execute();
		$pq->setDelete("reflirt_nieuw.users");
		$pq->execute();
	}

	public function import() {
		
		if(!isset($_GET["uptodate"])) {
			DebugUtils::debug("Check: Is the user table up to date? (parameter: uptodate)");
			return;	
		}
		$oldUser = new oldUser();
		$keyColumn = $oldUser->getTable()->getKeyColumn();
		Utils::assertTrue("Check: haal de primary key van reflirt_oud.USER en set er een nieuwe op die systemid heet", $keyColumn != "NICK");
		
		$this->deleteAll();
		
		$this->systemUser = UserFactory::getSystemUser();
		 
		$oq = ObjectQuery::buildACS(new OldUser, $this->systemUser);
		//$oq->addConstraint(Constraint::eq("import", ""));
		//$oq->setLimit(300);
		$list = SearchObject::search($oq);
		$onbekendeCities = 0;
		$dateFormat = new DateFormat();
		foreach($list as $oldUser) {
			$user = new User();
			$username = $oldUser->get("NICK");
			
			$user->put("username", $username);
			$user->putCol("email", $oldUser->get("EMAIL"));
			$user->putCol("firstname", $oldUser->get("VOORNAAM"));
			$tussenvoegsel = $oldUser->get("TUSSENVOEGSEL");
			$achternaam = $oldUser->get("ACHTERNAAM");
			if(!empty($tussenvoegsel)) {
				$achternaam = $tussenvoegsel . " " . $achternaam;
			}
			$user->putCol("lastname", $achternaam);
			$user->putCol("password", $oldUser->get("PASS"));
			
			$dateFormat->setValue($oldUser->get("GEB_DAT"));
			$user->putCol("birthdate", $dateFormat->parse());
			$dateFormat->setValue($oldUser->get("REG_DAT"));
			$user->putCol("insertdate", $dateFormat->parse());
			$user->putCol("lastaction", DateUtils::now());
			
			$plaatsnaam = $oldUser->get("PLAATSNAAM");
			$city = CityFactory::getCity($plaatsnaam);
			if($city == null) {
				$onbekendeCities++;
				//echo "Onbekende stad: " . $plaatsnaam . "<br/>";
			} else {
				$user->putCol("cityid", $city->getKey());
			}
			
			$sex = $oldUser->get("GESLACHT");
			if($sex == "1") {
				$user->putCol("sex", "1");
			} else {
				$user->putCol("sex", "0");
			}
			try {
				$user->save();
			} catch(DuplicateException $e) {
				echo "bestaat al:" . $user->get("username"); 
			}
			
			// Photo
			$toon_photo_id = $oldUser->get("TOON_FOTO_ID");
			if($toon_photo_id == "1") {
				$photo = $this->makePhoto($user, $oldUser);
				if($photo != null) {
					$user->putCol("photoid", $photo->getKey());
					$user->save();
				}
			}
		}
		echo "Onbekende steden: " . $onbekendeCities. "<br/>";
	}
	
	private function makePhoto($newUser, $oldUser) {
		$nick = $oldUser->get("NICK");
		
		$oq = ObjectQuery::buildACS(new OldPhoto, $this->systemUser);
		$oq->addConstraint(Constraint::eq("NICK", $nick));
		$oldPhoto = SearchObject::select($oq);
		
		if($oldPhoto == null) {
			return null;
		}
		$filename = $oldPhoto->get("ORG_FILENAME");
		
		$album = new PhotoAlbum();
		$album->setUser($newUser);
		$album->putCol("albumname", "Mijn album");
		$album->save();
		
		$photo = new PhotoTemp();
		$photo->setUser($newUser);
		$photo->putCol("albumid", $album->getKey());
		$photo->putCol("filename", $filename);
		$photo->putCol("orig_filename", $filename);
		$photo->putCol("mimetype", "image/jpeg");
		$photo->save();
		
		echo "foto voor user $nick : " . $filename . "<br/>";
		return $photo;
	}
	
}

?>