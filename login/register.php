<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Initialize variables and error messages
    $password_err = $confirm_password_err = $first_name_err = $last_name_err = $email_err = "";
    $first_name = $last_name = $email = $password = "";

    // Validate and sanitize First Name
    if (empty(trim($_POST["FirstName"]))) {
        $first_name_err = "First name cannot be blank";
    } else {
        $first_name = trim($_POST["FirstName"]);
    }

    // Validate and sanitize Last Name
    if (empty(trim($_POST["LastName"]))) {
        $last_name_err = "Last name cannot be blank";
    } else {
        $last_name = trim($_POST["LastName"]);
    }

    // Validate and sanitize Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Email cannot be blank";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check for password
    if(empty(trim($_POST['password']))){
        $password_err = "Password cannot be blank";
    }
    elseif(strlen(trim($_POST['password'])) < 5){
        $password_err = "Password cannot be less than 5 characters";
    }
    else{
        $password = trim($_POST['password']);
    }

    // Check for confirm password field
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = "Please confirm your password";
    } elseif(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
        $confirm_password_err = "Passwords should match";
    }

    // If there were no errors, go ahead and insert into the database
    if(empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err))
    {
        $sql = "INSERT INTO users (password, first_name, last_name, email) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt)
        {
            mysqli_stmt_bind_param($stmt, "ssss", $param_password, $param_first_name, $param_last_name, $param_email);

            // Set these parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;

            // Try to execute the query
            if (mysqli_stmt_execute($stmt))
            {
                header("location: login.php");
            }
            else{
                echo "Something went wrong... cannot redirect!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>TAXIFY</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TAXIFY</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4">
    <h3>Please Register Here:</h3>
    <hr>
    <form action="" method="post">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputFirstName">First Name</label>
          <input type="text" class="form-control" name="FirstName" id="inputFirstName" placeholder="First Name">
        </div>
        <div class="form-group col-md-6">
          <label for="inputLastName">Last Name</label>
          <input type="text" class="form-control" name="LastName" id="inputLastName" placeholder="Last Name">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail">Email ID</label>
        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Email">
      </div>
      <div class="form-group">
        <label for="inputPassword">Password</label>
        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password">
      </div>
      <div class="form-group">
        <label for="inputConfirmPassword">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" id="inputConfirmPassword" placeholder="Confirm Password">
      </div>
      <button type="submit" class="btn btn-primary">Sign in</button>
    </form>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
