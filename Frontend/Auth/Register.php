<?php
session_start();
include("../../Backend/DB.php"); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type     = mysqli_real_escape_string($conn, trim($_POST['type']));
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tel      = mysqli_real_escape_string($conn, trim($_POST['tel']));

    if (empty($name) || empty($email) || empty($_POST['password']) || empty($tel) || empty($type)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $check_sql = "SELECT id FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        exit;
    }

    $insert_sql = "INSERT INTO users (name, email, password, tel, type, account_balance) 
                   VALUES ('$name', '$email', '$password', '$tel', '$type', 0.00)";

    if (mysqli_query($conn, $insert_sql)) {
        $_SESSION['user_id']    = mysqli_insert_id($conn);
        $_SESSION['user_type']  = $type;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        $redirect = ($type === 'company') ? '../Company/Home.php' : '../Passenger/Home.php';

        echo json_encode([
            'success'  => true,
            'message'  => 'Registration successful!',
            'redirect' => $redirect
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../../CSS/Style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .card {
            width: 360px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background: #007cba;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #005a87;
        }
        .type-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 12px 0;
        }
        .type-group p {
            margin: 0;
            white-space: nowrap;
            font-weight: bold;
            color: #555;
            min-width: 110px;
        }
        .type-group select {
            flex: 1;
            margin: 0;
        }
        #msg {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            min-height: 24px;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Create Account</h2>
        <form id="registerForm">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="tel" name="tel" placeholder="Phone" required>

            <div class="type-group">
                <p>Account Type</p>
                <select name="type" required>
                    <option value="company">Company</option>
                    <option value="passenger">Passenger</option>
                </select>
            </div>

            <button type="submit">Register</button>
        </form>

        <div id="msg"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#msg').removeClass('error').addClass('success').text(response.message);
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 1500);
                        } else {
                            $('#msg').removeClass('success').addClass('error').text(response.message);
                        }
                    },
                    error: function() {
                        $('#msg').removeClass('success').addClass('error').text('Server connection error. Check console.');
                    }
                });
            });
        });
    </script>
</body>
</html>