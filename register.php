<?php 
require 'function.php';

// Proses registrasi
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi input
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } else {
        // Cek apakah email sudah terdaftar
        $cekdatabase = mysqli_query($con, "SELECT * FROM login WHERE email = '$email'");
        if (mysqli_num_rows($cekdatabase) > 0) {
            $error = "Email already exists";
        } else {
            // Enkripsi password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $query = mysqli_query($con, "INSERT INTO login (email, password) VALUES ('$email', '$hashedPassword')");

            if ($query) {
                $_SESSION['success'] = "Registration successful. Please login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>Register</title>
        <link href="css/register.css" rel="stylesheet"/>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"
            crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">Register</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php if (isset($error)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $error ?>
                                        </div>
                                        <?php endif; ?><br>
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input
                                                    class="form-control py-4"
                                                    name="email"
                                                    id="inputEmailAddress"
                                                    type="email"
                                                    placeholder="Enter email address"
                                                    required="required"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input
                                                    class="form-control py-4"
                                                    name="password"
                                                    id="inputPassword"
                                                    type="password"
                                                    placeholder="Enter password"
                                                    required="required"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                <input
                                                    class="form-control py-4"
                                                    name="confirmPassword"
                                                    id="inputConfirmPassword"
                                                    type="password"
                                                    placeholder="Confirm password"
                                                    required="required"/>
                                            </div>
                                            <div
                                                class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="login.php">Already have an account? Login!</a>
                                                <button class="btn btn-primary" name="register">Register</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small">
                                            <a href="login.php">Back to Login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>

</html>