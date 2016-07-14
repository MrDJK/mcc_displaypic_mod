<?php


function displayPic() {
	/* Magic Quotes = BAD!!! Correcting issue if it's enabled. */
	if ( get_magic_quotes_gpc () ) {
		if(!function_exists('mqCallback')) {
			function mqCallback(&$var) {
				if(get_magic_quotes_gpc()) 
					$var = stripslashes($var);
			}
			if(count($_GET)) {
				array_walk ($_GET, 'mqCallback');
			}
			if ( count ( $_POST ) ) {
				array_walk ($_POST, 'mqCallback');
			}
		}
	}
	/* END */

	global $db, $userid;
	$path = 'userdps/'; //Change the userdps to a random folder name :)
	if(!isset($_POST['upload'])) {
			echo '
			<form enctype="multipart/form-data" action="preferences.php?action=picchange" method="post">
				<label for="dp">Display Picture:</label>
				<input type="file" name="dp" id="dp" /><br />
				<input type="submit" value="upload" name="upload" />
			</form>
			';
	} else {
		try {
			if( !is_writeable($path) ) {
				throw new Exception('Error: Something is wrong with the upload system please contact an administrator');
			}
			if( !preg_match ( '/^image/', $_FILES['dp']['type'] ) ) {
				throw new Exception('Error: That is not an image!');
			}
			if( !preg_match ('/(png|jpe?g)$/i', $_FILES['dp']['name'] ) ) {
				throw new Exception('Error: We don\'t support this type of file extension!');
			}
			if( $_FILES['dp']['size'] > 200000 ) {
				throw new Exception('Error: Image is too big, 200kb max!');
			}
			
			$e = substr ( $_FILES['dp']['name'], strpos ( $_FILES['dp']['name'], '.' ), strlen ( $_FILES['dp']['name'] )-1 );
			$uniqueName = md5(uniqid(null,false));
			move_uploaded_file($_FILES['dp']['tmp_name'], $path . $uniqueName.$e);
			$id = uniqid(null,false);
			
			$check = $db->query("SELECT `id` FROM `displaypics` WHERE (`user` = ".$userid.")");
			if($db->num_rows($check) > 0) {
				$db->query("DELETE FROM `displaypics` WHERE (`user` = ".$userid.")");
			}
			
			$db->query("INSERT INTO `displaypics` VALUES ('".$id."', ".$userid.", ".$_FILES['dp']['type']."', '".$uniqueName.$e."')");
			echo '<span class="success">Success; Your Display picture has been changed</span>';
			
		} catch (Exception $e) {
			echo '<span class="error">'.$e->getMessage().'</span>';
			exit;
		}
	}
}
	
?>
