<?php
session_start();

// Initialize error message variable
$error = '';

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if necessary POST elements are set
    if (isset($_POST['adminname']) && isset($_POST['password'])) {
        // Get input from form
        $adminname = $_POST['adminname'];
        $password = $_POST['password'];

        // Check credentials
        if ($adminname === 'admin' && $password === 'admin123') {
            // Successful login
            $_SESSION['loggedin'] = true;
            header("Location: beranda.php"); // Redirect to admin dashboard
            exit();
        } else {
            // Failed login
            $error = "Username atau password salah.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7fafc;
        }
        .container {
            max-width: 400px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="container bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
        <form action="login.php" method="post">
            <div class="mb-4">
                <label for="adminname" class="block text-sm font-medium text-gray-700">Admin Name</label>
                <input type="text" id="adminname" name="adminname" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full px-4 py-3 bg-indigo-500 text-white font-medium rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Login</button>
            </div>
        </form>
    </div>
</body>
</html>

