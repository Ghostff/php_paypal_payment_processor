<?php
const DEFAULT_LANG = "EN";
require_once(dirname(__FILE__).'/includes/'.DEFAULT_LANG.'.php');
class payment
{
	public $error = null;
	public $success = false;
	private $pp_cmd = null;
	private $pp_email = null;
	private $error_output;
	private $form_data = array();
	public function __construct($action, $email, $error_rtn_type)
	{
		if(!is_callable(array($this, $action))){
			//spelt processor type wrong
			$this->error = $this->lang("INVALID_PROCS", array($action));
		}
		else{
			if($error_rtn_type != 'array' && $error_rtn_type != 'string')
				$this->error = $this->lang("INVL_ER_PRO");
			else
				$this->error_output = $error_rtn_type;
			$this->pp_email = $email;
			$this->pp_cmd = call_user_func(array($this, $action));
		}
		
	}
	public function info($currency, $ammount, 
						$first_name, $last_name, $email_address,
						$address1, $address2 = null, $city, $state, $zip_code, $country,
						$night_phone_a = null, $night_phone_b = null, $night_phone_c = null)
	{
		if(!$this->error){
			//more docs https://www.paypal.com/cgi-bin/webscr?cmd=_pdn_xclick_prepopulate_outside
			$this->form_data['currency'] 		= $this->validate_input($currency, 'req|char|minmax(3,3)', 'CU');
			$this->form_data['ammount'] 		= $this->validate_input($ammount, 'req|num|minmax(1, 50)', 'AM');
			$this->form_data['first_name'] 		= $this->validate_input($first_name, 'req|charnum|minmax(2,20)', 'FN');
			$this->form_data['last_name'] 		= $this->validate_input($last_name, 'req|charnum|minmax(2,20)', 'LN');
			$this->form_data['email_address'] 	= $this->validate_input($email_address, 'req|email|minmax(4,127)', 'EM');
			$this->form_data['address1'] 		= $this->validate_input($address1, 'req|charnum|minmax(2,100)', 'A1');
			$this->form_data['address2'] 		= $this->validate_input($address2, 'req|charnum|minmax(2,100)', 'A2', true);
			$this->form_data['city'] 			= $this->validate_input($city, 'req|charnum|minmax(2,100)', 'CT');
			$this->form_data['state'] 			= $this->validate_input($state, 'req|char|minmax(2,2)', 'ST');
			$this->form_data['country'] 		= $this->validate_input(strip_tags($country), 'req', 'CY');
			$this->form_data['zip_code'] 		= $this->validate_input($zip_code, 'req|num|minmax(1,32)', 'ZP');
			$this->form_data['night_phone_a'] 	= $this->validate_input($night_phone_a, 'req|num|minmax(9,16)', 'P1', true);
			$this->form_data['night_phone_b'] 	= $this->validate_input($night_phone_b, 'req|char|minmax(3,3)', 'P2', true);
			$this->form_data['night_phone_c'] 	= $this->validate_input($night_phone_c, 'req|char|minmax(4,4)', 'P3', true);	
		}
		return $this->form_data;


	}
	public function save($save_user_data, $email_receipt = null)
	{
		if($save_user_data == true){
			$file = 'data.log';
			@$current = file_get_contents($file);
			$receipt = (!$email_receipt)?"NO":"YES";
			$current .= "
				+----------------------------------------------------
				|	      ---------[".$this->form_data['first_name']." ".$this->form_data['last_name']."]----------
				+----------------------------------------------------
				|FNAME:		".$this->form_data['first_name']."
				|LNAME:		".$this->form_data['last_name']."
				|CURENCY:	".$this->form_data['currency']."
				|AMMOUNT:	".$this->form_data['ammount']."
				|EMAIL:		".$this->form_data['email_address']."
				|ADDRESS1:	".$this->form_data['address1']."
				|ADDRESS2:	".$this->form_data['address2']."
				|CITY:		".$this->form_data['city'] ."
				|STATE:		".$this->form_data['state']."
				|ZIP:		".$this->form_data['zip_code']."
				|COUNTRY:	".$this->form_data['country']."
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
		$name = $this->form_data['first_name']." ".$this->form_data['last_name'];
		$current = $this->form_data['ammount'].' '.strtoupper($this->form_data['currency']);
		if($email_receipt == true && !$this->mail_user($this->form_data['email_address'], $name, $current))
			$this->error = $this->lang('MAIL_FLD');
			
		if(!$this->error){
			if(!$save_user_data	&& !$email_receipt)
				$this->success = $this->lang('NON_SAVED');
			else if($save_user_data && !$email_receipt)
				$this->success = $this->lang('SV_USR_SAVED');
			else if(!$save_user_data && $email_receipt)
				$this->success = $this->lang('EML_REC_SAVED', array($this->form_data['email_address']));
			else
				$this->success = $this->lang('ALL_SAVED', array($this->form_data['email_address']));
				
			
		}
		
	}
	private function mail_user($email, $name, $amount)
	{
		$subject = 'Receipt for your Donation at mysitname';
		$mail_message = "Hello $name,".PHP_EOL."
		Thanks again for your donation ".date('l jS \of F Y h:i:s A').".
		The merchant has completed your transaction. Please see your transaction details and final purchase price below.
		Payment method	Amount ".PHP_EOL."
		Donation		$amount";
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: Sender Name ".$this->pp_email;
		$headers[] = "Reply-To: Recipient Name <receiver@domain3.com>";
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		return mail($email, $subject, $$mail_message, implode("\r\n", $headers));
	}
	private function validate_input($value, $type, $name, $can_take_null_val = null)
	{
		foreach(explode('|', trim($type)) as $validation_type){
			if($validation_type == 'req' && trim($value) == false ){
				if($can_take_null_val)
					break;
				$this->error_handler($this->lang($name.'_EMPTY'));
			}
			else if($validation_type == 'char' && !ctype_alpha($value)){
				$this->error_handler($this->lang($name.'_INVALID'));
			}
			else if($validation_type == 'charnum' && !preg_match('/[\a-z0-9 ]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID'));
			}
			else if($validation_type == 'num' && !is_numeric($value) && !preg_match('/[\0-9\+\(\)]+/', $value)){
				$this->error_handler($this->lang($name.'_INVALID'));
			}
			else if($validation_type == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)){
				$this->error_handler($this->lang($name.'_INVALID'));
			}
			else if(preg_match("/minmax\((.*)\)/", $validation_type, $output_array)){
				$minmax = explode(',', trim($output_array[1]));
				if(strlen($value) < $minmax[0] || strlen($value) > $minmax[1])
					$this->error_handler($this->lang($name.'_COUNT', array($minmax[0], $minmax[1])));
			}
			if($this->error){
				return null;
				break;
			}
			return $value;
		}
		
	}
	private function error_handler($new_error)
	{
		if($this->error_output == 'array')
			$this->error[] = $new_error;
		else if($this->error_output == 'string')
			$this->error = $new_error;
		else
			return;
			
	}
	private function lang($key,$markers = NULL)
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
	private function donate()
	{
		return '_donations';
	}
	private function buy_now()
	{
		return '_xclick';
	}
	private function add_to_cart()
	{
		return '_cart';
	}
	private function gift()
	{
		return '_oe-gift-certificate';
	}
	private function subscription()
	{
		return '_xclick-subscriptions';
	}
	private function billing()
	{
		return '_xclick-auto-billing';
	}
	private function payment_plan()
	{
		return '_xclick-payment-plan';
	}
}
