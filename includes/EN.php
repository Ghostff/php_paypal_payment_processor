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
	"ST_EMPTY" 		=> "Specify state name abbreviation",
	"CY_EMPTY" 		=> "Specify country name abbreviation",
	"ZP_EMPTY" 		=> "Specify zip code",
	"P1_EMPTY" 		=> "Specify phone1",
	"P2_EMPTY" 		=> "Specify Phone2",
	"P3_EMPTY" 		=> "Specify Phone3",
	
	"CU_INVALID" 	=> "invalid currency type  (characters only) <code>'%m1%'</code> given",
	"AM_INVALID" 	=> "invalid ammount  (numeric characters only) <code>'%m1%'</code> given",
	"FN_INVALID" 	=> "invalid first name (Alpha numeric characters only) <code>'%m1%'</code> given",
	"LN_INVALID" 	=> "invalid last name (Alpha numeric characters only) <code>'%m1%'</code> given",
	"EM_INVALID" 	=> "invalid email address  <code>'%m1%'</code> given",
	"A1_INVALID" 	=> "invalid address1 (Alpha numeric characters only) <code>'%m1%'</code> given",
	"A2_INVALID" 	=> "invalid address2 (Alpha numeric characters only) <code>'%m1%'</code> given",
	"CT_INVALID" 	=> "invalid city name (Alpha numeric characters only) <code>'%m1%'</code> given",
	"ST_INVALID" 	=> "invalid state name abbreviation (characters only) <code>'%m1%'</code> given",
	"CY_INVALID" 	=> "invalid country name abbreviation (characters only) <code>'%m1%'</code> given",
	"ZP_INVALID" 	=> "invalid zip code  (numeric characters only) <code>'%m1%'</code> given",
	"P1_INVALID" 	=> "invalid phone1 <code>'%m1%'</code> given",
	"P2_INVALID" 	=> "invalid Phone2 <code>'%m1%'</code> given",
	"P3_INVALID" 	=> "invalid Phone3 <code>'%m1%'</code> given",
	

	"CU_COUNT" 		=> "currency type is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"AM_COUNT" 		=> "ammount is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"FN_COUNT" 		=> "first name is below %m1% or above %m2% in length  <code>'%m3%'</code> given",
	"LN_COUNT" 		=> "last name is below %m1% or above %m2% in length  <code>'%m3%'</code> given",
	"EM_COUNT" 		=> "email address is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"A1_COUNT" 		=> "address1 is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"A2_COUNT" 		=> "address2 is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"CT_COUNT" 		=> "city name is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"ST_COUNT" 		=> "state name abbreviation is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"CY_COUNT" 		=> "country name abbreviation is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"ZP_COUNT" 		=> "zip code is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"P1_COUNT" 		=> "phone1 is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"P2_COUNT" 		=> "Phone2 is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	"P3_COUNT" 		=> "Phone3 is below %m1% or above %m2% in length <code>'%m3%'</code> given",
	));
	
//Devs posible error
$lang = array_merge($lang,array(
	"DATA_NT_SVD" 		=> "file wasnt saved please check your privileges",
	"MAIL_FLD" 			=> "Mail send process (failed)",
	"NON_SAVED" 		=> "success!! no data was saved nor email sent",
	"SV_USR_SAVED" 		=> "success!! data was saved. No email sent",
	"EML_REC_SAVED" 	=> "success!! datas not saved. Email sent to <code>%m1%</code>",
	"ALL_SAVED" 		=> "success!! datas saved. Email Send to <code>%m1%</code>",
	
	"INVALID_PROCS" 	=> "No Payment method called <code>--> %m1% <--</code>, check ur spellings",
	"INVL_ARG_RTN_1" 		=> "invalid save type argument <code>%m1%</code> (<code> --> true or false <-- </code>, paypal_email, link_handler)",
	"INVL_ARG_RTN_2" 		=> "invalid save type argument <code>%m1%</code> (type, <code> --> true or false <-- </code>, link_handler)",
	"INVL_ARG_RTN_3" 		=> "invalid save type argument <code>%m1%</code> (type, paypal_email, <code> --> 'return', 'redirect', empty, null or false <-- </code>)",
	"INVL_ER_PRO_2" 		=> "invalid error return type argument <code>%m1%</code> (type, paypal_email, <code>-->product name<--</code>, error_return_type)",
	"INVL_ER_PRO_3" 		=> "invalid error return type argument <code>%m1%</code> (type, paypal_email, error_return_type,  <code> --> array</code>, <code>string</code>, <code>empty or null <-- </code>)",
	));

?>