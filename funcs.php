<?php
const DEFAULT_LANG = "EN";
require_once(dirname(__FILE__).'/includes/'.DEFAULT_LANG.'.php');
class payment_solution
{
	public $error = null;
	public $success = false;
	protected $product_name = null;
	protected $pp_email = null;
	protected $error_output;
	protected $pp_url = 'https://www.paypal.com/cgi-bin/webscr?';
	protected $form_data = array();
	public $info;
	public function __construct()
	{
		if($this->error_output != 'array' && $this->error_output != 'string')
			$this->error = $this->lang("INVL_ER_PRO_3", array(3));
		else if(trim($this->product_name) == false)
			$this->error = $this->lang("INVL_ER_PRO_2", array(2));
	}
	public function save($save_user_data, $email_receipt = null, $new_link = null)
	{
		if(!$this->error){
			if($save_user_data === true){
				$file = 'data.log';
				@$current = file_get_contents($file);
				$receipt = (!$email_receipt)?"NO":"YES";
				$current .= "
					+----------------------------------------------------
					|	      ---------[".$this->form_data['first_name']." ".$this->form_data['last_name']."]----------
					+----------------------------------------------------
					|NAME:		".$this->form_data['item_name']."
					|FNAME:		".$this->form_data['first_name']."
					|LNAME:		".$this->form_data['last_name']."
					|CURENCY:	".$this->form_data['currency_code']."
					|AMMOUNT:	".$this->form_data['amount']."
					|EMAIL:		".$this->form_data['email']."
					|ADDRESS1:	".$this->form_data['address1']."
					|ADDRESS2:	".$this->form_data['address2']."
					|CITY:		".$this->form_data['city'] ."
					|STATE:		".$this->form_data['state']."
					|ZIP:		".$this->form_data['zip']."
					|COUNTRY:	".$this->form_data['lc']."
					|PHOE1:		".$this->form_data['night_phone_a']."
					|PHONE2:	".$this->form_data['night_phone_b']	."
					|PHONE3:	".$this->form_data['night_phone_c']."
					|RECEIPT:	".$receipt."
					|STATUS:	PENDING
					+-----------------------------------------------------	
				";
				@file_put_contents($file, $current);
				if(!filesize($file))
					$this->error = $this->lang('DATA_NT_SVD');
			}
			else if($save_user_data !== false){
				$this->error = $this->lang('INVL_ARG_RTN_1', array(1));
			}
			if($email_receipt === true){
				$name = $this->form_data['first_name']." ".$this->form_data['last_name'];
				$current = $this->form_data['amount'].' '.strtoupper($this->form_data['currency_code']);
				if(!$this->mail_user($this->form_data['email'], $name, $current))
					$this->error = $this->lang('MAIL_FLD');//email failed to send
			}
			else if($email_receipt !== false){
				$this->error = $this->lang('INVL_ARG_RTN_2', array(2));
			}
			if($new_link === 'return'){
				foreach($this->form_data as $name => $value){
					if($value)
						$this->pp_url .= $name.'='.$value.htmlspecialchars('&');
				}
				$this->success = trim($this->pp_url, htmlspecialchars('&'));
			}
			else if($new_link === 'redirect'){
				foreach($this->form_data as $name => $value){
					if($value)
						$this->pp_url .= $name.'='.$value.'&';
				}
				header('location: '.trim($this->pp_url, '&'));
				exit;
			}
			else if(!$new_link){
				$this->success = false;
			}
			else
				$this->error = $this->lang('INVL_ARG_RTN_3', array(3));
				
			if(!$this->error){
				if(!$this->success)
					if(!$save_user_data	&& !$email_receipt)
						$this->success = $this->lang('NON_SAVED');
					else if($save_user_data && !$email_receipt)
						$this->success = $this->lang('SV_USR_SAVED');
					else if(!$save_user_data && $email_receipt)
						$this->success = $this->lang('EML_REC_SAVED', array($this->form_data['email']));
					else
						$this->success = $this->lang('ALL_SAVED', array($this->form_data['email']));
			}
		}
		
	}
	protected function mail_user($email, $name, $amount)
	{
		$subject = 'Receipt for your Donation at mysitname';
		$mail_message = "Hello $name,".PHP_EOL."
		Thanks again for your donation on ".date('l jS \of F Y h:i:s A').".
		The merchant has completed your transaction. Please see your transaction details and final purchase price below.
		Payment method	Amount ".PHP_EOL."
		Donation		$amount";
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: Sender Name ".$this->pp_email;
		$headers[] = "Reply-To: Recipient Name ".$this->pp_email;
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		return mail($email, $subject, $mail_message, implode("\r\n", $headers));
	}
	protected function validate_input($value, $type, $name, $can_take_null_val = null)
	{
		foreach(explode('|', trim($type)) as $validation_type){
			if($validation_type == 'req' && trim($value) == false ){
				if($can_take_null_val)
					break;
				$this->error_handler($this->lang($name.'_EMPTY'));
			}
			else if($validation_type == 'char' && !ctype_alpha($value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			else if($validation_type == 'charnum' && !preg_match('/[\a-z0-9 ]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			else if($validation_type == 'num' && !is_numeric($value) && !preg_match('/[\0-9\+\(\)]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
			else if($validation_type == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)){
				$this->error_handler($this->lang($name.'_INVALID', array($value)));
			}
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
	protected function error_handler($new_error)
	{
		if($this->error_output == 'array')
			$this->error[] = $new_error;
		else if($this->error_output == 'string')
			$this->error = $new_error;
		else
			return;
			
	}
	protected function lang($key,$markers = NULL)
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
}
class donate extends payment_solution
{
	public function __construct($email, $product_name, $return_type = null){
		$this->pp_email = $email;
		$this->product_name = $product_name;
		$this->error_output = $return_type;
		parent::__construct();
	}
	public function info($currency, $ammount = null, 
						$first_name = null, $last_name = null, $email_address = null,
						$address1 = null, $address2 = null, $city = null, $state = null, $zip_code = null, $country = null,
						$night_phone_a = null, $night_phone_b = null, $night_phone_c = null)
	{
		if(is_array($currency)){
			$ammount 		= $currency[1];
			$first_name 	= $currency[2];
			$last_name 		= $currency[3];
			$email_address  = $currency[4];
			$address1 		= $currency[5];
			$address2 		= $currency[6];
			$city 			= $currency[7];
			$state 			= $currency[8];
			$zip_code 		= $currency[9];
			$country 		= $currency[10];
			$night_phone_a  = $currency[11];
			$night_phone_b  = $currency[12];
			$night_phone_c  = $currency[13];
			$currency 		= $currency[0];
		}
		if(!$this->error){
			$this->form_data['cmd'] 			= '_xclick';
			$this->form_data['business'] 		= $this->pp_email;
			$this->form_data['item_name'] 		= $this->product_name;
			//user input starts
			$this->form_data['currency_code'] 	= $this->validate_input($currency, 'req|char|minmax(3,3)', 'CU');
			$this->form_data['amount'] 			= $this->validate_input($ammount, 'req|num|minmax(1, 50)', 'AM');
			$this->form_data['first_name'] 		= $this->validate_input($first_name, 'req|charnum|minmax(2,20)', 'FN');
			$this->form_data['last_name'] 		= $this->validate_input($last_name, 'req|charnum|minmax(2,20)', 'LN');
			$this->form_data['address1'] 		= $this->validate_input($address1, 'req|charnum|minmax(2,100)', 'A1');
			$this->form_data['address2'] 		= $this->validate_input($address2, 'req|charnum|minmax(2,100)', 'A2', true);
			$this->form_data['city'] 			= $this->validate_input($city, 'req|charnum|minmax(2,40)', 'CT');
			$this->form_data['state'] 			= $this->validate_input($state, 'req|char|minmax(2,2)', 'ST');
			$this->form_data['lc'] 				= $this->validate_input($country, 'req|char|minmax(2,2)', 'CY');
			$this->form_data['email'] 			= $this->validate_input($email_address, 'req|email|minmax(4,127)', 'EM');
			$this->form_data['zip'] 			= $this->validate_input($zip_code, 'req|num|minmax(1,32)', 'ZP');
			$this->form_data['night_phone_a'] 	= $this->validate_input($night_phone_a, 'req|num|minmax(9,16)', 'P1', true);
			$this->form_data['night_phone_b'] 	= $this->validate_input($night_phone_b, 'req|char|minmax(3,3)', 'P2', true);
			$this->form_data['night_phone_c'] 	= $this->validate_input($night_phone_c, 'req|char|minmax(4,4)', 'P3', true);	
		}
		return $this->form_data;
	}	
}
function payment($payment_type, $email, $product_name = null, $return_type = null)
{
	if(class_exists($payment_type)){
		if(is_array($email)){
			$product_name = $email[1];
			$return_type = $email[2];
			$email = $email[0];	
		}
		return (object) new $payment_type($email, $product_name, $return_type);
	}
	else{
		echo "No Payment method called <code>--> $payment_type <--</code>, check ur spellings";
	}
}

?>