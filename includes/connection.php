<?php 

	$conn = new PDO("sqlsrv:Server=DESKTOP-MA33A92;Database=teller", "teller", "p@ssw0rD");

	if(!$conn)
		echo "ERROR";
	
	
?>	