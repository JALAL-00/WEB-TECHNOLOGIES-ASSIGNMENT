<?php
$errors = [];

// Check if the book is already borrowed using cookies
if (isset($_POST["btitle"])) {
    $book = trim($_POST["btitle"]);
    if (isset($_COOKIE["borrowed_book"]) && $_COOKIE["borrowed_book"] === $book) {
        $errors[] = "This book has already been borrowed recently. Please wait 15 seconds before borrowing it again.";
    } else {
        // Set a cookie for the borrowed book, valid for 15 seconds
        setcookie("borrowed_book", $book, time() + 15);
    }
}

// Name validation
if (empty($_POST["fname"])) {
    $errors[] = "Name is required";
} else {
    $name = trim($_POST["fname"]);
    if (!preg_match("/^([A-Z][a-z]*\s)*[A-Z][a-z]*$/", $name)) {
        $errors[] = "Each name should be start with letter & 1st letter shoub be capital";
    }
}

// ID validation
if (empty($_POST["id"])) {
    $errors[] = "ID is required";
} else {
    $id = trim($_POST["id"]);
    if (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $id)) {
        $errors[] = "ID format should be xx-xxxxx-x";
    }
}

// Email validation
if (empty($_POST["email"])) {
    $errors[] = "Email is required";
} else {
    $mail = trim($_POST["email"]);
    if (!preg_match("/^[\w\.-]+@student\.aiub\.edu$/", $mail)) {
        $errors[] = "Incorrect student email format";
    }
}

// Book title validation
if (empty($_POST["btitle"])) {
    $errors[] = "Please choose a book";
}

// Date validation
if (empty($_POST["bdate"]) || empty($_POST["rdate"])) {
    $errors[] = "Both borrow and return dates are required";
} else {
    $borrowDate = $_POST["bdate"];
    $returnDate = $_POST["rdate"];

    $borrowDateObj = DateTime::createFromFormat('Y-m-d', $borrowDate);
    $returnDateObj = DateTime::createFromFormat('Y-m-d', $returnDate);

    if ($borrowDateObj && $returnDateObj) {
        $dateDiff = $borrowDateObj->diff($returnDateObj)->days;
        if ($dateDiff > 10) {
            $errors[] = "You have missed the submission deadline, can't be borrowed for more than 10 days";
        }
    } else {
        $errors[] = "Invalid date format";
    }
}

// Token validation
if (empty($_POST["token"])) {
    $errors[] = "Token is required";
} else {
    $token = trim($_POST["token"]);
    if (!preg_match("/^[a-zA-Z0-9]{6}$/", $token)) {
        $errors[] = "Token must be exactly 6 alphanumeric characters";
    }
}

// Fees validation
if (empty($_POST["fees"])) {
    $errors[] = "Fees are required";
} else {
    $fees = trim($_POST["fees"]);
    if (!is_numeric($fees) || $fees < 0) {
        $errors[] = "Fees must be a positive number";
    }
}

// Output errors or success message
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
} else {
    // Generate receipt
    echo "<h2 style='color: green;'>Receipt</h2>";
    echo "<div style='border: 1px solid #ccc; padding: 15px; border-radius: 10px; max-width: 400px;'>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>ID:</strong> $id</p>";
    echo "<p><strong>Email:</strong> $mail</p>";
    echo "<p><strong>Book Title:</strong> $book</p>";
    echo "<p><strong>Borrow Date:</strong> $borrowDate</p>";
    echo "<p><strong>Return Date:</strong> $returnDate</p>";
    echo "<p><strong>Token:</strong> $token</p>";
    echo "<p><strong>Fees:</strong> $fees</p>";
    echo "<p style='color: blue;'><strong>Thank you</strong></p>";
    echo "</div>";
}
?>
