<?php
/*-----------------------------------------PAYMENT--------------------------------------------------------
+--+ donate
+--+ buy_now
+--+ add_to_cart
+--+ gift
+--+ subscription
+--+ billing
+--+ payment_plan
payment(type_of_payment, paypal_email, check_if_email_is_a_valid_paypal_email)

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
https://www.paypal.com/cgi-bin/webscr?cmd=_pdn_xclick_prepopulate_outside (detailed doc)*
			
-----------------------------------------END-INFO--------------------------------------------------------*/
/*-----------------------------------------SAVE--------------------------------------------------------
$pay->save(
			save_user_info, if true save user data( defualt user_data.log not database)
			email_receipt, email receipt of payment( defualt false)
		)










-----------------------------------------SAVE-END--------------------------------------------------------*/
?>
