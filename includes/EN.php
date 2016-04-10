<?php
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
	"ST_EMPTY" 		=> "Specify state",
	"CY_EMPTY" 		=> "Specify country",
	"ZP_EMPTY" 		=> "Specify zip code",
	"P1_EMPTY" 		=> "Specify phone1",
	"P2_EMPTY" 		=> "Specify Phone2",
	"P3_EMPTY" 		=> "Specify Phone3",
	
	"CU_INVALID" 	=> "invalid currency type  (characters only)",
	"AM_INVALID" 	=> "invalid ammount  (numeric characters only)",
	"FN_INVALID" 	=> "invalid first name (Alpha numeric characters only)",
	"LN_INVALID" 	=> "invalid last name (Alpha numeric characters only)",
	"EM_INVALID" 	=> "invalid email address",
	"A1_INVALID" 	=> "invalid address1 (Alpha numeric characters only)",
	"A2_INVALID" 	=> "invalid address2 (Alpha numeric characters only)",
	"CT_INVALID" 	=> "invalid city name (Alpha numeric characters only)",
	"ST_INVALID" 	=> "invalid state name (characters only)",
	"ZP_INVALID" 	=> "invalid zip code  (numeric characters only)",
	"P1_INVALID" 	=> "invalid phone1",
	"P2_INVALID" 	=> "invalid Phone2",
	"P3_INVALID" 	=> "invalid Phone3",
	

	"CU_COUNT" 		=> "currency type is below %m1% or above %m2% in length ",
	"AM_COUNT" 		=> "ammount is below %m1% or above %m2% in length ",
	"FN_COUNT" 		=> "first name is below %m1% or above %m2% in length ",
	"LN_COUNT" 		=> "last name is below %m1% or above %m2% in length ",
	"EM_COUNT" 		=> "email address is below %m1% or above %m2% in length ",
	"A1_COUNT" 		=> "address1 is below %m1% or above %m2% in length ",
	"A2_COUNT" 		=> "address2 is below %m1% or above %m2% in length ",
	"CT_COUNT" 		=> "city name is below %m1% or above %m2% in length ",
	"ST_COUNT" 		=> "state name is below %m1% or above %m2% in length ",
	"ZP_COUNT" 		=> "zip code is below %m1% or above %m2% in length ",
	"P1_COUNT" 		=> "phone1 is below %m1% or above %m2% in length ",
	"P2_COUNT" 		=> "Phone2 is below %m1% or above %m2% in length ",
	"P3_COUNT" 		=> "Phone3 is below %m1% or above %m2% in length ",
	));
	
//Devs posible error
$lang = array_merge($lang,array(
	"DATA_NT_SVD" 		=> "file wasnt saved please check your privileges",
	"MAIL_FLD" 			=> "Mail send process (failed)",
	"NON_SAVED" 		=> "success!! not data was saved nor email sent",
	"SV_USR_SAVED" 		=> "success!! data was saved. No email sent",
	"EML_REC_SAVED" 	=> "success!! datas not saved. Email sent to <code>%m1%</code>",
	"ALL_SAVED" 		=> "success!! datas saved. Email Send to <code>%m1%</code>",
	
	"INVALID_PROCS" 	=> "No Payment method called <code>--> %m1% <--</code>, check ur spellings",
	"INVL_ER_PRO" 		=> "invalid error return type (type, paypal_email, <code> --> array</code>, <code>string</code>, <code>empty or null <-- </code>)",
	));

?>