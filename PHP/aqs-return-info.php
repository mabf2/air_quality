<?php
  include_once('aqs-database.php');
  
  $post_key = $data_type = $macaddress = "";

  if      ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_key   = test_input($_POST["post_key"]);
    $data_type  = test_input($_POST["data_type"]);
    $macaddress = test_input($_POST["macaddress"]);
	
  }elseif ($_SERVER["REQUEST_METHOD"] == "GET") { 
    $post_key   = test_input($_GET["post_key"]);
    $data_type  = test_input($_GET["data_type"]);
    $macaddress = test_input($_GET["macaddress"]);
	
  }

  if($post_key == $post_key_value) {
    if    ($data_type == "DT")
      echo date("d-m-Y H:i");
	  
    elseif($data_type == "D")
      echo date("d-m-Y");
	  
    elseif($data_type == "T")
      echo date("H:i:s");
	  
    elseif($data_type == "LOCATION") {
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

      $date_today = date('Y-m-d');
      $sql = "SELECT location FROM SensorMAC WHERE active_since <= '" . $date_today . "' AND macaddress = '" . $macaddress . "' ORDER BY active_since Limit 0,1";
      if ($result = $conn->query($sql)){
        while($row = mysqli_fetch_array($result)) {
          echo $row["location"];
        }
		
      }else 
        return false;
		
      $conn->close();
    }
  }else
    return false;

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>