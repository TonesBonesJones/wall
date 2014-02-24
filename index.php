<?php
session_start();


?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The Wall</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
</head>
<body>
	<?php
  	if(isset($_SESSION['error']))
  	{
      	foreach($_SESSION['error'] as $name => $message)
      	{
        	?>
          <span class="label label-danger"><?=$message?></span>
        	<?php 
      	}
  	}
 	elseif(isset($_SESSION['success_message']))
	{
   		?>
   		<span class="label label-success"><?=$_SESSION['success_message']?></span>
   		<?php 
  	}
  	?>
    <h1> Welcome to FakeBook! Please Register or Log In Below</h1>
    <h3> New Members </h3>
  	<form action="process.php" method="post" enctype="multipart/form-data">
  		<input type="hidden" name="action" value="register">
  		<input type="text" name="first_name" placeholder="Enter First Name">
  		<input type="text" name="last_name" placeholder="Enter Last Name">
  		<input type="text" name="email" placeholder="Enter Email">
  		<input type="password" name="password" placeholder="Password">
  		<input type="password" name="confirm_password" placeholder="Confirm Password">
  		<input type="submit" value="Register">
  	</form>		
    <h3> Existing Members </h3>
    <form action="process.php" method="post">
      <input type="hidden" name="action" value="login">
      <input type="text" name="email" placeholder="Enter Email">
      <input type="password" name="password" placeholder="Password">  
      <input type="Submit" value="Login">
    </form>
</body>
</html>
<?php
  session_unset('error');
  session_unset('success_message');
?>