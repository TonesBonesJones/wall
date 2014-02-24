<?php
session_start();
require_once('connection.php');

$posts_query = "SELECT first_name, last_name, email, /* users table */
							message, messages.created_at, messages.user_id as user_id, messages.id as message_id /* posts table */
					FROM messages 
					LEFT JOIN users 
					ON users.id = messages.user_id 
					ORDER BY messages.created_at DESC";

	$posts = fetch_all($posts_query);
	

$comments_query = "SELECT first_name, last_name, email, /* users table */
								comment, comments.created_at, comments.user_id as user_id, comments.id as comment_id, comments.message_id as message_id /* comments table */
						FROM comments 
						LEFT JOIN users 
						ON users.id = comments.user_id 
						LEFT JOIN messages
						ON comments.message_id = messages.id
						ORDER BY comments.created_at DESC";					

	$comments = fetch_all($comments_query);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
</head>
<nav class="navbar navbar-default" role="navigation">
	<h2 id="title">FakeBook Wall</h2>
	<?php
		$query = "SELECT first_name, last_name, email, created_at
				  FROM users
				  WHERE id = ".$_GET['id'];		  

		$row = fetch_record($query);		 
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id'])
		{
			?>
			<div id="welcome">
				<span class="label label-success">Welcome <?= $row['first_name'].' '.$row['last_name'] ?> !</span><span id="logout" class="label label-primary"><a href="process.php?logout=1"> Log Out</a></span>
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
				<input type="hidden" name="action" value="post">
				<input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
				<textarea id="post" type="text" name="post" cols="80" rows="5" placeholder="What's on your mind"></textarea><br>
				<button id="postbutton" class="label label-primary" type="Submit" value="Post">Post</button>
			</form>
		</div>	
<?php 		if(isset($_SESSION['notifications']))
			{
				foreach($_SESSION['notifications'] as $notification)
				{
					echo "<p style='color: green;'> $notification </p>";
				}
			}	
			if(isset($_SESSION['errors']))
			{
				foreach($_SESSION['errors'] as $error)
				{
					echo "<p style='color: red;'> $error </p>";
				}
			}
			$_SESSION['notifications'] = array();
			$_SESSION['errors'] = array();
?>

<?php 	if(isset($posts) && !empty($posts))
		{ 
?>
			<ol>
<?php		foreach($posts as $post)
			{ 
?>
				<li><p><?= $post['message'] ?></p>
					<small>By <?= $post['first_name'] ?> | <?= $post['created_at'] ?></small> 
				<ul>					
<?php			foreach($comments as $comment)
				{ 
					if($post['message_id'] == $comment['message_id'])
					{ 
?>
					<li>
						<p><?= $comment['comment'] ?></p>
						<small>by <?= $comment['first_name'] ?> | <?= $comment['created_at'] ?></small>
					</li>
<?php 				}
				} ?>
					<li>
						<form action="process.php" method="post">
							<input type="hidden" name="action" value="comment" />
							<input type="hidden" name="message_id" value="<?= $post['message_id'] ?>" />
							<input type="text" name="comment" placeholder="comment..." />
							<button class="label label-primary" type="submit" value="comment">Comment</button>
						</form>
					</li>
				</ul>
<?php		} ?>
			</ol>
<?php	} ?>
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
	left: 650px;
}
a {
	color: white;
}
p {
	margin-top: 5px;
}
#logout {
	margin-left: 20px;
}
#postbutton {
	float: right;
	margin-top: 10px;
	margin-bottom: 10px;
}
</style>