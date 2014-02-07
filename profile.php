<?php
session_start();
require_once('connection.php');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
</head>
<nav class="navbar navbar-default" role="navigation">
	<h2 id="title">CodingDojo Wall</h2>
	<?php
		$query = "SELECT first_name, last_name, email
				  FROM users
				  WHERE id = ".$_GET['id'];
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_assoc($result);		 
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id'])
		{
			?>
			<div id="welcome">
				<span class="label label-success">Welcome <?= $row['first_name'].' '.$row['last_name'] ?> !</span><span class="label label-primary"><a href="process.php?logout=1"> Log Out</a></span>
			</div>
			<?php 
		} 
		
		?>	
</nav>
<body>
	<div id="container">
		<h3>Post a Message</h3>
		<div class="input-group">
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="message">
				<textarea type="text" name="message" cols="80" rows="5"></textarea><br>
				<input class="blue" type="Submit" value="Post a message">
			</form>
		</div>	
		<div id="wall">
			<h1><?=$row['first_name'].' '.$row['last_name']?></h1>
			<h2><?=$row['email']?></h2>
		</div>
	</div>
</body>
</html>
<style>
#title {
	display: inline;
}
#welcome {
	display: inline;
	position: relative;
	left: 800px;
}
a {
	color: white;
}
.blue {
	background-color: #428BCA;
	color: white;
}
</style>