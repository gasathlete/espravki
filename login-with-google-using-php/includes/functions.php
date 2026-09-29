<?php
class Users {
	public $tableName = 'customers';
	
	function __construct(){
		//database configuration
		$dbServer = 'localhost'; //Define database server host
		$dbUsername = 'infojnug_espravkiUser'; //Define database username
		$dbPassword = 'UR[I*&I4?8_W'; //Define database password
		$dbName = 'infojnug_espravki'; //Define database name
		
		
		//connect databse
		$con = mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbName);
		if(mysqli_connect_errno()){
			die("Failed to connect with MySQL: ".mysqli_connect_error());
		}else{
			$this->connect = $con;
		}
	}
	
	function checkUser($oauth_provider,$oauth_uid,$fname,$lname,$email,$gender,$locale,$link,$picture){
	$query='select * from customers where customer_email="'.mysql_real_escape_string($email).'" and active="1"';
	$result=mysql_query($query) or die(send_error($query,$_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"],$error=mysql_error(),$_SERVER['REMOTE_ADDR']));
	if(mysql_num_rows($result) > 0){
		//imame registriran potrebitel
		$row=mysql_fetch_assoc($result);
		$_SESSION['customer']=$row['id'];
		$_SESSION['customer_names']=$row['customer_names'];
		$_SESSION['customer_email']=$email;
		$_SESSION['customer_phone']=$row['customer_phone'];
	
		header('Location:'.WebSite);
		exit(0);
			//$update = mysqli_query($this->connect,"UPDATE $this->tableName SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fname = '".$fname."', lname = '".$lname."', email = '".$email."', gender = '".$gender."', locale = '".$locale."', picture = '".$picture."', gpluslink = '".$link."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'") or die(mysqli_error($this->connect));
		}else{
		$_SESSION['customer_names'] = $fname.' '.$lname;
		$_SESSION['customer_email'] = $email;
		//var_dump($_SESSION);
		header('Location:'.WebSite.'/userlogins/index.php?showform=profile');
		exit(0);
			//$insert = mysqli_query($this->connect,"INSERT INTO $this->tableName SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$oauth_uid."', fname = '".$fname."', lname = '".$lname."', email = '".$email."', gender = '".$gender."', locale = '".$locale."', picture = '".$picture."', gpluslink = '".$link."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'") or die(mysqli_error($this->connect));
		exit();
		}
		
		$query = mysqli_query($this->connect,"SELECT * FROM $this->tableName WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'") or die(mysqli_error($this->connect));
		$result = mysqli_fetch_array($query);
		return $result;
	}
}
?>