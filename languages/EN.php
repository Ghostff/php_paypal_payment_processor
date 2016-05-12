<?php
/*
%m(0-9)% - Dymamic markers which are replaced at run time by the relevant index.
*/
$lang = array();
//Account
$lang = array_merge($lang,array(
	"CU_EMPTY" 		=> "Specify currency type",
	"AM_EMPTY" 		=> "Specify ammount",
	"FN_EMPTY" 		=> "Specify first name",
	"LN_EMPTY" 		=> "Specify last name",
	"EM_EMPTY" 		=> "Specify email address",
	"A1_EMPTY" 		=> "Specify address1",
	"A2_EMPTY" 		=> "Specify address2",
	"CT_EMPTY" 		=> "Specify city",
	"ST_EMPTY" 		=> "Specify state name abbreviation",
	"CY_EMPTY" 		=> "Specify country name abbreviation",
	"ZP_EMPTY" 		=> "Specify zip code",
	"P1_EMPTY" 		=> "Specify phone1 Area code",
	"P2_EMPTY" 		=> "Specify Phone1",
	"P3_EMPTY" 		=> "Specify Phone2 Area code",
	"P4_EMPTY" 		=> "Specify Phone2",
	
	"CU_INVALID" 	=> "invalid currency type  (characters only) '%m1%' given",
	"AM_INVALID" 	=> "invalid ammount  (numeric characters only) '%m1%' given",
	"FN_INVALID" 	=> "invalid first name (Alpha numeric characters only) '%m1%' given",
	"LN_INVALID" 	=> "invalid last name (Alpha numeric characters only) '%m1%' given",
	"EM_INVALID" 	=> "invalid email address  '%m1%' given",
	"A1_INVALID" 	=> "invalid address1 (Alpha numeric characters only) '%m1%' given",
	"A2_INVALID" 	=> "invalid address2 (Alpha numeric characters only) '%m1%' given",
	"CT_INVALID" 	=> "invalid city name (Alpha numeric characters only) '%m1%' given",
	"ST_INVALID" 	=> "invalid state name abbreviation (characters only) '%m1%' given",
	"CY_INVALID" 	=> "invalid country name abbreviation (characters only) '%m1%' given",
	"ZP_INVALID" 	=> "invalid zip code  (numeric characters only) '%m1%' given",
	"P1_INVALID" 	=> "invalid phone1 Area code '%m1%' given",
	"P2_INVALID" 	=> "invalid Phone1 '%m1%' given",
	"P3_INVALID" 	=> "invalid Phone2 Area code '%m1%' given",
	"P4_INVALID" 	=> "invalid Phone2 '%m1%' given",
	

	"CU_COUNT" 		=> "currency type is below %m1% or above %m2% in length '%m3%' given",
	"AM_COUNT" 		=> "ammount is below %m1% or above %m2% in length '%m3%' given",
	"FN_COUNT" 		=> "first name is below %m1% or above %m2% in length  '%m3%' given",
	"LN_COUNT" 		=> "last name is below %m1% or above %m2% in length  '%m3%' given",
	"EM_COUNT" 		=> "email address is below %m1% or above %m2% in length '%m3%' given",
	"A1_COUNT" 		=> "address1 is below %m1% or above %m2% in length '%m3%' given",
	"A2_COUNT" 		=> "address2 is below %m1% or above %m2% in length '%m3%' given",
	"CT_COUNT" 		=> "city name is below %m1% or above %m2% in length '%m3%' given",
	"ST_COUNT" 		=> "state name abbreviation is below %m1% or above %m2% in length '%m3%' given",
	"CY_COUNT" 		=> "country name abbreviation is below %m1% or above %m2% in length '%m3%' given",
	"ZP_COUNT" 		=> "zip code is below %m1% or above %m2% in length '%m3%' given",
	"P1_COUNT" 		=> "phone1 Area code is below %m1% or above %m2% in length '%m3%' given",
	"P2_COUNT" 		=> "Phone1 is below %m1% or above %m2% in length '%m3%' given",
	"P3_COUNT" 		=> "Phone2 Area code is below %m1% or above %m2% in length '%m3%' given",
	"P4_COUNT" 		=> "Phone2 is below %m1% or above %m2% in length '%m3%' given",
	));
