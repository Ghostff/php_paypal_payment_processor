# php_paypal_payment_processor

instantiate
```php
#$pay = payment(payment type, business email, item name, error return type);
#args as array
$pay = payment('donate', array('paypal_email', 'Donation to my website', 'string'));
#args as string
$pay = payment('donate', 'paypal_email', 'Donation to my website', 'string')
```

user defined datas
```php
#data as array 
$pay->info(array('USD', '50', 'fname', 'lname', 'email', 'addr1', null, 'city', 'state', 'zip', 'country', 'phone', null, null));
#data as string
$pay->info('USD', 'amt', 'fname', 'lname', 'email', 'addr1', null, 'city', 'tx', 'zip', 'country', 'phone', null, null);
```

data handler
```php
#$pay->save(save user data, email user, url handler);
$pay->save(false, false, 'return');
```

process stats
```php
if(!$pay->error)
	var_dump($pay->success);
else
	var_dump($pay->error);
```

index prototype
```php
require_once('funcs.php');
$pay = payment('donate', 'email', 'string');
$pay->info('USD', 'amt', 'fname', 'lname', 'email', 'addr1', null, 'city', 'tx', 'zip', 'country', 'phone', null, null);
$pay->save(false, false, 'return');
if(!$pay->error)
	var_dump($pay->success);
else
	var_dump($pay->error);
```
