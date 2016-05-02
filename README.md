# php_paypal_payment_processor
Process payment with paypal Adaptive Payments API [GPP SITE](http://ghostff.com/demo/oop/?Php_Paypal_Payment_Processor=php)

#DONATION
instantiate
```php
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', 'homeless kids funding', PP_SUCCESS, PP_CANCELED, 'string');
```

user defined datas
```php
$pay->info('currency type', 'ammount', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
```

data handler
```php
$pay->save(SAVE_USER_DATA, SAVE_PROD_DATA, array('id' => 'lid', 'class' => 'lclas', 'data_id' => 'ldi', 'link' => 'donate to me :(', 'target' => '_black'));
```

process stats
```php
if(!$pay->error)
	echo($pay->success);
else
	echo($pay->error)
```

index prototype
```php
require_once('funcs.php');
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', 'homeless kids funding', PP_SUCCESS, PP_CANCELED, 'string');
$pay->info('currency type', 'ammount', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
$pay->save(SAVE_USER_DATA, SAVE_PROD_DATA, array('id' => 'lid', 'class' => 'lclas', 'data_id' => 'ldi', 'link' => 'donate to me :(', 'target' => '_black'));
if(!$pay->error)
	echo($pay->success); 
else
	echo($pay->error); 
```

#BUY NOW
instantiate
```php
#instance
//$new[] = _new('name', 'amount', 'id', 'quantity', 'tax', no shipping, 'shipping charge', 'discount amount', discount rate, Optional label);
$new[] = _new('green shirt', '39.99', '18495371', null, '0.5%', '1', '2.44', null, null, 'The padded insole for your comfortable wear');

/*
for more than one product
$new[] = _new('white shirt', '32.79', '14s755596', '4', '0.5', '1', '2.44', '2.00', null, null, 'classic-style pumps feature'); -2.00 discount and 0.5 tax
$new[] = _new('blue shirt', '82.79', '147D5596', '1', '0.5%', '1', '2.44', null, '50', null); 50% discount and 0.5% tax
$new[] = _new('myebook resale', '32.79', '14755596', null, null, null, null, null, 10%, null);
$new[] = _new('software product key', '32.79', '14755596');//no ship, tax, discount, label, and quantity is 1
*/
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', $new, PP_SUCCESS, PP_CANCELED, 'string', 'sale');
```

user defined datas
```php
#data as array 
$pay->info('currency type', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
```

data handler
```php
$pay->save(SAVE_USER_DATA, SAVE_PROD_DATA, 'redirect');
```

process stats
```php
if(!$pay->error)
	echo($pay->success);
else
	echo($pay->error);
```

index prototype
```php
require_once('funcs.php');
$new[] = _new('green shirt', '39.99', '18495371', null, '0.5%', '1', '2.44', null, null, 'The padded insole for your comfortable wear');
$new[] = _new('myebook resale', '32.79', '14755596', null, null, null, null, null, 10%, null);
$pay = payment(PAYMETN_TYPE, 'paypal business or personal email address', $new, PP_SUCCESS, PP_CANCELED, 'string', 'sale');
$pay->info('currency type', 'first name', 'last name', 'email address', 'address 1', 'address 2 (optional: null)', 'city name', 'state name (2 letters abbreviation)', 
		   'zip code', 'country name (2 letters abbreviation)', 'phone 1 Area code', 'phone 1 number ', 'phone 2 Area code', 'phone 2 number');
$pay->save(SAVE_USER_DATA, SAVE_PROD_DATA, 'redirect');
if(!$pay->error)
	echo($pay->success);
else
	echo($pay->error);
```