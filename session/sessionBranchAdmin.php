<?php 
	date_default_timezone_set('Asia/Manila');

	session_start();
	if($_SESSION["type"] != "bAdmin"){
		$_SESSION = array();
    	session_destroy();
		header("Location: login.php");
	}
?>
	
    <input type="hidden" id="department" value="<?php echo $_SESSION["type"]  ?>" >