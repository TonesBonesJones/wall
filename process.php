<?php
session_start();
require_once('connection.php');

function logout()
{
	$_SESSION = array();
	session_destroy();
}

function genRand($length) 
{
   $validCharacters = 'abcdefghijklmnopqrstuvwxyz0123456789';
   $myKeeper = '';
   for ($n = 1; $n < $length; $n++) {
      $whichCharacter = mt_rand(0, strlen($validCharacters)-1);
      $myKeeper .= $validCharacters{$whichCharacter};
	}
   return $myKeeper;
}


function register($connection, $post)
{
	foreach ($post as $name => $value) 
	{
		if(empty($value))
		{
			$_SESSION['error'][$name] = "sorry, " . $name . " cannot be blank";
		}
		else
		{
			switch ($name) {
				case 'first_name':
				case 'last_name':
					if(is_numeric($value))
					{
						$_SESSION['error'][$name] = $name . ' cannot contain numbers';
					}
				break;
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL))
					{
						$_SESSION['error'][$name] = $name . ' is not a valid email';
					}
				break;
				case 'password':
					$password = $value;
					if(strlen($value) < 5)
					{
						$_SESSION['error'][$name] = $name . ' must be greater than 5 characters';
					}
				break;
				case 'confirm_password':
					if($password != $value)
					{
						$_SESSION['error'][$name] = 'Passwords do not match';
					}
				break;
			}
		}	
	}


	if(!isset($_SESSION['error']))
	{
		$_SESSION['success_message'] = "Congratulations you are now a member!";

		$salt = bin2hex(genRand(22)); // creates a random 22 character hexidecimal value
		$hash = crypt($post['password'], $salt); //uses $salt to create an encrypted password **THIS IS THE B-CRYPT Method one of the MOST SECURE WAYS**

		$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
				  VALUES('".$post['first_name']."', '".$post['last_name']."', '".$post['email']."', '".$hash."', NOW(), NOW())";
				  // echo $query;
				  // die;
		run_mysql_query($query);
		// die;

		$user_id = mysqli_insert_id($connection); //gives us the user id that was just created so we don't have to re-query
		
		$_SESSION['user_id'] = $user_id; //sets session equal to that user
		

		header('Location: profile.php?id='.$user_id); //GET method to grab the user id and link it to that specific user's profile page.
		exit;

	}
}

function post_message($connection, $post )
{
	// var_dump($post);
	// die();
	if(!empty($post['post']))
	{
		$insert_post_query = "INSERT INTO messages (user_id, message, created_at) VALUES ('". $_SESSION['user_id'] ."', '". $post['post'] ."', NOW()) ";
		$insert_post = run_mysql_query($insert_post_query);
		

		if($insert_post == TRUE)
			$_SESSION['notifications'][] = "New post inserted!";
		else
			$_SESSION['errors'][] = "Cannot post right now. Please check database connection.";
	}
	else
		$_SESSION['errors'][] = "Post field must not be empty!";

	header('Location: profile.php?id=' . $post['user_id'] );
	exit;
}

function comment_post()
{
	if(!empty($_POST['comment']))
	{
		$insert_comment_query = "INSERT INTO comments (user_id, message_id, comment, created_at) VALUES('". $_SESSION['user_id'] ."', '". $_POST['message_id'] ."', '". $_POST['comment'] ."', NOW()) ";
		$insert_comment = run_mysql_query($insert_comment_query);
		// echo $insert_comment_query;
		// die;

		if($insert_comment == TRUE)
			$_SESSION['notifications'][] = "New comment inserted";
		else
			$_SESSION['errors'][] = "Cannot comment right now. Please check database connection.";
	}
	else
		$_SESSION['errors'][] = "Comment field must not be empty!";

	header('Location: profile.php?id=' .$_SESSION['user_id'] );
	exit();
	}

function login($connection, $post)
{
	if(empty($post['email']) || empty($post['password']))
	{
		$_SESSION['error']['message'] = "Email or Password cannot be blank";
	}
	else
	{
		$query = "SELECT id, password
				  FROM users
				  WHERE email = '".$post['email']."'";
		$row = fetch_record($query);
		

		if(empty($row))
		{
			$_SESSION['error']['message'] = 'Could not find Email in database.. =[   Please Register';
		}
		else
		{
			if(crypt($post['password'], $row['password']) != $row['password'])
			{
				$_SESSION['error']['message'] = 'Incorrect Password';
			}
			else
			{
				$_SESSION['user_id'] = $row['id'];
				header('Location: profile.php?id='.$row['id']);
				exit;
			}
		}
	}
	header('Location: index.php');
	exit;
}

if(isset($_GET['logout']))
{
	logout();
}
if(isset($_POST['action']) && $_POST['action'] == 'register')
{
	register($connection, $_POST);
}
if(isset($_POST['action']) && $_POST['action'] == 'login')
{
	login($connection, $_POST);
}
if(isset($_POST['action']) && $_POST['action'] == 'post')
{
	post_message($connection, $_POST);
}
if(isset($_POST['action']) && $_POST['action'] == 'comment')
{
	comment_post($connection, $_POST);
}


header('Location: index.php');

?>