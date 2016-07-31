<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>WRAM : Wer radelt am meisten?</title>


	<link href="/css/radel.reset.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.structure.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.theme.min.css" rel="stylesheet" />
    <link href="/css/radel.style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <base href="https://<?= $server ?>" />
  </head>
  <body class="<?= $body_class ?>">
	  
	<?= $navigation ?>
	
	<div class="container" id="container">
    
	<?= $content ?>

	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/radel/js/bootstrap.min.js"></script>
    <script src="/radel/js/Chart.min.js"></script> 
    <script src="/radel/js/jquery-ui.min.js"></script> 
    <script src="/radel/js/jquery.plugins.js"></script>
    <script src="/radel/js/jquery.radel.main.js"></script>
    <script src="/radel/js/jquery.radel.charts.js"></script>
  </body>
</html>
