<?php
// session_start();
//define constants for db_host, db_user, db_pass, and db_database
//adjust the values below to match your database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); //set DB_PASS as 'root' if you're using mac
define('DB_DATABASE', 'wall'); //make sure to set your database


//connect to database host
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

//used when expecting multiple results
function fetch_all($query)
{
	$data = array();
	global $connection;
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_assoc($result))
	{
		$data[] = $row;
	}
	return $data;
}

//use when expecting a single result
function fetch_record($query)
{
	global $connection;
	$result = mysqli_query($connection, $query);
	return mysqli_fetch_assoc($result);
}

//use to run INSERT/DELETE/UPDATE, queries that don't return a value
function run_mysql_query($query)
{
	global $connection;
	$result = mysqli_query($connection, $query);
	return $result;
}

?>