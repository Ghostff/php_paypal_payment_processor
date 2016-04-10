<?php
require_once('funcs.php');
$pay = payment('donate', 'email', 'string');
$pay->info('USD', 50.30, 'chrys', 'ugwu', 'frankchris76@gmail.com', 'address2', null, 'city', 'tx', '5454', 'us', 'phone', null, null);
$pay->save(false, false, 'return');
if(!$pay->error)
	var_dump($pay->success);
else
	var_dump($pay->error);
?>

	
