<?php
  include_once('aqs-database.php');

  $post_key = $macaddress = $sensor = $feature = $value = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_key = test_input($_POST["post_key"]);
    
    if($post_key == $post_key_value) {
      $macaddress = test_input($_POST["macaddress"]);
      $sensor     = test_input($_POST["sensor"]);
      $feature    = test_input($_POST["feature"]);
      $value      = test_input($_POST["value"]);
      
      if($macaddress !== "" && $sensor !== "" && $feature !== "" && $value !== "") {
        $retorno = insertReading($macaddress, $sensor, $feature, $value);
        echo $retorno;
      }
    }
    else {
      echo "Wrong API Key provided.";
    }
  }
  else {
    echo "No data posted with HTTP POST.";
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>