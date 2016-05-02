<?php
if(isset($_POST['submit'])){
	$m = $err = 0;
	$data = $a = array();
	foreach($_POST as $values){
		if(trim($values) == false && !in_array($m, array(2, 6, 7))){
			$err = 1;
			$a[$m][0] = 'red';
			$a[$m][1] = ' <code> required </code>';
		}
		else{
			if($m == 0 && ($values != 'donate' && $values != 'shop')){
				$err = 1;
				$a[$m][0] = 'red';
				$a[$m][1] = ' <code> invalid valude expected values (\'shop\' or \'donate\')</code>';
			}
			else{
				$data[$m] = $values;
				$a[$m][0] = 'green';
				$a[$m][1] = ' <code> ok! </code>';
			}
		}
		$m++;
	}
	if($err === 0)
	{
		$dir = 'rendered_payment_processor_folder';
		if(is_dir($dir))
			@unlink($dir);
		@mkdir($dir);
		
		if(file_exists($dir.'/index.php'))
			unlink($dir.'/index.php');
		$fileamke= function($path) use ($dir){
			if(strpos($path, '/') == true){
				$new_dir = $dir;
				$new_path = explode('/', $path);
				array_pop($new_path);
				foreach($new_path as $dir_name){
					$new_dir .= '/'.$dir_name;
					if(!is_dir($new_dir))
						mkdir($new_dir);
				}
			}
			return true;
		};
		$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%'), array('<?php', '?>', $data[1], $data[10]), 
								base64_decode(file_get_contents($data[0].'.index.inc')));
		file_put_contents($dir.'/index.php',  $file_cont);//make index file
		if($fileamke($data[1])){
			//make function file
			$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%', '%m3%'), 
									array('<?php', '?>', $data[4], $data[3], $data[2]), 
									base64_decode(file_get_contents($data[0].'.funcs.inc')));
			file_put_contents($dir.'/'.$data[1],  $file_cont);
		}
		if($fileamke($data[4])){
			//make connection and const file
			$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%', '%m3%', '%m4%', '%m5%', '%m6%',), 
									array('<?php', '?>', $data[5], $data[6], $data[7], $data[8], $data[11], $data[9]), 
									base64_decode(file_get_contents($data[0].'.conn.inc')));
			file_put_contents($dir.'/'.$data[4],  $file_cont);
		}
		//make stats file
		$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%'),  array('<?php', '?>', $data[1], $data[2]), 
								base64_decode(file_get_contents($data[0].'.stats.inc')));
		file_put_contents($dir.'/stats.php',  $file_cont);
	}
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
<body>
<style>
input{padding:10px; width: 400px;}
.green{ border:1px solid green; }
.red{border:1px solid red;}
</style>
<table width="100%" border="0" style="font-family:'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif;">
<form method="post" action="index.php">
  <tbody>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td>payment type</td>
      <td><input name="PT" value="<?php echo @$_POST['PT']; ?>" placeholder="eg(shop or donate)" class="<?php echo @$a[0][0]; ?>"><?php echo @$a[0][1]; ?></td>
    </tr>
    <tr>
      <td>payment processor filename and path</td>
      <td><input name="PP" value="<?php echo @$_POST['PP']; ?>" placeholder="eg(include/function.php or function.php)" class="<?php echo @$a[1][0]; ?>"><?php echo @$a[1][1]; ?></td>
    </tr>
    <tr>
      <td>class name</td>
      <td><input name="CL" value="<?php echo @$_POST['CL']; ?>" placeholder="leave empty if not familiar with php classes and objects" class="<?php echo @$a[2][0]; ?>"><?php echo @$a[2][1]; ?></td>
    </tr>
     <tr>
      <td>language file name and path</td>
      <td><input name="LA" value="<?php echo @$_POST['LA']; ?>" placeholder="eg(language/EN.php or EN.php)" class="<?php echo @$a[3][0]; ?>"><?php echo @$a[3][1]; ?></td>
    </tr>
    <tr>
      <td>connection and constant file name and path</td>
      <td><input name="CN" value="<?php echo @$_POST['CN']; ?>" placeholder="eg(includes/conn.php or conn.php)" class="<?php echo @$a[4][0]; ?>"><?php echo @$a[4][1]; ?></td>
    </tr>
    <tr>
      <td>database host</td>
      <td><input name="DH" value="<?php echo @$_POST['DH']; ?>" placeholder="eg(localhost)" class="<?php echo @$a[5][0]; ?>"><?php echo @$a[5][1]; ?></td>
    </tr>
    <tr>
      <td>database username</td>
      <td><input name="DU" value="<?php echo @$_POST['DU']; ?>" placeholder="eg(root)" class="<?php echo @$a[6][0]; ?>"><?php echo @$a[6][1]; ?></td>
    </tr>
    <tr>
      <td>database password</td>
      <td><input name="DP" value="<?php echo @$_POST['DP']; ?>" placeholder="eg(root)" class="<?php echo @$a[7][0]; ?>"><?php echo @$a[7][1]; ?></td>
    </tr>
    <tr>
      <td>database tablename</td>
      <td><input name="DT" value="<?php echo @$_POST['DT']; ?>" placeholder="eg(payment_proccessor)" class="<?php echo @$a[8][0]; ?>"><?php echo @$a[8][1]; ?></td>
    </tr>
    <tr>
      <td>working url</td>
      <td><input name="WN" value="<?php echo @$_POST['WN']; ?>" placeholder="eg(https://ghostff.com/ or https://localhost/)" class="<?php echo @$a[9][0]; ?>"><?php echo @$a[9][1]; ?></td>
    </tr>
    <tr>
      <td>paypal personal or business email address</td>
      <td><input name="PE" value="<?php echo @$_POST['PE']; ?>" placeholder="eg(name@domain.com)" class="<?php echo @$a[10][0]; ?>"><?php echo @$a[10][1]; ?></td>
    </tr>
    <tr>
      <td>website name</td>
      <td><input name="WW" value="<?php echo @$_POST['WW']; ?>" placeholder="eg(ghostff, google, facebook)" class="<?php echo @$a[11][0]; ?>"><?php echo @$a[11][1]; ?></td>
    </tr>
    <tr>
      <td></td>
      <td> <p /><input name="submit" value="submit" type="submit">    </tr>
  </tbody>
  </form>
</table>
</body>
</html>
