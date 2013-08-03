<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wunderdashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/signin.css" rel="stylesheet">
    <link href="css/fixed-footer.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="dashboard.php" method="post">
      	
		<?php if ($_GET['f'] == 'y') { ?>
		<span class="label label-warning">Credentials were incorrect.</span>
		<?php } ?>
      
        <h2 class="form-signin-heading">Wunderdashboard</h2>
        <input name="email" type="text" class="input-block-level" placeholder="Email address" autofocus>
        <input name="pass" type="password" class="input-block-level" placeholder="Password">

        <button class="btn btn-large btn-primary btn-block" type="submit">Sign in</button>
        
        <p>Please note, that this is not official Wunderlist! Wunderdashboard is using the unofficial API <a href="https://github.com/PENDOnl/Wunderlist2-PHP-Wrapper">Wunderlist2-PHP-Wrapper</a> by Joshua de Gier. We do not store your credentials! </p>
      </form>

    </div> <!-- /container -->
    
<?php include("footer.php"); ?>

  </body>
</html>