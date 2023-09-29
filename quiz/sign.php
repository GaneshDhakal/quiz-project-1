<?php
include_once 'dbConnection.php';
ob_start();

// Initialize an array to store validation errors
$errors = [];

// Define a function to validate email format
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

$name = $gender = $email = $college = $mob = $password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $college = $_POST['college'];
    $mob = $_POST['mob'];
    $password = $_POST['password'];

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors[] = "Name should contain only alphabetic characters and spaces";
    } else {
        // Sanitize and format the name
        $name = ucwords(strtolower($name));
    }

    // Validate Gender (you may add more specific validation if needed)
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }

    // Validate Email (should contain only alphabetic characters before @gmail.com)
    // if (empty($email)) {
    //     $errors[] = "Email is required";
    // } elseif (!validateEmail($email)) {
    //     $errors[] = "Invalid email format";
    // } else {
    //     // Split the email address to get the part before @gmail.com
    //     list($username, $domain) = explode('@', $email);
        
    //     if ($domain !== 'gmail.com') {
    //         $errors[] = "Email domain should be @gmail.com";
    //     } elseif (!ctype_alpha($username)) {
    //         $errors[] = "Username part of email should contain only alphabetic characters";
    //     }
    // }

    // Validate College (you may add more specific validation if needed)
    if (empty($college)) {
        $errors[] = "College is required";
    }

    // Validate Mobile Number (you may add more specific validation if needed)
    if (empty($mob)) {
        $errors[] = "Mobile number is required";
    }

    // Validate Password (should not be empty)
    if (empty($password)) {
        $errors[] = "Password is required";
    } else {
        // Sanitize and hash the password
        $password = md5($password);
    }

    // If there are no validation errors, proceed with the database insertion
    if (empty($errors)) {
        $q3 = mysqli_query($con, "INSERT INTO user VALUES ('$name', '$gender', '$college', '$email', '$mob', '$password')");
        if ($q3) {
            session_start();
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;

            header("location:account.php?q=1");
        } else {
            $errors[] = "Email Already Registered!!!";
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Page</title>
</head>
<body>
    <!-- Your HTML form goes here -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Form fields -->

        <input type="submit" value="Register">
    </form>

    <!-- Display validation errors, if any -->
    <?php
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
    ?>
</body>
</html>
