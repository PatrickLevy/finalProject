<!DOCTYPE html>
<html lang="en">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<head>
<meta charset="utf-8" />
<title>Login</title>
</head>
<body>

<p><h1>Testing Center Login Page</h1>
<h2>New Users</h2>

<p>Register as a new user:<br>
<form action="home.php" method="post">
  <input type="text" name="newUsername" placeholder="username">
  <input type="password" name="newPassword" placeholder="password">
  <select name="newRole">
    <option value="Student">Student</option>
    <option value="Teacher">Teacher</option>
  </select>
  <input type="submit" value="Login">
</form>

<br><br>
<a href="login.php">Click here to login with your existing account.</a>

</body>
</html>
