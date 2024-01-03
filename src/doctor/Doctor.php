<?php
class Doctor
{
  private $conn;

  public function __construct()
  {
    $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
  }

  public function isDoctorConfirmed($user_id)
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

  public function isDoctor($user_id)
  {
    $query = "SELECT access_level FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user['access_level'] == 2) {
      return true;
    } else {
      return false;
    }
  }
  public function has_doctor_record($user_id)
  {
    // Assuming you have already connected to your database and stored the connection in a variable called $conn
    $stmt = $this->conn->prepare("SELECT * FROM doctors WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
  }

  public function insertDoctorData($user_id, $location, $education, $biography, $date_of_birth, $clinic_names = [], $clinic_phone_numbers = [], $clinic_locations = [])
  {
    // Validate input fields
    $errors = [];

    if (empty($user_id) || !is_numeric($user_id) || $user_id <= 0) {
      $errors['user_id'] = "Invalid user ID.";
    }

    if (empty($location) || !is_string($location)) {
      $errors['location'] = "Invalid location.";
    }

    if (empty($education) || !is_string($education)) {
      $errors['education'] = "Invalid education.";
    }

    if (empty($biography) || !is_string($biography)) {
      $errors['biography'] = "Invalid biography.";
    }

    if (empty($date_of_birth) || !DateTime::createFromFormat('Y-m-d', $date_of_birth)) {
      $errors['date_of_birth'] = "Invalid date of birth. Please use the format YYYY-MM-DD.";
    }

    // Handle validation errors
    if (!empty($errors)) {
      return $errors; // or throw an exception with the errors
    }



    // Sanitize input fields
    $location = filter_var($location, FILTER_SANITIZE_STRING);
    $education = filter_var($education, FILTER_SANITIZE_STRING);
    $biography = filter_var($biography, FILTER_SANITIZE_STRING);
    $date_of_birth = filter_var($date_of_birth, FILTER_SANITIZE_STRING);

    // Check if a record with the given user_id already exists
    if ($this->has_doctor_record($user_id)) {
      // A record already exists, update it with the new data
      $sql = "UPDATE doctors SET location = ?, education = ?, biography = ?, date_of_birth = ? WHERE user_id = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("ssssi", $location, $education, $biography, $date_of_birth, $user_id);
    } else {
      // No record exists, insert a new one with the provided data
      $sql = "INSERT INTO doctors (user_id, location, education, biography, date_of_birth) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("issss", $user_id, $location, $education, $biography, $date_of_birth);
    }

    // Execute the prepared statement
    if ($stmt->execute() && $clinic_names != null && $clinic_phone_numbers != null && $clinic_locations != null) {
      // Validate clinic inputs
      if (empty($clinic_names) || !is_array($clinic_names) || count($clinic_names) === 0) {
        return false;
      }

      if (empty($clinic_phone_numbers) || !is_array($clinic_phone_numbers) || count($clinic_phone_numbers) === 0) {
        return false;
      }

      if (empty($clinic_locations) || !is_array($clinic_locations) || count($clinic_locations) === 0) {
        return false;
      }
      // Insert doctor clinics
      $clinic_count = min(count($clinic_names), count($clinic_phone_numbers), count($clinic_locations));
      for ($i = 0; $i < $clinic_count; $i++) {
        $clinic_name = $clinic_names[$i];
        $clinic_phone_number = $clinic_phone_numbers[$i];
        $clinic_location = $clinic_locations[$i];

        $sql = "INSERT INTO clinics (user_id, clinic_name, phone_number, clinic_location) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $clinic_name, $clinic_phone_number, $clinic_location);
        $stmt->execute();
      }

      return true;
    }
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getTotalDoctors()
  {

    // Number of results per page
    $resultsPerPage = 10;

    // Query to get the total number of doctors
    $countQuery = "SELECT COUNT(*) AS total FROM doctors";
    $countResult = $this->conn->query($countQuery);
    $totalDoctors = $countResult->fetch_assoc()['total'];

    // Calculate the total number of pages
    $totalPages = ceil($totalDoctors / $resultsPerPage);

    // Output the total number of pages as JSON
    header("Content-Type: application/json");
    echo json_encode(['totalPages' => $totalPages]);

  }
  public function getDoctors($page)
  { // Validate and sanitize the page number
    $page = filter_var($page, FILTER_VALIDATE_INT);
    $page = ($page !== false && $page > 0) ? $page : 1;
    // Number of results per page
    $resultsPerPage = 10;

    // Calculate the offset for the current page
    $offset = ($page - 1) * $resultsPerPage;

    // Prepare the query to fetch doctors
    $query = "SELECT doctors.*, users.phone_number, CONCAT(users.first_name, ' ', users.last_name) AS name
    FROM doctors 
    JOIN users ON doctors.user_id = users.id LIMIT ?, ?;";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $offset, $resultsPerPage);
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch the doctors into an array
    $doctors = [];
    while ($row = $result->fetch_assoc()) {
      $doctors[] = $row;
    }

    // Close the statement
    $stmt->close();

    // Output the doctors as JSON
    header("Content-Type: application/json");
    echo json_encode($doctors);
  }


  public function fetchDoctorsDataAsJson($userID)
{
  $query = "SELECT doctors.*, clinics.*, users.profile_picture, users.first_name, users.last_name, users.email
    FROM users
    JOIN doctors ON users.id = doctors.user_id
    LEFT JOIN clinics ON doctors.user_id = clinics.user_id
    WHERE users.id = ?";
  $stmt = $this->conn->prepare($query);
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $result = $stmt->get_result();

  $data = array();
  $clinics = array();

  while ($row = $result->fetch_assoc()) {
    $data['doctor_id'] = $row['doctor_id'];
    $data['user_id'] = $row['user_id'];
    $data['location'] = $row['location'];
    $data['education'] = $row['education'];
    $data['biography'] = $row['biography'];
    $data['date_of_birth'] = $row['date_of_birth'];
    $data['profile_picture'] = $row['profile_picture'];
    $data['first_name'] = $row['first_name'];
    $data['last_name'] = $row['last_name'];
    $data['email'] = $row['email'];

    // Check if the current row has clinic data
    if (!empty($row['clinic_name'])) {
      $clinic = array(); // Create a new clinic array

      $clinic['clinic_name'] = $row['clinic_name'];
      $clinic['clinic_number'] = $row['phone_number'];
      $clinic['clinic_location'] = $row['clinic_location'];
      $clinic['clinic_id'] = $row['clinic_id'];

      $clinics[] = $clinic; // Add the clinic to the clinics array
    }
  }

  // Assign clinics array to the data array if it's not empty
  if (!empty($clinics)) {
    $data['clinics'] = $clinics;
  }

  // Set the appropriate header for JSON response
  header('Content-Type: application/json');

  // Echo the data as JSON
  echo json_encode($data);
}


  public function deleteClinicRecord($clinic_id, $user_id)
  {
    // Prepare the delete query
    $query = "DELETE FROM clinics WHERE clinic_id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $clinic_id, $user_id);

    // Execute the delete query
    if ($stmt->execute()) {
      // Deletion successful
      return true;
    } else {
      // Deletion failed
      return false;
    }
  }


  public function updateClinicRecord($user_id, $clinic_id, $clinic_name, $phone_number, $clinic_location)
  {
    // Validate input fields
    $errors = [];

    if (empty($user_id) || !is_numeric($user_id) || $user_id <= 0) {
      $errors['user_id'] = "Invalid user ID.";
    }

    if (empty($clinic_id) || !is_numeric($clinic_id) || $clinic_id <= 0) {
      $errors['clinic_id'] = "Invalid clinic ID.";
    }

    if (empty($clinic_name) || !is_string($clinic_name)) {
      $errors['clinic_name'] = "Invalid clinic name.";
    }

    if (empty($phone_number) || !is_string($phone_number)) {
      $errors['phone_number'] = "Invalid phone number.";
    }

    if (empty($clinic_location) || !is_string($clinic_location)) {
      $errors['clinic_location'] = "Invalid clinic location.";
    }

    // Handle validation errors
    if (!empty($errors)) {
      return $errors; // or throw an exception with the errors
    }

    // Sanitize input fields
    $clinic_name = filter_var($clinic_name, FILTER_SANITIZE_STRING);
    $phone_number = filter_var($phone_number, FILTER_SANITIZE_STRING);
    $clinic_location = filter_var($clinic_location, FILTER_SANITIZE_STRING);

    // Update the clinic record
    $query = "UPDATE clinics SET clinic_name = ?, phone_number = ?, clinic_location = ? WHERE user_id = ? AND clinic_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssii", $clinic_name, $phone_number, $clinic_location, $user_id, $clinic_id);

    if ($stmt->execute()) {
      // Update successful
      return true;
    } else {
      // Update failed
      return false;
    }
  }


}

?>