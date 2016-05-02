<?php
require_once('funcs.php');

/*
---- UPDATE PARAMS FOR SHOP ------
@param 1 Action(h = canceled | u = complteted) of product id 
@param 2 Replacement for payment status in db (completed: replaces pending with completed) while (canceled: replaces pending with canceled) **dont change
@param 3 payment type (default 'donate') find( 'PAYMETN_TYPE' in conn.inc and modify )
@param 5 mail reciept to user (default (bool) true) **need more validation for this (mostly if @param 3 of save is not 'redirect') action DO NOT USE UNDER PRODUCTION
---REASON
	user can always go to 'www.mysitename.com/stats.php?h=generated_id(1ABD83983952C)
	and get an email sent to them.
*/
if(isset($_GET['u']) && $user = payment_solution::update(htmlspecialchars($_GET['u']), 'completed', PAYMETN_TYPE, MAIL_RECEIPT)){
	 echo $user;
}
else if(isset($_GET['h']) && $user = payment_solution::update(htmlspecialchars($_GET['h']), 'canceled', PAYMETN_TYPE)){
	echo $user;
}
else
	echo 'invalid or expired token';
?>