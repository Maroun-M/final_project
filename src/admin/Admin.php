<?php
class Admin
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    }

    public function isAdmin($userId)
    {
        $query = "SELECT access_level FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $accessLevel = $row['access_level'];
        $stmt->close();
        
        return ($accessLevel == 3);
    }
    
    public function getUsersData($name = "", $page = 1, $perPage = 10)
    {
        // Validate and sanitize the page number
        $page = filter_var($page, FILTER_VALIDATE_INT);
        $page = ($page !== false && $page > 0) ? $page : 1;

        // Validate and sanitize the number of records per page
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT);
        $perPage = ($perPage !== false && $perPage > 0) ? $perPage : 10;

        // Calculate the offset for pagination
        $offset = ($page - 1) * $perPage;

        // Create the query to retrieve users
        $query = "SELECT CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
              TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) AS age,
              p.location,
              u.phone_number,
              u.id
              FROM users u
              JOIN patients p ON u.id = p.user_id
              WHERE u.access_level = 1";

        $params = array();
        // Append search condition if name is provided
        if ($name !== "") {
            $query .= " AND CONCAT(u.first_name, ' ', u.last_name) LIKE ?";
            $params[] = "%$name%";
        }

        // Append pagination limit and offset to the query
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the search parameter if name is provided
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        $stmt->execute();

        // Fetch the results
        $result = $stmt->get_result();
        $usersData = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement
        $stmt->close();

        // Prepare the response data
        $responseData = array(
            'totalPages' => 0,
            'data' => $usersData
        );

        // Set the response headers and output the JSON response
        if (!empty($usersData)) {
            // Calculate the total count of records
            $totalCount = count($usersData);

            // Calculate the total number of pages
            $totalPages = ceil($totalCount / $perPage);
            $responseData['totalPages'] = $totalPages;
        }

        header('Content-Type: application/json');
        echo json_encode($responseData);
    }


    public function getDoctors($name = "", $page = 1, $perPage = 10)
    {
        // Validate and sanitize the page number
        $page = filter_var($page, FILTER_VALIDATE_INT);
        $page = ($page !== false && $page > 0) ? $page : 1;

        // Validate and sanitize the number of records per page
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT);
        $perPage = ($perPage !== false && $perPage > 0) ? $perPage : 10;

        // Calculate the offset for pagination
        $offset = ($page - 1) * $perPage;

        // Create the query to retrieve users
        $query = "SELECT CONCAT(u.first_name, ' ', u.last_name) AS full_name, TIMESTAMPDIFF(YEAR, d.date_of_birth, CURDATE()) AS age, d.location, u.phone_number, d.education, u.id FROM users u JOIN doctors d ON u.id = d.user_id WHERE u.access_level = 2";

        $params = array();
        // Append search condition if name is provided
        if ($name !== "") {
            $query .= " AND CONCAT(u.first_name, ' ', u.last_name) LIKE ?";
            $params[] = "%$name%";
        }

        // Append pagination limit and offset to the query
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the search parameter if name is provided
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        $stmt->execute();

        // Fetch the results
        $result = $stmt->get_result();
        $usersData = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement
        $stmt->close();

        // Prepare the response data
        $responseData = array(
            'totalPages' => 0,
            'data' => $usersData
        );

        // Set the response headers and output the JSON response
        if (!empty($usersData)) {
            // Calculate the total count of records
            $totalCount = count($usersData);

            // Calculate the total number of pages
            $totalPages = ceil($totalCount / $perPage);
            $responseData['totalPages'] = $totalPages;
        }

        header('Content-Type: application/json');
        echo json_encode($responseData);
    }












    public function getDoctorsData($page = 1, $perPage = 10)
    {
        // Validate and sanitize the page number
        $page = filter_var($page, FILTER_VALIDATE_INT);
        $page = ($page !== false && $page > 0) ? $page : 1;

        // Validate and sanitize the number of records per page
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT);
        $perPage = ($perPage !== false && $perPage > 0) ? $perPage : 10;

        // Calculate the offset for pagination
        $offset = ($page - 1) * $perPage;

        $query = "SELECT CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                         TIMESTAMPDIFF(YEAR, d.date_of_birth, CURDATE()) AS age,
                          u.phone_number
                  FROM doctors AS d
                  INNER JOIN users AS u ON d.user_id = u.id
                  LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $offset, $perPage);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctorsData = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode($doctorsData);
    }


    public function forceRegisterUser($firstName, $lastName, $phoneNumber, $email, $password, $accessLevel)
{
    // Check if the email already exists in the database
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // If a row is found, it means the email already exists
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("LOCATION: ../../manageUsers.php?error=duplicateEmail");
        return "Email already exists";
    }
    
    // Continue with the user registration process
    $options = [
        'cost' => 12,
    ];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
    // Set confirmed to 1
    $confirmed = 1;

    $query = "INSERT INTO users (first_name, last_name, phone_number, email, account_password, confirmed, access_level)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssssii", $firstName, $lastName, $phoneNumber, $email, $hashedPassword, $confirmed, $accessLevel);
    $stmt->execute();
    $stmt->close();
    
    return "User registered successfully";
}



    public function forceDeleteUser($userId)
    {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }





}


?>