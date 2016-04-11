<?php
require_once('funcs.php');
$pay = payment('donate', 'email', 'string');
$pay->info('USD', 'amt', 'fname', 'lname', 'email', 'addr1', (null|addr2), 'city', 'tx', '5454', 'country', 'phone', null, null);
$pay->save(false, false, 'return');
if(!$pay->error)
	var_dump($pay->success);
else
	var_dump($pay->error);
?>

	
