<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/media/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>

    </style>
    <link href="/media/css/main.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar">Sign in</a>
          <a class="brand" href="/">Cebu Coliseum Online Ticketing</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <!--<li class="active"><a href="/">Home</a></li> -->
              <li><a href="/">Home</a></li>
              <li><a href="account">My Account</a></li>
              <li><a href="#">Contact Us</a></li>
            </ul>
			{userControl}
          </div><!--/.nav-collapse -->

        </div>
      </div>
    </div>
	<div class="container">