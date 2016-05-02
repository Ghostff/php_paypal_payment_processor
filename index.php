<?php
require_once('funcs.php');
/*
---- PAYMENT PARAMS FOR DONATION ------
@param 1 payment type (default 'donate')
@param 2 your paypal business or personal email address
@param 3 Name of item users will be donating for (default 'homeless kids funding' )
@param 4 The URL to which PayPal redirects buyers' browser after they complete their payments (**CHNAGE THIS )
@param 5 The URL to which PayPal redirects buyers' browser if they canceled their payments (**CHNAGE THIS )
@param 6 Error return type if(array(error will be retruned as array)) if(string(errror will be return as string)) if null (no error will be returned) default (string)
*/
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', 'homeless kids funding', PP_SUCCESS, PP_CANCELED, 'string');
/*
---------- USER DEFINED DATA FOR DONATION -------------------
@param 1  currency type eg("USD", "EUR", "GBP", "CAD", "JPY")
@param 2  ammount eg(50, 55.5, 99.99)
@param 3  first name(A-Z) only
@param 4  last name(A_Z) only
@param 5  email address
@param 6  address 1
@param 7  address 2(optional: null)
@param 8  city name
@param 9  state name(2 letters abbreviation) eg('mx', 'tx', 'lg')
@param 10 zip code eg('7765', '4454', 'S4422')
@param 11 country name(2 letters abbreviation) eg('ng', 'us', 'bg')
@param 12 phone 1 Area code eg(123, 234, 345)  (optional: null)
@param 13 phone 1 number eg('5518727323', '0802783893', '081237833767')  (optional: null)
@param 14 phone 2 Area code eg(123, 234, 345)  (optional: null)
@param 15 phone 2 number eg('5518727323', '0802783893', '081237833767')  (optional: null)
*/
$pay->info('currency type', 'ammount', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
/*
---------- SAVE PARAMS FOR DONATION -------------------
@param 1  save product information and payment status (default (bool) true)
@param 2  save users informations (default (bool) true)
@param 3  how to handle paypal rendered link arg('return', 'redirect', (array))
---@returrn: 	return the rendered paypal gateway url as string
---@redirect:	redirect users browser to paypal payment gateway
---@array:		return html link tag to user 
			array('id' 	  => 'lid', 					<a href='payapl_gw_url', id='lid' 
				  'class' => 'lclass', 					<a href='payapl_gw_url', id='lid' class='lclass'
				  'link'  => 'click here to donate', 	<a href='payapl_gw_url', id='lid' class='lclass'> click here to donate </a>
				 )
				  
*/
$pay->save(SAVE_PROD_DATA, SAVE_USER_DATA, array('id' => 'lid', 'class' => 'lclas', 'data_id' => 'ldi', 'link' => 'donate to me :(', 'target' => '_black'));
if(!$pay->error)
	echo($pay->success); //no error occured in process
else
	echo($pay->error); //error occured in process


/*
---------- DEFINED PRODUCT DATA PARAM FOR SHOP -------------------
@param 1  item name
@param 2  item amount
@param 3  item id (they id of the product from product database)
@param 4  item quantity (optional: null) default '1' wen set to null
@param 5  item tax (optional: null) tax is charged under a flat ammount if (x.x) and charged with percentage if (x.x%) were 'x' is the value eg(5.5 and 5.5%)
@param 6  no shipping (optional: null) use ('1') if shipment charge should be applied
@param 7  shipping charge (optional: null) note ** (@param 6 no shipping) must be '1' for this to take effect
@param 8  discount amount (optional: null) a flat discount ammount
@param 9  discount_rate (optional: null) note ** this will only have an effect if (@param 8  discount amount) is set to null
@param 10  cn Optional label that will appear above the note field

https://www.paypal.com/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside#methodone
*/					

//instance
//$new[] = _new('name', 'amount', 'id', 'quantity', 'tax', no shipping, 'shipping charge', 'discount amount', discount rate, Optional label);

$new[] = _new('green shirt', '39.99', '18495371', null, '0.5%', '1', '2.44', null, null, 'The padded insole for your comfortable wear');

/*
for more than one product
$new[] = _new('white shirt', '32.79', '14s755596', '4', '0.5', '1', '2.44', '2.00', null, null, 'classic-style pumps feature'); -2.00 discount and 0.5 tax
$new[] = _new('blue shirt', '82.79', '147D5596', '1', '0.5%', '1', '2.44', null, '50', null); 50% discount and 0.5% tax
$new[] = _new('myebook resale', '32.79', '14755596', null, null, null, null, null, 10%, null);
$new[] = _new('software product key', '32.79', '14755596');//no ship, tax, discount, label, and quantity is 1
*/

/*
---- PAYMENT PARAMS FOR SHOP ------
@param 1 payment type (default 'donate') find( 'PAYMETN_TYPE' in conn.inc and change to 'donate' to shop )
@param 2 your paypal business or personal email address
@param 3 added up items (default 'homeless kids funding' )
@param 4 The URL to which PayPal redirects buyers' browser after they complete their payments (**CHNAGE THIS )
@param 5 The URL to which PayPal redirects buyers' browser if they canceled their payments (**CHNAGE THIS )
@param 6 Error return type if(array(error will be retruned as array)) if(string(errror will be return as string)) if null (no error will be returned) default (string)
@param 7 Indicates whether the payment is a final sale or an authorization for a final sale, to be captured later Allowable arg('sale', 'authorization', order)

https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/
*/
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', $new, PP_SUCCESS, PP_CANCELED, 'string', 'sale');
/*
---------- USER DEFINED DATAS -------------------
@param 1  currency type eg("USD", "EUR", "GBP", "CAD", "JPY")
@param 2  first name (A-Z) only
@param 3  last name (A_Z) only
@param 4  email address
@param 5  address 1
@param 6  address 2 (optional: null)
@param 7  city name
@param 8  state name (2 letters abbreviation) eg('mx', 'tx', lg')
@param 9  zip code eg('7765', '4454', 'S4422')
@param 10 country name (2 letters abbreviation) eg('ng', 'us', 'bg')
@param 11 phone 1 Area code eg(123, 234, 345)  (optional: null)
@param 12 phone 1 number eg('5518727323', '0802783893', '081237833767')  (optional: null)
@param 13 phone 2 Area code eg(123, 234, 345)  (optional: null)
@param 14 phone 2 number eg('5518727323', '0802783893', '081237833767')  (optional: null)
*/
$pay->info('currency type', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
/*
---------- SAVE PARAMS FOR DONATION -------------------
@param 1  save product information and payment status (default (bool) true)
@param 2  save users informations (default (bool) true)
@param 3  how to handle paypal rendered link arg('return', 'redirect', (array))
---@returrn: 	return the rendered paypal gateway url as string
---@redirect:	redirect users browser to paypal payment gateway
---@array:		return html link tag to user 
			array('id' 	  => 'lid', 					<a href='payapl_gw_url', id='lid' 
				  'class' => 'lclass', 					<a href='payapl_gw_url', id='lid' class='lclass'
				  'link'  => 'click here to donate', 	<a href='payapl_gw_url', id='lid' class='lclass'> click here to donate </a>
				 )
				  
*/
$pay->save(SAVE_PROD_DATA, SAVE_USER_DATA, 'redirect');
if(!$pay->error)
	echo($pay->success); //no error occured in process
else
	echo($pay->error); //if error occured in process
?>

	
