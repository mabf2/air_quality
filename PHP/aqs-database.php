<?php
/* Start Config */
  $servername = "localhost";
  $dbname     = "aqs_data";
  $username   = "aqs_user";
  $password   = "3spUs3r";

  $post_key_value = "rGSpY4jBk1vRz";
/* End Config */

  function insertReading($macaddress, $sensor, $feature, $value) {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `SensorData` (`id`, `macaddress`, `sensor`, `feature`, `value`, `reading_time`) VALUES (NULL, '" . $macaddress . "', '" . $sensor . "', '" . $feature . "', '" . $value . "', current_timestamp())";
    if ($conn->query($sql) === TRUE) {
      return "New record created successfully";
    }
    else {
      return "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
  }
  
  function getAllLocation() {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $date_today = date('Y-m-d');
    $sql = "SELECT macaddress, location, checked FROM SensorMAC WHERE active_since <= '" . $date_today . "' AND active_until >= '" . $date_today . "' ORDER BY location";
    if ($result = $conn->query($sql)) {
      return $result;
    }
    else {
      return false;
    }
    $conn->close();
  }

  function getAllReadings($location, $readings_date, $limit) {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $date_from = $readings_date . " 00:00:00";
    $date_to   = $readings_date . " 23:59:59";

    $sql  = "SELECT id, macaddress, value1, value2, value3, reading_time FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY reading_time DESC LIMIT " . $limit;
    if ($result = $conn->query($sql)) {
      return $result;
    }
    else {
      return false;
    }
    $conn->close();
  }

  function getLastReadings($location, $readings_date, $feature) {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $date_from = $readings_date . " 00:00:00";
    $date_to   = $readings_date . " 23:59:59";

    $sql  = "SELECT id, macaddress, sensor, feature, value, reading_time FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND feature = '" . $feature . "' AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY reading_time DESC LIMIT 1" ;
    if ($result = $conn->query($sql)) {
      return $result->fetch_assoc();
    }
    else {
      return false;
    }
    $conn->close();
  }

  function minReading($location, $readings_date, $feature) {
     global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $date_from = $readings_date . " 00:00:00";
    $date_to   = $readings_date . " 23:59:59";

    $sql  = "SELECT MIN(value) AS min_amount FROM (SELECT value FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND feature = '" . $feature . "' AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY reading_time DESC) AS min";
    if ($result = $conn->query($sql)) {
      return $result->fetch_assoc();
    }
    else { 
      return false;
    }
    $conn->close();
  }

  function maxReading($location, $readings_date, $feature) {
     global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $date_from = $readings_date . " 00:00:00";
    $date_to   = $readings_date . " 23:59:59";

    $sql  = "SELECT MAX(value) AS max_amount FROM (SELECT value FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND feature = '" . $feature . "' AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "'  ORDER BY reading_time DESC) AS max";
    if ($result = $conn->query($sql)) {
      return $result->fetch_assoc();
    }
    else {
      return false;
    }
    $conn->close();
  }

  function avgReading($location, $readings_date, $feature) {
     global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $date_from = $readings_date . " 00:00:00";
    $date_to   = $readings_date . " 23:59:59";

    $sql  = "SELECT AVG(value) AS avg_amount FROM (SELECT value FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND feature = '" . $feature . "' AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY reading_time DESC) AS avg";
    if ($result = $conn->query($sql)) {
      return $result->fetch_assoc();
    }
    else {
      return false;
    }
    $conn->close();
  }

  function avgTReading($location, $readings_date, $feature, $time) {
     global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $date_from = $readings_date . " " . $time . ":00:00";
    $date_to   = $readings_date . " " . $time . ":59:59";

    $sql  = "SELECT AVG(value) AS avg_amount FROM (SELECT value FROM SensorData WHERE macaddress = '" . $location . "'";
    $sql .= " AND feature = '" . $feature . "' AND reading_time BETWEEN '" . $date_from . "' AND '" . $date_to . "' ORDER BY reading_time DESC) AS avg";
    if ($result = $conn->query($sql)) {
      return $result->fetch_assoc();
    }
    else {
      return false;
    }
    $conn->close();
  }
?>
