<?php
class Patient
{
  private $conn;
  private $userId;

  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->userId = $_SESSION['user_id'];
  }

  public function setUserId()
  {
    $stmt = $this->conn->prepare("INSERT INTO patients(user_id) VALUES(?) ");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
  }

  public function checkID($userID)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
  }


  public function getLocation()
  {
    $stmt = $this->conn->prepare("SELECT location FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['location'];
  }

  // Setter for patient location
  public function setLocation($location)
  {
    $stmt = $this->conn->prepare("UPDATE patients SET location = ? WHERE user_id = ?");
    $stmt->bind_param("si", $location, $this->userId);
    $stmt->execute();
  }
  public function setLMP($lmpDate)
  {
    // Prepare the query
    $query = "
          UPDATE patients
          SET LMP = ?
          WHERE user_id = ?
      ";

    // Prepare the statement
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $lmpDate, $this->userId);

    // Execute the query
    $stmt->execute();
  }
  public function updatePatientEDD()
  {
    // Prepare the queries
    $query2 = "UPDATE patients
               SET EDD = CASE
                   WHEN previous_pregnancies = 0 THEN LMP + INTERVAL 12 MONTH - INTERVAL 2 MONTH - INTERVAL 14 DAY
                   ELSE LMP + INTERVAL 12 MONTH - INTERVAL 2 MONTH - INTERVAL 18 DAY
               END
               WHERE user_id = $this->userId";


    // Execute the queries
    $this->conn->query($query2);
  }
  public function updateGestationAge()
  {

    $query3 = "UPDATE patients
               SET gestational_age = (DATEDIFF(CURRENT_DATE, EDD) / 7 + 40) * 7
               WHERE user_id = $this->userId";


    // Execute the queries
    $this->conn->query($query3);

  }
  public function updatePregnancyStage()
  {
    $query1 = "UPDATE patients
               SET pregnancy_stage = 
               CASE
                   WHEN gestational_age <= 84 THEN 1
                   WHEN gestational_age <= 168 THEN 2
                   ELSE 3
               END
               WHERE user_id = $this->userId";

    $this->conn->query($query1);

  }

  // Getter for date of birth
  public function getDateOfBirth()
  {
    $stmt = $this->conn->prepare("SELECT date_of_birth FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['date_of_birth'];
  }

  // Setter for date of birth
  public function setDateOfBirth($dateOfBirth)
  {
    $stmt = $this->conn->prepare("UPDATE patients SET date_of_birth = ? WHERE user_id = ?");
    $stmt->bind_param("si", $dateOfBirth, $this->userId);
    $stmt->execute();
  }

  // Getter for age
  public function getAge()
  {
    $stmt = $this->conn->prepare("SELECT FLOOR(DATEDIFF(CURRENT_DATE, date_of_birth)/365) AS age FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['age'];
  }

  // Getter for previous pregnancies
  public function getPreviousPregnancies()
  {
    $stmt = $this->conn->prepare("SELECT previous_pregnancies FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['previous_pregnancies'];
  }

  // Setter for previous pregnancies
  public function setPreviousPregnancies($previousPregnancies)
  {
    if ($previousPregnancies === "true") {
      $previousPregnancies = 1;
    } else {
      $previousPregnancies = 0;
    }
    $stmt = $this->conn->prepare("UPDATE patients SET previous_pregnancies = ? WHERE user_id = ?");
    $stmt->bind_param("si", $previousPregnancies, $this->userId);
    $stmt->execute();
  }

  // Getter for pregnancy stage
  public function getLMP()
  {
    $stmt = $this->conn->prepare("SELECT LMP FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['LMP'];
  }
  public function getProfilePicture()
  {
    $stmt = $this->conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['profile_picture'];
  }
  // Setter for pregnancy stage
  public function setPregnancyStage($pregnancyStage)
  {
    $stmt = $this->conn->prepare("UPDATE patients SET pregnancy_stage = ? WHERE user_id = ?");
    $stmt->bind_param("si", $pregnancyStage, $this->userId);
    $stmt->execute();
  }

  // Getter for diabetic
  public function getDiabetic()
  {
    $stmt = $this->conn->prepare("SELECT diabetic FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['diabetic'];
  }

  // Setter for diabetic
  public function setDiabetic($diabetic)
  {
    $stmt = $this->conn->prepare("UPDATE patients SET diabetic = ? WHERE user_id = ?");
    $stmt->bind_param("si", $diabetic, $this->userId);
    $stmt->execute();
  }

  // Getter for hypertension
  public function getHypertension()
  {
    $stmt = $this->conn->prepare("SELECT hypertension FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['hypertension'];
  }
  public function getEDD()
  {
    $stmt = $this->conn->prepare("SELECT EDD FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['EDD'];
  }  public function getGestationalAge()
  {
    $stmt = $this->conn->prepare("SELECT gestational_age FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['gestational_age'];
  }
  // Setter for hypertension
  public function setHypertension($hypertension)
  {
    $stmt = $this->conn->prepare("UPDATE patients SET hypertension = ? WHERE user_id = ?");
    $stmt->bind_param("si", $hypertension, $this->userId);
    $stmt->execute();
  }
  public function getAllInfo()
  {
    $data = array(
      'dob' => $this->getDateOfBirth(),
      'location' => $this->getLocation(),
      'prevPreg' => $this->getPreviousPregnancies(),
      'LMP' => $this->getLMP(),
      'diabetic' => $this->getDiabetic(),
      'hypertension' => $this->getHypertension(),
      'EDD' => $this->getEDD(),
      'gest_age' => $this->getGestationalAge(),
      'profile_picture' => $this->getProfilePicture()

    );
    return $data;
  }

  public function insert_hr($bpm, $user_id)
{
    // Validate that input is an integer
    $bpm = (int) $bpm;

    // Sanitize input
    $bpm = filter_var($bpm, FILTER_SANITIZE_NUMBER_INT);
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);

    // Validate BPM is within a reasonable range
    if ($bpm < 40 || $bpm > 200) {

        header("LOCATION: ../../heartRate.php?error=invalidValue");

        return "Error: Invalid BPM value.";
    }

    // Prepare and bind statement
    $stmt = $this->conn->prepare("INSERT INTO heart_rate (user_id, bpm) VALUES (?, ?)");

    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $this->conn->error);
        exit;
    }

    $stmt->bind_param("ii", $user_id, $bpm);

    // Execute statement and check for errors
    if ($stmt->execute() === TRUE) {
        // Close statement and connection
        $stmt->close();
        $this->conn->close();
        header("LOCATION: ../../heartRate.php?insert=success");
        return "Success: New heart rate record created successfully.";
    } else {
        return "Error: " . $stmt->error;
    }
}

public function insert_bp($systolic, $diastolic, $user_id)
{
    // Validate that inputs are integers
    $systolic = (int) $systolic;
    $diastolic = (int) $diastolic;

    // Sanitize inputs
    $systolic = filter_var($systolic, FILTER_SANITIZE_NUMBER_INT);
    $diastolic = filter_var($diastolic, FILTER_SANITIZE_NUMBER_INT);
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);

    // Validate systolic and diastolic are within reasonable range
    if ($systolic < 70 || $systolic > 200 || $diastolic < 40 || $diastolic > 120) {
        header("LOCATION: ../../bloodPressure.php?error=invalidValue");

        return "Error: Invalid systolic or diastolic blood pressure value.";
    }

    // Prepare and bind statement
    $stmt = $this->conn->prepare("INSERT INTO blood_pressure (user_id, systolic, diastolic) VALUES (?, ?, ?)");

    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $this->conn->error);
        exit;
    }

    $stmt->bind_param("iii", $user_id, $systolic, $diastolic);

    // Execute statement and check for errors
    if ($stmt->execute() === TRUE) {
        // Close statement and connection
        $stmt->close();
        $this->conn->close();
        header("LOCATION: ../../bloodPressure.php?insert=success");
        return "Success: New blood pressure record created successfully.";
    } else {
        return "Error: " . $stmt->error;
    }
}


  public function addTemperature($temperature, $userId)
  {
    // Validate temperature
    if (!is_numeric($temperature) || $temperature < 34 || $temperature > 43) {
        header("LOCATION: ../../temperature.php?error=invalidValue");

      return false;
    }

    // Sanitize input
    $temperature = filter_var($temperature, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    // Insert into database


    $stmt = $this->conn->prepare("INSERT INTO temperature (user_id, temp) VALUES (?, ?)");
    $stmt->bind_param("sd", $userId, $temperature);

    // Execute statement and check for errors
    if ($stmt->execute() === TRUE) {
      // Close statement and connection
      $stmt->close();
      $this->conn->close();
      header("LOCATION: ../../temperature.php?insert=success");
      return true;
    } else {
      return "Error: " . $stmt->error;
    }
  }

  public function insert_blood_oxygen($blood_oxygen, $user_id)
  {
    // Validate input
    if (!is_numeric($blood_oxygen) || $blood_oxygen < 0 || $blood_oxygen > 100) {
        header("LOCATION: ../../bloodOxygen.php?error=invalidValue");

      return false; // Invalid input
    }

    // Sanitize input
    $blood_oxygen = filter_var($blood_oxygen, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);


    // Prepare and bind SQL statement
    $stmt = $this->conn->prepare("INSERT INTO blood_oxygen (user_id, percentage) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $blood_oxygen);

    // Execute SQL statement and check for errors
    if (!$stmt->execute()) {
      $stmt->close();
      $this->conn->close();
      return false; // SQL error
    }

    $stmt->close();
    $this->conn->close();
    header("LOCATION: ../../bloodOxygen.php?insert=success");

    return true; // Success
  }

  public function insertBloodGlucose($blood_glucose, $user_id)
  {

    // Sanitize blood glucose level
    $blood_glucose = filter_var($blood_glucose, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Validate blood glucose level
    if (!is_numeric($blood_glucose) || $blood_glucose < 0 || $blood_glucose > 100) {
        header("LOCATION: ../../bloodGlucose.php?error=invalidValue");

      return false;
    }

    // Prepare and bind the insert statement
    $stmt = $this->conn->prepare("INSERT INTO blood_glucose(user_id, glucose_level) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $blood_glucose);

    // Execute SQL statement and check for errors
    if (!$stmt->execute()) {
      $stmt->close();
      $this->conn->close();
      return false; // SQL error
    }

    $stmt->close();
    $this->conn->close();
    header("LOCATION: ../../bloodGlucose.php?insert=success");
    return true; // Success
  }

  function insertOrUpdateFetusRecord($user_id, $weight, $heart_rate)
  {
    // Validate inputs

    // Check that weight is a positive float or integer within a reasonable range
    if (!is_numeric($weight) || $weight <= 0 || $weight > 5000) {
        header("LOCATION: ../../fetus.php?error=invalidValue");

      return false;
    }

    // Check that heart rate is a positive integer within a reasonable range
    if (!is_numeric($heart_rate) || $heart_rate <= 0 || $heart_rate > 200) {
        header("LOCATION: ../../fetus.php?error=invalidValue");

      return false;
    }

    // Sanitize inputs
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
    $weight = filter_var($weight, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $heart_rate = filter_var($heart_rate, FILTER_SANITIZE_NUMBER_INT);

    // Fetch gestational age from the patients table
    $stmt = $this->conn->prepare("SELECT gestational_age FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      // User ID not found in patients table
      $stmt->close();
      $this->conn->close();
      return false;
    }

    $row = $result->fetch_assoc();
    $gestational_age = $row['gestational_age'];

    $stmt->close();

    // Insert data into database

    $stmt = $this->conn->prepare("INSERT INTO fetus (user_id, gestational_age, weight, heart_rate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $user_id, $gestational_age, $weight, $heart_rate);

    if (!$stmt->execute()) {
      $stmt->close();
      $this->conn->close();
      return false; // SQL error
    }

    $stmt->close();
    $this->conn->close();
    header("LOCATION: ../../fetus.php?insert=success");
    return true; // Success
  }





  // authentications for the patient to be able to access the main menu of patients

  public function isUserConfirmed($user_id)
  {
    $query = "SELECT confirmed FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
      die("Error preparing statement: " . $this->conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
      die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user['confirmed'] == 1) {
      return true;
    } else {
      return false;
    }
  }

  // checks the access level of the user if he is patient
  public function isPatient($user_id)
  {
    $query = "SELECT access_level FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user['access_level'] == 1) {
      return true;
    } else {
      return false;
    }
  }

  // checks if the patient has a record in the table patient
  public function has_patient_record($user_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
  }

  // returns the patients data for the doctor tab
  public function getPatientsByDoctorId($doctor_id)
  {

    // Prepare the SQL statement to join the tables and retrieve the patient data
    $stmt = $this->conn->prepare("
    SELECT u.id, p.location, CONCAT(u.first_name, ' ', u.last_name) AS name, u.phone_number,p.diabetic, p.hypertension, p.previous_pregnancies, p.pregnancy_stage, TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) AS age FROM patients p JOIN patient_doctor pd ON p.patient_id = pd.patient_id JOIN users u ON p.user_id = u.id JOIN doctors d ON pd.doctor_id = d.doctor_id WHERE d.user_id = ?;
    
    ");
    // Bind the doctor ID parameter to the SQL statement
    $stmt->bind_param('i', $doctor_id);

    // Execute the SQL statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch the patient data as an associative array
    $patients = [];
    while ($row = $result->fetch_assoc()) {
      $patients[] = $row;
    }

    // Loop through the patient data and calculate the age of each patient


    // Close the database connection
    $this->conn->close();
    echo json_encode($patients);
    // Return the patient data as a JSON array
    return true;
  }

  public function getRecentUserData($user_id)
  {
    // Set the user_id parameter for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Prepare and execute the query for each table
    $tables = array('blood_glucose', 'blood_oxygen', 'fetus', 'blood_pressure', 'temperature', 'user_files', 'heart_rate');
    $data = array();

    foreach ($tables as $table) {
      $sql = "SELECT * FROM $table WHERE user_id = '$user_id' ORDER BY timestamp DESC LIMIT 1";
      $result = $this->conn->query($sql);

      if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $timestamp = $row['timestamp'];
        $row['date'] = date('Y-m-d', strtotime($timestamp));
        $row['time'] = date('H:i:s', strtotime($timestamp));
        unset($row['timestamp']);
        $data[$table] = $row;
      } else {
        $data[$table] = array();
      }
    }

    // Close the database connection
    $this->conn->close();

    // Echo the data as a JSON array
    echo json_encode($data);
  }

  public function getBloodGlucoseData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);
    $time_range = $this->conn->real_escape_string($time_range);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');
    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }
    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, glucose_level, DATE(timestamp) AS date, TIME(timestamp) AS time 
          FROM blood_glucose 
          WHERE user_id = $user_id AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'value' => $row['glucose_level'],
          'date' => $row['date'],
          'time' => $row['time']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }

  public function getBloodOxygenData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }
    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, percentage, DATE(timestamp) AS date, TIME(timestamp) AS time 
          FROM blood_oxygen 
          WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'value' => $row['percentage'],
          'date' => $row['date'],
          'time' => $row['time']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }

  public function getFetusData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);
    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }

    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, gestational_age, weight, heart_rate, DATE(timestamp) AS date, TIME(timestamp) AS time 
        FROM fetus 
        WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'gestational_age' =>(int)((int) $row['gestational_age'] / 7) ,
          'weight' => $row['weight'],
          'heart_rate' => $row['heart_rate'],
          'date' => $row['date'],
          'time' => $row['time']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }


  public function getHRData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }

    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, DATE(timestamp) AS date, TIME(timestamp) AS time, bpm 
            FROM heart_rate 
            WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'date' => $row['date'],
          'time' => $row['time'],
          'value' => $row['bpm'],

        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }

  public function getBPData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }

    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, DATE(timestamp) AS date, TIME(timestamp) AS time, systolic, diastolic 
            FROM blood_pressure 
            WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'date' => $row['date'],
          'time' => $row['time'],
          'systolic' => $row['systolic'],
          'diastolic' => $row['diastolic']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }

  public function getTemperatureData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }

    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, temp, DATE(timestamp) AS date, TIME(timestamp) AS time 
          FROM temperature 
          WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'value' => $row['temp'],
          'date' => $row['date'],
          'time' => $row['time']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }


  public function getUserFilesData($user_id, $time_range)
  {
    // Set the user_id and time_range parameters for the query
    $user_id = $this->conn->real_escape_string($user_id);

    // Calculate the start and end dates based on the time range
    $start_date = '';
    $end_date = date('Y-m-d');

    if ($time_range === 'weekly') {
      $start_date = date('Y-m-d', strtotime('-1 week'));
    } elseif ($time_range === 'monthly') {
      $start_date = date('Y-m-d', strtotime('-1 month'));
    } elseif ($time_range === 'yearly') {
      $start_date = date('Y-m-d', strtotime('-1 year'));
    }

    // Prepare and execute the query
    $sql = "SELECT record_id, user_id, DATE(timestamp) AS date, TIME(timestamp) AS time, file_path 
          FROM user_files 
          WHERE user_id = '$user_id' AND DATE(timestamp) BETWEEN '$start_date' AND '$end_date' ORDER BY timestamp DESC";
    $result = $this->conn->query($sql);

    // Check if the query was successful
    if ($result) {
      $data = array();

      // Fetch the result rows and process the data
      while ($row = $result->fetch_assoc()) {
        $data[] = array(
          'record_id' => $row['record_id'],
          'user_id' => $row['user_id'],
          'date' => $row['date'],
          'time' => $row['time'],
          'file_path' => $row['file_path']
        );
      }

      // Echo the data as a JSON array
      echo json_encode($data);
    } else {
      // Handle query error
      echo 'Error: ' . $this->conn->error;
    }
  }

  



  // trimester for the patient app
  function getPatientPregnancyStage($user_id)
  {
    // Sanitize the user_id input
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);

    // Prepare and execute the database query
    $stmt = $this->conn->prepare("SELECT id, pregnancy_stage, first_name, last_name, profile_picture FROM patients, users WHERE users.id = patients.user_id AND user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user_id exists in the table
    if ($result->num_rows === 0) {
      $stmt->close();
      return json_encode(['error' => 'User ID not found in the patients table']);
    }
    
    // Fetch the pregnancy_stage from the query result
    $doctorName = $this->getDoctorName();
    
    
    $row = $result->fetch_assoc();
    $row['doctor_name'] = $doctorName;
    $stmt->close();

    // Return the pregnancy_stage as JSON
    echo json_encode($row);
  }

  function getDoctorName()
{
  // Prepare and execute the database query to retrieve doctor name
  $stmt = $this->conn->prepare("SELECT
  u_doctor.first_name AS doctor_first_name,
  u_doctor.last_name AS doctor_last_name
FROM
  patient_doctor AS pd
JOIN
  doctors AS d ON pd.doctor_id = d.doctor_id
JOIN
  users AS u_doctor ON d.user_id = u_doctor.id
JOIN
  patients AS p ON pd.patient_id = p.patient_id
WHERE
  p.user_id = ?;
");
  $stmt->bind_param("i", $this->userId);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if the doctor_id exists in the table
  if ($result->num_rows === 0) {
    $stmt->close();
    return null; // Or you can return an empty string or any other value to indicate no doctor found
  }

  // Fetch the doctor name from the query result
  $row = $result->fetch_assoc();
  $stmt->close();

  // Concatenate first name and last name to get the full doctor name
  $doctorName = $row['doctor_first_name'] . ' ' . $row['doctor_last_name'];

  return $doctorName;
}

  public function assignDoctorToPatient($patientUserId, $doctorId)
  {
    // Retrieve the patient_id based on the patient's user_id
    $query = "SELECT patient_id FROM patients WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $patientUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $patientId = $row['patient_id'];

      // Check if the patient-doctor relationship already exists in the patient_doctor table
      $checkQuery = "SELECT * FROM patient_doctor WHERE patient_id = ?";
      $checkStmt = $this->conn->prepare($checkQuery);
      $checkStmt->bind_param("i", $patientId);
      $checkStmt->execute();
      $checkResult = $checkStmt->get_result();

      if ($checkResult->num_rows === 0) {
        // The patient-doctor relationship does not exist, so insert it into the patient_doctor table
        $insertQuery = "INSERT INTO patient_doctor (patient_id, doctor_id) VALUES (?, ?)";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $patientId, $doctorId);
        $insertStmt->execute();

        if ($insertStmt->affected_rows === 1) {
          // Successfully inserted the record
          return true;
        } else {
          // Failed to insert the record
          return false;
        }
      } else {
        $insertQuery = "UPDATE  patient_doctor  SET doctor_id = ? WHERE patient_id = ?";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $doctorId, $patientId);
        $insertStmt->execute();
        return true;
      }
    } else {
      // Patient not found
      return false;
    }
  }
public function deleteBloodGlucoseRecord($user_id, $record_id) {
    $query = "DELETE FROM blood_glucose WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public function deleteBloodOxygenRecord($user_id, $record_id) {
    $query = "DELETE FROM blood_oxygen WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public function deleteFetusRecord($user_id, $record_id) {
    $query = "DELETE FROM fetus WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public function deleteHR($user_id, $record_id) {
    $query = "DELETE FROM heart_rate WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
public function deleteBP($user_id, $record_id) {
  $query = "DELETE FROM blood_pressure WHERE user_id = ? AND record_id = ?";
  
  $stmt = $this->conn->prepare($query);
  $stmt->bind_param("ii", $user_id, $record_id);
  
  if ($stmt->execute()) {
      return true;
  } else {
      return false;
  }
}
public function deleteTemperatureRecord($user_id, $record_id) {
    $query = "DELETE FROM temperature WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public function deleteUserFile($user_id, $record_id) {
    $query = "DELETE FROM user_files WHERE user_id = ? AND record_id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $record_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}







}



?>