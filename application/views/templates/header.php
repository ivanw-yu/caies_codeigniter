<!DOCTYPE html>
<html>
  <head>
    <title> CAIES </title>
    <meta charset  = "UTF-8">
    <base href = "<?php echo base_url(); ?>"> <!-- development -->
    <!--<base href = "/"> Production-->

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css">
    <script type = "text/javascript" src = "assets/scripts/jquery/jquery-2.2.4.js"></script>
    <script src= "assets/css/bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,‌​100italic,300,300ita‌​lic,400italic,500,50‌​0italic,700,700itali‌​c,900italic,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">



    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css'); ?>">
    <script type = "text/javascript" src = "<?php echo base_url('assets/scripts/jquery/jquery-2.2.4.js'); ?>"></script>
    <script src= "<?php echo base_url('assets/css/bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js'); ?>"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,‌​100italic,300,300ita‌​lic,400italic,500,50‌​0italic,700,700itali‌​c,900italic,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/styles.css'); ?>"> -->

    <!-- <link rel="stylesheet" type="text/css" href="<?php echo APPPATH . '../assets/css/bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css'; ?>">
    <script type = "text/javascript" src = "<?php echo APPPATH . '../assets/scripts/jquery/jquery-2.2.4.js'; ?>"></script>
    <script src= "<?php echo APPPATH . '../assets/css/bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js'; ?>"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,‌​100italic,300,300ita‌​lic,400italic,500,50‌​0italic,700,700itali‌​c,900italic,900' rel='stylesheet' type='text/css'>
    <script src = "./scripts/urlparser.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <?php
      echo APPPATH . '../assets/css/bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css';
    ?> -->
    <!-- <script src = "./routes/routes.js"></script>
    <script>
      $( document ).ready(function() {
        $("#single-page").load("./views/" + mapUrl());
        window.addEventListener('hashchange', function(){
          console.log("it changl");
          // $("#single-page").load("./views/" + mapUrl());
          $.ajax({url: "./views/" + mapUrl(), success: function(result){
            $("#single-page").html(result);
          }});
            var hash = location.hash.replace( /#/g, '' );
        });
      });
    </script> -->
    <!-- THIS IS FOR LOCAL ENV ONLY -->
    <!-- <base href="/caies/"> -->
    <!-- !!!!!!CHANGE TO <base href="/"> FOR PRODUCTION -->
  </head>
  <body>
    <body>

    <ul class = "contact-info">
      <li> caies@caies.org |</li>
      <li> 415----------- |</li>
      <li> Join us </li>
    </ul>
    <div class = "img-area">
      CAIES
    </div>


    <nav id = "navbar-id-for-height-compare" class = "nav navbar-inverse"> <!--style = "position : fixed; width: 100%; top: 0; z-index: 10">-->
        <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">CAIES</a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-left">
            <li><a href= "#"> Home </a></li>
            <?php if(isset($_SESSION['username'])){ ?>
              <li><a href = "dashboard">Dashboard</a></li>
            <?php } ?>
            <li><a href = "news">News</a></li>
            <li><a href = "photos">Photos</a></li>
            <li><a href = "board">Board</a></li>
            <li><a href = "questions">Questions</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php
              if(!isset($_SESSION['username'])){
                // echo $_SESSION['user'];
            ?>
                <li id = "register">
                    <a href = "register">Register</a></li>
                <li id = "login" >
                    <a href = "login">Login</a>
                </li>
            <?php
              }else{
            ?>
                <li id = "logout">
                    <a href = "logout">Logout</a>
                </li>
            <?php
              }
            ?>
          </ul>
        </div>
      </div>
    </nav>
