   <?php
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
<html lang="en">
<head>
	<title>.:: Welcome to iPOS ::.</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/droplinebar.css">
	
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>



	<script src="js/droplinemenu.js" type="text/javascript"></script>
	<script type="text/javascript">

		//build menu with DIV ID="myslidemenu" on page:
droplinemenu.buildmenu("mydroplinemenu")
	</script>
</head>

<body>
<div class="ch-container">
    <div class="row">
        
    <div class="row">
        <div class="col-md-12 center login-header">
            <h2>Welcome to iPos</h2>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                Please login with your Username and Password.
            </div>
            <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="clearfix"></div>

                    <div class="input-prepend">
                        <label class="remember" for="remember"><input type="checkbox" id="remember"> Remember me</label>
                    </div>
                    <div class="clearfix"></div>

                    <p class="center col-md-5">
                        <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    </p>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<!-- external javascript -->
<?php include("includes/footer.php");?>
