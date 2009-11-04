<?php

class EntityUtils {
	
	public static final function save(Entity $ent, User $user, Map $map) {
		$e = new $ent();
		$e->setUser($user);
		$e->putAll($map);
		$e->save();
		return $e;
	}
	
}
?>