<?php

require_once('funcs.php');
$start = microtime(true);
$pay = new payment('donate', 'frankchris@hotmail.com', 'array');
$pay->info('USD', 50.30, 'chrys', 'ugwu', 'frankchris@hotmail.com', 'address2', null, 'city', 'tx', '545', 'country', '8323321903', null, null);
$pay->save(true, true);

if(!$pay->error)
	echo $pay->success;
else
	var_dump( $pay->error);
echo '<br>';
printf("Total time cached query: %.6fs\n", microtime(true) - $start);
	
