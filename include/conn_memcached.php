<?php
	$memcache = new Memcached();
	$memcache->addServer('localhost', 11211) or die ("Could not connect");
?>