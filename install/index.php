<?php
$post = false;
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
				$a[$m][1] = ' <code> invalid expected values (\'shop\' or \'donate\')</code>';
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
		require_once('sql.php');//make database and tables
	if( $err === 0 && $new_error == false)
	{
		$post = true;
		$dir = 'rendered_'.$data[0].'_payment_processor';
		if(is_dir($dir))
			@unlink($dir);
		@mkdir($dir);
		
		$folder = '';
		if(file_exists($dir.'/index.php'))
			unlink($dir.'/index.php');
		
		//make folders and subfolders according to url specs
		$fileamke = function($path, &$folder = null) use ($dir){
			if(strpos($path, '/') == true){
				$new_dir = $dir;
				$new_path = explode('/', $path);
				array_pop($new_path);
				foreach($new_path as $dir_name){
					$new_dir .= '/'.$dir_name;
					$folder .= '/../'; //for included files that is on a diffrent directory 
					if(!is_dir($new_dir))
						mkdir($new_dir);
				}
			}
			$folder = str_replace('//', '/', $folder);
			return true;
		};
		
		$inc_path = function($new_path, $path, $folder){
			//if the included file is not in same folder with the includer
			if(strstr($new_path, '/', true) != strstr($path, '/', true)){
				return $folder.$new_path;//set include path the to count of includer path eg(../, ../../)
			}
			else{
				//yes the included files are on same folder
				$new_path = explode('/', $new_path);//check if included will be in a subfolder
				array_shift($new_path);	//remove the name of the included folder and leave the subfolder
				return  implode('/', $new_path);	
			}
			
		};
		$data[2] = (trim($data[2]) == false)? $data[0]:$data[2];
		$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%'), array('<?php', '?>', $data[1], $data[2]), 
								base64_decode(file_get_contents($data[0].'.index.sh')));
		file_put_contents($dir.'/index.php',  $file_cont);//make index file
		//file_put_contents($dir.'/index.php',  base64_decode(file_get_contents($data[0].'.index.sh'))); dubuger: index.index
		if($fileamke($data[1], $folder)){
			//make function file
			$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%', '%m3%'), 
									array('<?php', '?>', $inc_path($data[4], $data[1], $folder), $inc_path($data[3], $data[1], $folder), $data[2]), 
									base64_decode(file_get_contents($data[0].'.funcs.sh')));
			file_put_contents($dir.'/'.$data[1],  $file_cont);
			//file_put_contents($dir.'/'.$data[1],  base64_decode(file_get_contents($data[0].'.funcs.sh'))); dubuger: funcs.php
		}
		if($fileamke($data[3])){
			//make language file
			$file_cont = str_replace(array('%m0%', '%m00%', '%m000%'),  array('<?php', '?>', $data[2]), 
								base64_decode(file_get_contents($data[0].'.lang.sh')));
			file_put_contents($dir.'/'.$data[3], $file_cont);
			//file_put_contents($dir.'/'.$data[3],  base64_decode(file_get_contents($data[0].'.lang.sh'))); dubuger: lang.php
		}
		if($fileamke($data[4], $folder)){
			//make connection and const file
			$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%', '%m3%', '%m4%', '%m5%', '%m6%', '%m7%'), 
									array('<?php', '?>', $data[11], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10]), 
									base64_decode(file_get_contents($data[0].'.conn.sh')));
			file_put_contents($dir.'/'.$data[4],  $file_cont);
			//file_put_contents($dir.'/'.$data[4],  base64_decode(file_get_contents($data[0].'.conn.sh'))); dubuger: conn.php
		}
		//make stats file
		$file_cont = str_replace(array('%m0%', '%m00%', '%m1%', '%m2%'),  array('<?php', '?>', $data[1], $data[2]), 
								base64_decode(file_get_contents($data[0].'.stats.sh')));
		file_put_contents($dir.'/stats.php',  $file_cont);
		//file_put_contents($dir.'/stats.php',  base64_decode(file_get_contents($data[0].'.stats.sh'))); dubuger: stats.php
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
<style>input{padding:10px; width: 400px;}.green{ border:1px solid green; }.red{border:1px solid red;}.cgreen{color:green;}.cred{color:red;}</style>
<?php if($post){
 if(@$new_error){ ?> 
		<code class="cred"><?php echo $new_error; ?></code> <?php }else{ ?> <code class="cgreen">SUCCESS!! <?php echo @$_POST['PT']; ?> payment processor installed. (installed folder: <?php echo dirname(__FILE__).DIRECTORY_SEPARATOR.@$_POST['PT']; ?>_payment_processor)</code> 
<?php } } ?>
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
      <td>working url <code>this is the were paypal redirects user at payment completion or cancelation</code></td>
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
      <td width="50%"><?php if($post){
	  if(!@$new_error){ ?><code class="cred">NOTE: </code> <code class="cgreen">make sure 'PP_CANCELED' and 'PP_SUCCESS' url in  <?php echo dirname(__FILE__).DIRECTORY_SEPARATOR.@$_POST['PT'].DIRECTORY_SEPARATOR.@$_POST['CN']; ?>" matches the location of stats.php in the <?php echo dirname(__FILE__).DIRECTORY_SEPARATOR.@$_POST['PT']; ?>_payment_processor folder. <code class="cred">this is the url paypal will redirect user at payment completion or cancelation</code></code><?php }  } ?></td>
      <td> <p /><input name="submit" value="submit" type="submit">    </tr>
  </tbody>
  </form>
</table>
</body>
</html>
