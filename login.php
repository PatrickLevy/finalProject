<!DOCTYPE html>
<html lang="en">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="finalProjectStyleSheet.css">
<head>
<meta charset="utf-8" />
<title>Login</title>
</head>
<body>

<p><h1>Testing Center Login Page</h1>
<h2>Existing Users</h2>
<p>Login in with your username and password:<br>
<form action="home.php" method="Post">
  <input type="text" name="username" placeholder="username">
  <input type="password" name="password" placeholder="password">
  <!-- <select name="role">
    <option value="student">Student</option>
    <option value="teacher">Teacher</option>
  </select> -->
  <input type="submit">
</form>
<br><br>
<a href="newUserlogin.php">Click here to register as a new user.</a>

</body>
</html>
