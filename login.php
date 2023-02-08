<?php ob_start(); ?>
   <?php
date_default_timezone_set("Asia/Dhaka");
   include('includes/config.php');
   
   session_start();
   $msg = '';
    if(isset($_POST['submit'])){
     
      $username = mysqli_real_escape_string($con,$_POST['username']);
      $password = mysqli_real_escape_string($con,md5($_POST['password']));
    
   
      if(!empty($username) && !empty($password)){
     
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
       
        $result = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($result);
       
        if(!empty($row)){
		
          $_SESSION['id'] = $row['id'];
          $_SESSION['store_id'] = '1';
          $_SESSION['type'] = $row['usertype'];		  		  
          $_SESSION['access'] = $row['access'];		  		  
          $_SESSION['username'] = $row['username'];	
		$login_time = date('Y-m-d H:i:s');
		$ck = mysqli_query($con,"INSERT INTO `login_details`(`user_id`, `login_time`, `logout_time`, `time`, `store_id`) VALUES ('".$row['id']."','$login_time','','','1')");
		if($ck){
			$max = mysqli_fetch_array(mysqli_query($con,"select max(id)as id from login_details where 1"));
			$_SESSION['login']= $max['id']; 
		}
		
		
		
		
          ob_start();
          header("Location:index.php");
          exit();
        }else{
          $msg = '<div class="alert alert-danger" role="alert">Username/Password Wrong!!!</div>';
        }
      }else{
        $msg = '<div class="alert alert-danger" role="alert">Give Username & Password</div>';
      }
    
	}

   ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>
        .::Welcome to iPos ::.
    </title>
    <style type="text/css">
      * { box-sizing: border-box; padding:0; margin: 0; }

body {
  font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
  color:white;
  font-size:12px;
  background: ;
}
.loginlogo {
  
  width:300px;
  margin:30px auto;
  margin-bottom: 0px;
  border-radius:0.4em;
  color: #333;
  overflow:hidden;
  position:relative;
  box-shadow: 0 5px 10px 5px rgba(0,0,0,0.2);
}
.loginfooter {
  text-align: center;
  width:300px;
  margin:30px auto;
  margin-bottom: 0px;
  border-radius:0.4em;
  color: #333;
  overflow:hidden;
  position:relative;
  /*box-shadow: 0 5px 10px 5px rgba(0,0,0,0.2);*/
}
form {
  background:#111; 
  width:300px;
  margin:30px auto;
  border-radius:0.4em;
  border:1px solid #191919;
  overflow:hidden;
  position:relative;
  box-shadow: 0 5px 10px 5px rgba(0,0,0,0.2);
}

form:after {
  content:"";
  display:block;
  position:absolute;
  height:1px;
  width:100px;
  left:20%;
  background:linear-gradient(left, #111, #444, #b6b6b8, #444, #111);
  top:0;
}

form:before {
  content:"";
  display:block;
  position:absolute;
  width:8px;
  height:5px;
  border-radius:50%;
  left:34%;
  top:-7px;
  box-shadow: 0 0 6px 4px #fff;
}

.inset {
  padding:20px; 
  border-top:1px solid #19191a;
}

form h1 {
  font-size:18px;
  text-shadow:0 1px 0 black;
  text-align:center;
  padding:15px 0;
  border-bottom:1px solid rgba(0,0,0,1);
  position:relative;
}

form h1:after {
  content:"";
  display:block;
  width:250px;
  height:100px;
  position:absolute;
  top:0;
  left:50px;
  pointer-events:none;
  transform:rotate(70deg);
  -webkit-transform: rotate(70deg);
  background:linear-gradient(50deg, rgba(255,255,255,0.15), rgba(0,0,0,0));
   background-image: -webkit-linear-gradient(50deg, rgba(255,255,255,0.05), rgba(0,0,0,0)); /* For Safari */

}

label {
  color:#666;
  display:block;
  padding-bottom:9px;
}

input[type=text],
input[type=password] {
  width:100%;
  padding:8px 5px;
  background:linear-gradient(#1f2124, #27292c);
  border:1px solid #222;
  box-shadow:
    0 1px 0 rgba(255,255,255,0.1);
  border-radius:0.3em;
  margin-bottom:20px;
  color: #fff;
  font-size: 15px;
}

label[for=remember]{
  color:white;
  display:inline-block;
  padding-bottom:0;
  padding-top:5px;
}

input[type=checkbox] {
  display:inline-block;
  vertical-align:top;
}

.p-container {
  padding:0 20px 20px 20px; 
}

.p-container:after {
  clear:both;
  display:table;
  content:"";
}

.p-container span {
  display:block;
  float:left;
  color:#0d93ff;
  padding-top:8px;
}

input[type=submit] {
  padding:5px 20px;
  border:1px solid rgba(0,0,0,0.4);
  text-shadow:0 -1px 0 rgba(0,0,0,0.4);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 10px 10px rgba(255,255,255,0.1);
  border-radius:0.3em;
  background:#0184ff;
  color:white;
  float:right;
  font-weight:bold;
  cursor:pointer;
  font-size:13px;
}

input[type=submit]:hover {
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 -10px 10px rgba(255,255,255,0.1);
}

input[type=text]:hover,
input[type=password]:hover,
label:hover ~ input[type=text],
label:hover ~ input[type=password] {
  background:#27292c;
}
    </style>
</head>

<body>
<div class="loginlogo"style="text-align:center"><img src="images/logo.png"/></div>
<form  class="form-horizontal" action="" method="post">

  <h1>iPos V.2.0 Log in</h1>
  <div class="inset">
  <p>
    <label for="email">USERNAME</label>
    <input type="text" name="username" id="username">
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input type="password" name="password" id="password">
  </p>
 
  </div>
  <p class="p-container">
    <span>Forgot password ?</span>
    <input type="submit" name="submit" id="go" value="Log in">
  </p>
</form>
<div class="loginfooter">&copy;<?= DATE('y') ?> by iPos | Version 2.0<br/>
<?php include('footer_text.php'); ?>
</div>
</body>
</html>