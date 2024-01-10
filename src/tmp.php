<?php
$hashed = hash('sha256','supagudVHS');
echo ($hashed);
@$rez = ["id"=>"1","hashedpassword"=>$hashed];
//echo (hash('sha256','supagudVHS') === $rez['hashedpassword']);