<?php
require_once('funcs.php');
$pay = payment('donate', 'paypal_email', 'Donation to my website', 'string');
$pay->info('USD', '50', 'fname', 'lname', 'email', 'addr1', null, 'city', 'state', 'zip', 'country', 'phone', null, null);
$pay->save(false, false, 'redirect');
if(!$pay->error)
	var_dump($pay->success);
else
	var_dump($pay->error);
?>

	
