<?php
require_once (__DIR__ . '/app/GenericQuery.php');

$gm = new \Webdev\Filmforge\GenericQuery();
print_r($gm->insertAndProvideId("INSERT IGNORE INTO actors(fullname) VALUES('tim prom')"));
