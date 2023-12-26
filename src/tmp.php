<?php
require_once (__DIR__.'/app/GenericModel.php');

$gm = new \Webdev\Filmforge\GenericModel();
print_r($gm->insertAndProvideId("INSERT IGNORE INTO actors(fullname) VALUES('tim prom')"));