//email donation reciept
$lang = array_merge($lang,array(
	"DONATE_RECPT" 	=> "%m4% donation receipt",
	"DONATE_MESG" 		=> "Hello %m2%,".PHP_EOL."
		Thanks for your donation at %m4%.
		The merchant has completed your transaction. Please see your transaction details and final purchase price below.
		Item: 				%m3%".PHP_EOL."
		Purchase ID:			%m1%".PHP_EOL."
		Date				%m6%
		Ammount				%m5%",
	));

//email shop reciept
$lang = array_merge($lang,array(
	"SHOP_RECPT" 		=> "%m1% payment receipt",
	"SHOP_MESG" 		=> "Hello %m2%,".PHP_EOL."
		Thanks for your purchase at %m4%.
		The merchant has completed your transaction. Please see your transaction details and final purchase price below.
		Item: 				%m3%".PHP_EOL."
		Purchase ID:			%m1%".PHP_EOL."
		Date				%m10%
		Descriptions			Amount ".PHP_EOL."
		Item total:			%m5%".PHP_EOL."
		Tax:				%m6%".PHP_EOL."
		Discount:			%m9%".PHP_EOL."
		Shipping:			%m7%".PHP_EOL."
		Total:				%m8%".PHP_EOL."",
	));
	
//Devs posible errors
$lang = array_merge($lang,array(
	"DE_INVALID" 		=> "Umm, is your paypal email correct? @param 2 of payment",
	"DATA_NT_SVD" 		=> "file wasnt saved please check your privileges",
	"MAIL_FLD" 			=> "Mail send process (failed)",
	
	"USER_N_SAVED" 		=> "user data (failed to save) ",
	"PAYM_N_SAVED" 		=> "payment status (failed to save) ",
	"PAY_N_SAVED" 		=> "payment data (failed to save) ",
	
	"UNIQ_GEN" 			=> "Unique id failed to generate",
	"EMAIL_USER" 		=> "thanks for your payment a receipt will be sent to your email shortly",
	"EMAIL_USERN" 		=> "thanks for your payment",
	"CANCELD_P" 		=> "Payment canceled",
	
	"FETCH_P" 			=> "error in excuting payment process 'get'",
	"FETCH_U" 			=> "payment informations failed to update",
	"EMAIL_F" 			=> "cant send receipt to %m1% with payment id of %m2%",
	"NO_ERROR" 			=> "its fine :)",
	
	"SV_USR_SAVED" 		=> "users data was saved",
	"NON_SAVED" 		=> "nothing went wrong and users data not saved",

	"S_PROD_1" 			=> "invalid 'save' parameter 1. expected value (bool) of save user data, '%m1%' given",
	"S_PROD_2" 			=> "invalid 'save' parameter 2. expected value (bool) of save payment data, '%m1%' given",
	"S_PROD_3" 			=> "invalid 'save' parameter 3. expected value ('return', 'redirect', null or (array)) of link and attributes, '%m1%' given",
	
	"D_PROD_3" 			=> "invalid 'payment' parameter 3. expected value (string) of produnct name, '%m1%' given",
	"D_PROD_4" 			=> "invalid 'payment' parameter 4. expected value (string) of url, '%m1%' given",
	"D_PROD_5" 			=> "invalid 'payment' parameter 5. expected value (string) of url, '%m1%' given",
	"D_PROD_6" 			=> "invalid 'payment' parameter 6. expected value ('string', 'array', null), '%m1%' given",
	
	"D_PAY_3" 			=> "invalid 'pay' parameter 3. expected value ((array)) of produnct name, '%m1%' given",
	"D_PAY_4" 			=> "invalid 'pay' parameter 4. expected value (string) of url, '%m1%' given",
	"D_PAY_5" 			=> "invalid 'pay' parameter 5. expected value (string) of url, '%m1%' given",
	"D_PAY_6" 			=> "invalid 'pay' parameter 6. expected value ('sale', 'authorization', 'order'), '%m1%' given",
	"D_PAY_7" 			=> "invalid 'pay' parameter 6. expected value ('string', 'array', null), '%m1%' given",
	));

?>