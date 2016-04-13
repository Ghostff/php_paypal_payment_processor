<?php
/*-----------------------------------------PAYMENT--------------------------------------------------------
payment methods(
	+--+ donate (done)
	+--+ buy_now
	+--+ add_to_cart
	+--+ gift
	+--+ subscription
	+--+ billing
	+--+ payment_plan
	)
payment(payment method, paypal bussness email adress, item name, error return type)

-----------------------------------------PAYMENT-END--------------------------------------------------------*
/*-----------------------------------------INFO--------------------------------------------------------
$pay->info(currency,
			ammount, +++++++++++++++++++++ maxlen(20) type(numeric or float characters only)
			first_name, +++++++++++++++++++++ maxlen(32) type(Alpha numeric characters only)
			last_name, +++++++++++++++++++++ maxlen(64) type(Alpha numeric characters only)
			email_address, +++++++++++++++++++++ maxlen(127)
			address1, +++++++++++++++++++++ maxlen(100) type(Alpha numeric characters only)
			address2 = null, +++++++++++++++++++++ maxlen(100) type(Alpha numeric characters only)
			city, +++++++++++++++++++++ maxlen(100) type(Alpha numeric characters only)
			state, +++++++++++++++++++++ maxlen(2) type(Alpha numeric characters only)
			zip_code, +++++++++++++++++++++ maxlen(32) type(numeric characters only)
			country, +++++++++++++++++++++  
			night_phone_a = null, +++++++++++++++++++++ maxlen(16) type(numeric(+,(and)) characters only)
			night_phone_b = null, +++++++++++++++++++++ maxlen(4) type(Alpha numeric characters only)
			night_phone_c = null); +++++++++++++++++++++ maxlen(3) type(Alpha numeric characters only)
		)
https://www.paypal-knowledge.com/infocenter/index?page=content&id=FAQ1372&actp=LIST (detailed doc)*
			
-----------------------------------------END-INFO--------------------------------------------------------*/



/*-----------------------------------------SAVE--------------------------------------------------------
$pay->save(
			save_user_info, if true save user data( defualt user_data.log not database)
			email_receipt, email receipt of payment( defualt false)
			new_link, arg(return): displays the html link
					  arg(redirect): redirects to paypal checkout
					  arg(false, null or empty) retrun the status of save_user_info and email_receipt
		)

-----------------------------------------SAVE-END--------------------------------------------------------*/
?>
