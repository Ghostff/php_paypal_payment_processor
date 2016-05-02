<?php
/*
-------------------------------------------- NOTE ----------------------------------------------------- 
all codes lines labled debuger can be removed to increase efficient, if everything is working perfectly.
*/
//language file default EN(English)
$included = true;
include_once('includes/conn.php');
require_once('languages/'.DEFAULT_LANG.'.php');
class payment_solution
{
	public $error = null;
	public $success = false;
	protected $product_name = null;
	protected $pp_email = null;
	protected $error_output;
	protected $pp_url = 'https://www.paypal.com/cgi-bin/webscr?';
	protected $form_data = array();
	protected $payment_type;
	protected $pp_cancel_url;
	protected $pp_return_url;
	protected $payment_action;
	protected $payment_id;
	protected static $dev_logs = array();
	public $info;
	public function __construct()
	{
		//validation for error return type @4th param of payment
		//4th param debuger start
		if($this->error_output != 'array' && $this->error_output != 'string'){
			$this->error = $this->lang("D_PROD_6", array($this->error_output));
		}
		if(!$this->pp_cancel_url){
			$this->error = $this->lang("D_PROD_5", array($this->pp_cancel_url));
		}
		else if(!$this->pp_return_url){
			$this->error = $this->lang("D_PROD_4", array($this->pp_return_url));
		}
			
		if($this->payment_type == 'donate'){
			if(trim($this->product_name) == false){
				$this->error = $this->lang("D_PROD_3", array($this->product_name));
			}
		}
		else if($this->payment_type == 'shop'){
			if(!$this->product_name || !is_array($this->product_name)){
				$this->error = $this->lang("D_PAY_3", array($this->product_name));
			}
			else if(!$this->pp_cancel_url){
				$this->error = $this->lang("D_PAY_4", array($this->pp_cancel_url));
			}
			else if(!$this->pp_return_url){
				$this->error = $this->lang("D_PAY_5", array($this->pp_return_url));
			}
			else if(!in_array($this->payment_action, array('sale', 'authorization', 'order'))){
				$this->error = $this->lang("D_PAY_6", array($this->payment_action));
			}
			else if($this->error_output != 'array' && $this->error_output != 'string'){
				$this->error = $this->lang("D_PAY_7", array($this->error_output));
			}
		}
		$this->validate_input($this->pp_email, 'req|email', 'DE');
		//4th param debuger end
	}
	//save type of payment and set payment status to pending(not completed)
	//save informations about payment
	//saves user defined inforamtion after safe check is done
	//@param 1 array of users informations
	//@param 2 switch to type of info to save(user info, payment info or both)
	//@param 3 current payment processor
	protected function log_info($user, $parttern, $type)
	{
		global $mysqli;
		$status = 'pending';
		$time = time();
		$new_error = '';
		//user data
		if($parttern == 'user'){
			$save_user = true;
			$stmt = $mysqli->prepare("INSERT INTO  users_data
							(payment_id, first_name, last_name, address1, address2, city, state, lc, email, zip, night_phone_a, night_phone_b, day_phone_a, day_phone_b) 
							VALUES 
							(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");			
			$stmt->bind_param("ssssssssssiiii",  $this->payment_id, $user['first_name'], $user['last_name'], $user['address1'], $user['address2'],
												$user['city'], $user['state'], $user['lc'], $user['email'], $user['zip'], $user['night_phone_a'],
												$user['night_phone_b'], $user['day_phone_a'], $user['day_phone_b']);
			//payment status
		}
		
		//payment data
		if($parttern == 'payment'){
			$save_payment = true;
			$stmt1 = $mysqli->prepare("INSERT INTO payment_status (payment_id, status, type, `date`) VALUES (?, ?, ?, ?)");
			$stmt1->bind_param("sssi", $this->payment_id, $status, $type, $time);
			
			if($type == 'donate'){
				$stmt2 = $mysqli->prepare("INSERT INTO donate (payment_id, currency_code, amount, name) VALUES (?, ?, ?, ?)");
				$stmt2->bind_param("ssss", $this->payment_id, $user['currency_code'], $user['amount'], $user['item_name']);
				if(!$stmt2->execute()){
					$new_error .= $this->lang("PAY_N_SAVED");
					$this->dev_logs[] = $new_error;
				}
			}
			else if($type == 'shop'){
				foreach($user['item_name'] as $key => $new_data){
					@$tax = ($new_data['tax_'])? $new_data['tax_'] : $new_data['tax_rate_']; //change tax flat rate to tax pecentage if '%' exist in tax arg
					@$quantity = (!$new_data['quantity_'])? 1 : $new_data['quantity_']; //set quatity to 1 if non specified
					$stmt2 = $mysqli->prepare("INSERT INTO shop 
											  (payment_id, currency_code, amount, name, quantity, tax, shipping, shipping_charge, discount_amount, discount_rate, cn) 
											  VALUES 
											  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
											  
					$stmt2->bind_param("ssssisissss", $this->payment_id, $user['currency_code'], $new_data['amount_'], $new_data['item_name_'], $quantity,
										 $tax, $new_data['no_shipping_'], $new_data['shipping_'], $new_data['discount_amount_'], 
										 $new_data['discount_rate_'], $new_data['cn_']);
					if(!$stmt2->execute()){
						$new_error = $this->lang("PAY_N_SAVED");
						$this->dev_logs[] = $new_error;
					}
				}
			}
		}
		if(@$save_user && !$stmt->execute()){
			$new_error .= $this->lang("USER_N_SAVED");
			$this->dev_logs[] = $new_error;
		}
		if(@$save_payment && !$stmt1->execute()){
			$new_error .= $this->lang("PAYM_N_SAVED");
			$this->dev_logs[] = $new_error;
		}
		$this->error = $new_error;
	}
	//@param 1 allow loging of users specified info (default true)
	//@param 2 allow loging payment data (default true)
	//@param 3 paypal getway handling (default redirect)
	public function save($save_user_data, $save_payment_data, $new_link = null)
	{
		if(!$this->error){
			//save user specified info @2nd param
			if($save_user_data === true){
				$this->log_info($this->form_data, 'user', $this->payment_type);	
			} //@1st param dubger start
			else if($save_user_data !== false){
				$this->error = $this->lang('S_PROD_1', array($save_user_data));
			}//@1st param dubuger end
			//save user specified info @2nd param
			if($save_payment_data === true){
				$this->log_info($this->form_data, 'payment', $this->payment_type);
			} //@2nd param dubger start
			else if($save_payment_data !== false){
				$this->error = $this->lang('S_PROD_2', array($save_payment_data));
			}//@2nd param dubuger end
			if($new_link === 'return'){
				//return paypal gateway with user specified informations
				foreach($this->form_data as $name => $value){
					if($value)
						if(is_array($value)){
							foreach($value as $count => $new_attr){
								$count += 1;
								foreach($new_attr as $key_name => $_new_attr){
									if($key_name == 'tax_rate_')//remove '%' char in tax_rate value
										$_new_attr = str_replace('%', '', $_new_attr);
									$this->pp_url .= $key_name.$count.'='.$_new_attr.'&';
								}
							}
						}
						else
							$this->pp_url .= $name.'='.$value.htmlspecialchars('&');
				}
				
				$this->success = trim($this->pp_url, htmlspecialchars('&'));
			}
			else if($new_link === 'redirect'){
				//redirects paypal gateway with user specified informations
				foreach($this->form_data as $name => $value){
					if($value)
						if(is_array($value)){
							foreach($value as $count => $new_attr){
								$count += 1;
								foreach($new_attr as $key_name => $_new_attr){
									if($key_name == 'tax_rate_')//remove '%' char in tax_rate value
										$_new_attr = str_replace('%', '', $_new_attr);
									$this->pp_url .= $key_name.$count.'='.$_new_attr.'&';
								}
							}
						}
						else
							$this->pp_url .= $name.'='.$value.htmlspecialchars('&');
				}
				header('location: '.trim($this->pp_url, '&'));
				die();
			}
			else if(is_array($new_link)){
				//make a link useing set attributes were last param (bool: true) if for target='_blank'
				foreach($this->form_data as $name => $value){
					if($value)
						if(is_array($value)){
							foreach($value as $count => $new_attr){
								$count += 1;
								foreach($new_attr as $key_name => $_new_attr){
									if($key_name == 'tax_rate_')//remove '%' char in tax_rate value
										$_new_attr = str_replace('%', '', $_new_attr);
									$this->pp_url .= $key_name.$count.'='.$_new_attr.'&';
								}
							}
						}
						else
							$this->pp_url .= $name.'='.$value.htmlspecialchars('&');
				}
				foreach($new_link as $attr => $value){
					if($attr != 'link')
						$link .= $attr.'="'.$value.'" ';
				}
				$this->success = '<a href="'.trim($this->pp_url, htmlspecialchars('&')).'" '.$link.'>'.$new_link['link'].'</a>';
				
			}
			//return nothing
			else if(!$new_link){
				$this->success = false;
			}//@3rd param dubuger start
			else
				$this->error = $this->lang('S_PROD_3', array($new_link));
			//@3rd param dubuger end
			
			//the below code logs process stats if new_link(@param 2 is null)
			if(!$this->error){
				if(!$this->success)
					if(!$save_user_data)
						$this->dev_logs[] = $this->lang('NON_SAVED');
					else 
						$this->dev_logs[] = $this->lang('SV_USR_SAVED');
			}
			//devs_logs hold backend errors that my occure at runtime
			//do what you like with it, am just gonna leave it that way(not logging it)
			//$this->dev_logs
		}
		
	}
	//@param 1 current user specified email **arg passed in save function
	//@param 2 current user name(first name and last name) **arg passed in save function
	//@param 3 current user specified amount **arg passed in save function
	//this is a basic php mail function, consider using advanced mailers. php mailer, might do the trick
	//http://phpmailer.worxware.com/
	protected static function mail_user($data, $payment_type)
	{
		//change payment type to capital letters to match language key
		$payment_type = strtoupper($payment_type);
		//default user mail require arg starts
		$email = $data['email'];
		$name = $data['name'];
		$item = $data['items'];
		$id = $data['payment_id'];
		$date = date('l jS \of F Y h:i:s A');
		//default user mail require arg ends 
		if($payment_type == 'SHOP'){
			$amount = $data['item_total'];
			$tax = $data['tax'];
			$ship = $data['ship'];
			$total = $data['total'];
			$discount = $data['discount'];							  
			$mail_message = self::lang($payment_type.'_MESG', array($id, $name, $item, SITE_NAME, $amount, $tax, $ship, $total, $discount, $date));
		}
		else if($payment_type == 'DONATE'){
			$mail_message = self::lang($payment_type.'_MESG', array($id, $name, $item, SITE_NAME, $data['ammount'], $date));
		}
		$subject = self::lang($payment_type.'_RECPT', array(SITE_NAME));
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: Sender Name ".$this->pp_email;
		$headers[] = "Reply-To: Recipient Name ".$this->pp_email;
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		return mail($email, $subject, $mail_message, implode("\r\n", $headers));
	}
	//@param 1 value to validate
	//@param 2 type expected type
	//@param 3 constant name of value (this work with language, you have to change this if language is not EN)
	//@param 4 allow null in value(not expecting user to specify the value eg: address 2 or phone 2)
	protected function validate_input($value, $type, $name, $can_take_null_val = null)
	{
		foreach(explode('|', trim($type)) as $validation_type){
			//validation for required characters 
			if($validation_type == 'req' && trim($value) == false ){
				if($can_take_null_val)
					break;
				$this->error_handler($this->lang($name.'_EMPTY'));
			}
			//validation for characters(A-Z) only 
			else if($validation_type == 'char' && !ctype_alpha($value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			//validation for characters(A-Z and 0-9) only 
			else if($validation_type == 'charnum' && !preg_match('/[\a-z0-9 ]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			//validation for characters(0-9) only 
			else if($validation_type == 'num' && !is_numeric($value) && !preg_match('/[\0-9\+\(\)]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			//validation for email address only 
			else if($validation_type == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			//validation for maximun and minimun lenght of characters required
			else if(preg_match("/minmax\((.*)\)/", $validation_type, $output_array)){
				$minmax = explode(',', trim($output_array[1]));
				if(strlen($value) < $minmax[0] || strlen($value) > $minmax[1])
					$this->error_handler($this->lang($name.'_COUNT', array($minmax[0], $minmax[1], $value)));
			}
			if($this->error){
				return null;
				break;
			}
		}
		return $value;
	}
	//works with 4th param of payment (process how 'function validate_input' should return errors)
	protected function error_handler($new_error)
	{
		if($this->error_output == 'array')
			$this->error[] = $new_error;
		else if($this->error_output == 'string')
			$this->error = $new_error;
		else 
			return;
			
	}
	//@param 1 language key name 
	//@param 2 language replacement eg (replaces:  (%m1%, %m2%) with (value1, value2) in language files
	protected static function lang($key, $markers = NULL)
	{
		global $lang;
		if($markers == NULL){
			$str = $lang[$key];
		}
		else{
			//Replace any dyamic markers
			$str = $lang[$key];
			$iteration = 1;
			foreach($markers as $marker){
				$str = str_replace("%m".$iteration."%",$marker,$str);
				$iteration++;
			}
		}
		//Ensure we have something to return
		return ($str == "")? "No language key found" : $str;
	}
	//generate random uniq id
	protected function uniq_id()
	{
		global $mysqli;
		$time = time();
		$this->payment_id = str_shuffle('ABCD'.mt_rand());
		$stmt = $mysqli->prepare("SELECT id FROM payment_status WHERE payment_id = '".$this->payment_id."' LIMIT 1");
		if(!$stmt->execute())
			$this->dev_logs[] = $this->lang("UNIQ_GEN");
		$stmt->bind_result($id_exits);
		$stmt->fetch();
		//recurse if rendered id has already been used
		return ($id_exits)? $this->uniq_id() : $this->payment_id;
	}
	//general requirement for all forms
	protected function form_req($currency,
					$first_name = null, $last_name = null, $email_address = null,
					$address1 = null, $address2 = null, $city = null, $state = null, $zip_code = null, $country = null,
					$night_phone_a = null, $night_phone_b = null, $day_phone_a = null, $day_phone_b = null)
	{
			//users input validation
			//req = required(works with last @param: if field is optional, last param must be true)
			//char = A to Z only
			//num = 0 -9 only
			//charnum = A to Z and 0 to 9 only
			//minmax = minmax(minmun length | maxmum length)
			$this->form_data['currency_code'] 	= $this->validate_input($currency, 'req|char|minmax(3,3)', 'CU');
			$this->form_data['first_name'] 		= $this->validate_input($first_name, 'req|charnum|minmax(2,20)', 'FN');
			$this->form_data['last_name'] 		= $this->validate_input($last_name, 'req|charnum|minmax(2,20)', 'LN');
			$this->form_data['address1'] 		= $this->validate_input($address1, 'req|charnum|minmax(2,100)', 'A1');
			$this->form_data['address2'] 		= $this->validate_input($address2, 'req|charnum|minmax(2,100)', 'A2', true);
			$this->form_data['city'] 			= $this->validate_input($city, 'req|charnum|minmax(2,40)', 'CT');
			$this->form_data['state'] 			= $this->validate_input($state, 'req|char|minmax(2,2)', 'ST');
			$this->form_data['lc'] 				= $this->validate_input($country, 'req|char|minmax(2,2)', 'CY');
			$this->form_data['email'] 			= $this->validate_input($email_address, 'req|email|minmax(4,127)', 'EM');
			$this->form_data['zip'] 			= $this->validate_input($zip_code, 'req|charnum|minmax(1,32)', 'ZP');
			$this->form_data['night_phone_a'] 	= $this->validate_input($night_phone_a, 'req|num|minmax(3,3)', 'P1', true);
			$this->form_data['night_phone_b'] 	= $this->validate_input($night_phone_b, 'req|num|minmax(8,15)', 'P2', true);
			$this->form_data['day_phone_a'] 	= $this->validate_input($day_phone_a, 'req|num|minmax(3,3)', 'P3', true);
			$this->form_data['day_phone_b'] 	= $this->validate_input($day_phone_b, 'req|num|minmax(8,15)', 'P4', true);	
	}
	//return users data with payment id and update status
	//@param 1 payment id
	//@param 2 payment status update change from pending to (completed or canceled)
	public static function update($payment_id, $type, $payment_type, $email_user = null)
	{
		global $mysqli;
		$new_error = $data = array();
		//calculate tax to sum up payment
		$tax_up = function ($value, $ammount)
		{
			if(substr($value, -1) == '%') //if tax is percentage
				return number_format(round($ammount * $value/ 100, 2), 2);
			else //if tax is a flat ammount
				return number_format(round($value, 2), 2);
		};
		$discount = function ($amount = null, $rate = null, $value)
		{
			//'DA' discount ammount
			//'DV' discount value
			if($amount){//if discount is a flat ammount
				return array('DV' => $amount, 'DA' => ($value - $amount));
			}
			else if($rate){ //if discount is a flat percentage
				return  array('DV' => ($rate / 100), 'DA' => ($rate / 100) * $value);
			}
			else{
				return array('DV' => 0, 'DA' => $value);
			}
		};
		//fetch data where status is 'pending'
		$stmt = $mysqli->prepare("SELECT ".$payment_type.".*, users_data.*, payment_status.status
								 FROM ".$payment_type." JOIN payment_status, users_data
								 WHERE ".$payment_type.".payment_id = '$payment_id' 
								 AND users_data.payment_id = '$payment_id'
								 AND payment_status.status = 'pending'
								 AND payment_status.payment_id = '$payment_id'");
		if(!$stmt->execute())
			$new_error[] = self::lang("FETCH_P");
		$res = $stmt->get_result();
		for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
			$res->data_seek($row_no);
			$data[] = $res->fetch_assoc();
		}
		$res->close();
		if(!$data){
			return	false;
		}
		else{
			$stmt = $mysqli->prepare("UPDATE payment_status SET status = '$type' WHERE payment_id = '$payment_id' AND status = 'pending' LIMIT 1");
			if(!$stmt->execute())
				$new_error[] = self::lang("FETCH_U");
			else
				if($type == 'canceled'){
					return self::lang("CANCELD_P");
				}
				else{
					//default user mail require arg
					$mail_arg = array('email' 		=> $data[0]['email'],
									  'name' 		=> $data[0]['first_name'].' '.$data[0]['last_name'],
									  'payment_id'  => $data[0]['payment_id']);
					if($payment_type == 'shop')
					{
						$new_name = $new_amount = $new_tax = $new_ship = $new_discount = $new_total = '';
						for($i = 0; $i < count($data); $i++){
							$new_name .= $data[$i]['name'].', ';
							@$amount 	 = number_format(round($data[$i]['amount'], 2), 2);//get ammount
							@$tax 	 	 = number_format(round($tax_up($data[$i]['tax'], $amount), 2), 2); //get tax
							$new_ship 	+= number_format(round($data[$i]['shipping_charge'], 2), 2); //shipment fee
							//get discount value and amount
							$discnt = $discount($data[$i]['discount_amount'], $data[$i]['discount_rate'], ($amount * $data[$i]['quantity']));
							//multiply ammount with quantity minus discount
							$new_amount   += number_format(round($discnt['DA'], 2), 2);
							$new_discount += number_format(round($discnt['DV'], 2), 2);
							$new_tax 	  += ($tax * $data[$i]['quantity']); //multiply tax with quantity
						}
						$new_tax 	= $new_tax; //add up all taxes
						$new_amount = $new_amount; //add up all ammount minus dicount
						$new_total  = $new_amount + $new_tax + $new_ship;
						$mail_arg_con = array('items'		=> chop($new_name, ','),
											  'item_total'  => $new_amount.' '.$data[0]['currency_code'],
											  'tax'			=> $new_tax.' '.$data[0]['currency_code'],
											  'ship'		=> $new_ship.' '.$data[0]['currency_code'],
											  'discount'	=> $new_discount.' '.$data[0]['currency_code'],
											  'total'		=> $new_total.' '.$data[0]['currency_code'],);
						//adding $mail_arg_con to default mail arg
						$mail_arg = array_merge($mail_arg, $mail_arg_con);
					}
					else if($payment_type == 'donate')
					{
						//adding $ammount to default mail arg
						$mail_arg = array_merge($mail_arg, array('ammount'  => $data[0]['amount'].' '.$data[0]['currency_code'], 'items' => $data[0]['name']));
					}
					if($email_user === true && !self::mail_user($mail_arg, $payment_type)){
						$new_error[] = self::lang("EMAIL_F", $data['email'], $data['payment_id']);
					}
					else{
						$new_error[] = self::lang("NO_ERROR");
						self::$dev_logs = $new_error;
						if($email_user === true && $data)
							return self::lang("EMAIL_USER");
						else if(!$email_user && $data)
							return self::lang("EMAIL_USERN");
					}
					return false;
				}
		}
	}
}
class donate extends payment_solution
{
	//@param 1 assigns your paypal email address(not user***) to an object
	//@param 2 assigns your product name eg(homeless funding ** for donation) to an object
	//@param 3 assigns how errors should be returned (array, string or null|empty) to an object
	public function __construct($email, $return, $cancel, $product_name, $return_type = null, $payment_type)
	{
		$this->pp_email = $email;
		$this->product_name = $product_name;
		$this->error_output = $return_type;
		$this->payment_type = $payment_type;
		$this->pp_cancel_url = $cancel;
		$this->pp_return_url = $return;
		parent::__construct();
	}
   /*	
	   @param 1 can be 
		array: of all user specified data (if this is the case param 2 - 15 is not needed they would be passed as array in param 1 anyways)
		OR 
		string: currency code eg("USD", "EUR", "GBP", "CAD", "JPY) (2 - 15 is needed)
	*/
	public function info($currency, $ammount = null, 
						$first_name = null, $last_name = null, $email_address = null,
						$address1 = null, $address2 = null, $city = null, $state = null, $zip_code = null, $country = null,
						$night_phone_a = null, $night_phone_b = null, $day_phone_a = null, $day_phone_b = null)
	{
		//allowing format like ('+', '-', ' ') from user input and ofcos striping them out
		$night_phone_a  =  str_replace('+', '', $night_phone_a);
		$night_phone_b  =  str_replace(array('-', ' '), '', $night_phone_b);
		$day_phone_a  	=  str_replace('+', '', $day_phone_a);
		$day_phone_b  	=  str_replace(array('-', ' '), '', $day_phone_b);
		if(!$this->error){
			$this->form_data['cmd'] 			= '_xclick';
			$this->form_data['business'] 		= $this->pp_email;
			$this->form_data['item_name'] 		= $this->product_name;
			
			//user defined starts
			$this->form_data['amount'] 			= $this->validate_input($ammount, 'req|num|minmax(1, 50)', 'AM');
			$this->form_req($currency, $first_name, $last_name, $email_address, $address1, $address2, $city, 
							$state, $zip_code, $country,$night_phone_a, $night_phone_b, $day_phone_a, $day_phone_b);
			//user defined ends
			
			$payment_id = $this->uniq_id();
			$this->form_data['return'] 			= $this->pp_return_url.$payment_id;
			$this->form_data['cancel_return'] 	= $this->pp_cancel_url.$payment_id;
		}
		return $this->form_data;
	}	
}
class shop extends payment_solution
{
	public function __construct($email, $return, $cancel, $product_name, $return_type = null, $payment_type, $payment_action)
	{
		$this->pp_email = $email;
		$this->product_name = $product_name;
		$this->error_output = $return_type;
		$this->payment_type = $payment_type;
		$this->payment_action = $payment_action;
		$this->pp_cancel_url = $cancel;
		$this->pp_return_url = $return;
		parent::__construct();
	}
	public function info($currency,
					$first_name = null, $last_name = null, $email_address = null,
					$address1 = null, $address2 = null, $city = null, $state = null, $zip_code = null, $country = null,
					$night_phone_a = null, $night_phone_b = null, $day_phone_a = null, $day_phone_b = null)
	{
		//allowing format like ('+', '-', ' ') from user input and ofcos striping them out
		$night_phone_a  =  str_replace('+', '', $night_phone_a);
		$night_phone_b  =  str_replace(array('-', ' '), '', $night_phone_b);
		$day_phone_a  	=  str_replace('+', '', $day_phone_a);
		$day_phone_b  	=  str_replace(array('-', ' '), '', $day_phone_b);
		if(!$this->error){
			$this->form_data['cmd'] 			= '_cart';
			$this->form_data['upload'] 			= '1';
			$this->form_data['business'] 		= $this->pp_email;
			$this->form_data['item_name'] 		= $this->product_name;
			$this->form_data['paymentaction'] 	= $this->payment_action;
			
			//user input starts
			$this->form_req($currency, $first_name, $last_name, $email_address, $address1, $address2, $city, 
							$state, $zip_code, $country,$night_phone_a, $night_phone_b, $day_phone_a, $day_phone_b);
			//user defined ends	
			$payment_id = $this->uniq_id();
			$this->form_data['return'] 			= $this->pp_return_url.$payment_id;
			$this->form_data['cancel_return'] 	= $this->pp_cancel_url.$payment_id;
					
		}
		return $this->form_data;
	}	
}
//@param 1 payment processing type ('donate' avaliable at the moment) 
/*@param 2 can be
	Array: (if this is the case param 3 and 4 is not needed they would be passed as array in param 2 anyways)
	OR
	string: (3 and 4 is needed)
*/
//@param 2 your paypal email address(not user***)
//@param 3 your product name eg(homeless funding ** for donation) 
//@param 4 how errors should be returned (array, string or null|empty) 
function payment($payment_type, $email, $product_name, $cancel, $return, $return_type = null, $payment_action = null)
{
	//check if payment processore if avaliable
	if(class_exists($payment_type)){
		return (object) new $payment_type($email, $cancel, $return, $product_name, $return_type, $payment_type, $payment_action);
	}
	else{
		die("No Payment method called <code>--> $payment_type <--</code>, check ur spellings");
	}
}
//strip empty queries
function _new($name, $amt, $item_id, $quat = null, $tax = null, $no_ship = null, $ship = null, $disc_am = null, $disc_rat = null, $cn = null)
{
	$new = array('item_name_' => $name, 'amount_' => $amt);
	if($item_id)
		$new['item_number_'] = $item_id;
	if($quat)
		$new['quantity_'] = $quat;
	if($tax)
		if(substr($tax, -1) == '%')
			$new['tax_rate_'] = $tax;
		else
			$new['tax_'] = $tax;
	if($no_ship)
		$new['no_shipping_'] = $no_ship;
	if($ship)
		$new['shipping_'] = $ship;
	if($disc_am)
		$new['discount_amount_'] = $disc_am;
	else if($disc_rat)
		$new['discount_rate_'] = $disc_rat;
	if($cn)
		$new['cn_'] = $cn;
	return $new;
}

?>