<?php
$mysqli = new mysqli($data[5], $data[6], $data[7]);
if(mysqli_connect_errno()) {
	$new_error = "Connection Failed here: " . mysqli_connect_errno();
	exit();
}
$new_error = false;
$sql = "CREATE DATABASE IF NOT EXISTS $data[8]"; //make database
if($mysqli->query($sql)){
	$mysqli->close();
	$mysqli = new mysqli($data[5], $data[6], $data[7], $data[8]);
	if(mysqli_connect_errno()) {
		$new_error = "Connection Failed here: " . mysqli_connect_errno();
		exit();
	}
	if($data[0] == 'donate'){
		$sql = 'CREATE TABLE IF NOT EXISTS `donate` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `payment_id` varchar(15) NOT NULL,
				  `currency_code` varchar(3) NOT NULL,
				  `amount` varchar(50) NOT NULL,
				  `name` varchar(400) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `payment_id` (`payment_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		if(!$mysqli->query($sql))//make donate table
			$new_error = 'error: donate table wasnt creeated';
	}
	else if($data[0] == 'shop'){
		$sql = 'CREATE TABLE IF NOT EXISTS `shop` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `payment_id` varchar(15) NOT NULL,
				  `currency_code` varchar(3) NOT NULL,
				  `amount` float NOT NULL,
				  `name` varchar(400) NOT NULL,
				  `quantity` int(11) NOT NULL,
				  `tax` varchar(50) DEFAULT NULL,
				  `shipping` tinyint(1) DEFAULT NULL,
				  `shipping_charge` float DEFAULT NULL,
				  `discount_amount` float DEFAULT NULL,
				  `discount_rate` float DEFAULT NULL,
				  `cn` varchar(50) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		if(!$mysqli->query($sql))//make shop table
			$new_error = 'error: shop table wasnt creeated';
	}
	$sql = 'CREATE TABLE IF NOT EXISTS `users_data` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `payment_id` varchar(15) NOT NULL,
			  `first_name` varchar(32) NOT NULL,
			  `last_name` varchar(64) NOT NULL,
			  `address1` varchar(100) DEFAULT NULL,
			  `address2` varchar(100) DEFAULT NULL,
			  `city` varchar(40) NOT NULL,
			  `state` varchar(2) NOT NULL,
			  `lc` varchar(2) NOT NULL,
			  `email` varchar(127) NOT NULL,
			  `zip` varchar(32) NOT NULL,
			  `night_phone_a` int(3) DEFAULT NULL,
			  `night_phone_b` int(15) DEFAULT NULL,
			  `day_phone_a` int(3) DEFAULT NULL,
			  `day_phone_b` int(15) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `payment_id` (`payment_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
	if(!$mysqli->query($sql))//make users data table
		$new_error = 'error: users_data table wasnt creeated';
	
	$sql = 'CREATE TABLE IF NOT EXISTS `payment_status` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `payment_id` varchar(15) NOT NULL,
			  `status` enum(\'pending\',\'completed\',\'canceled\') NOT NULL,
			  `type` varchar(10) NOT NULL,
			  `date` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
	if(!$mysqli->query($sql))//make payment status table
		$new_error = 'error: payment_status table wasnt creeated';
}
else
	$new_error = 'error: failed to create database '.$data[8];
?>