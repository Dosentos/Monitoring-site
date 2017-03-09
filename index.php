<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.04
 */
ob_start();
session_start();
require_once'config.php';
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Projekti 1, sivusto</title>

        <!-- Latest BOOTSTRAP minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- Latest BOOTSTRAP JC-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- GOOGLE CHARTS visualization -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="css/ulkoasu.css"/>

        <!-- Custom Font LATO -->
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    </head>
<body>
<?php
include 'header.php';
if(isset($_SESSION['user'])){
    include 'navigation.php';
}
?>
    <div id="content" class="container-fluid">
<?php

if(!isset($_SESSION['user'])){
    if($_GET['page'] == 'register'){
        include 'register.php';
    }
    else{
        include 'login.php';
    }
}
else{
    $found = false;

    if(isset($_GET['page'])){
        print_r($content);
        foreach($content as $page){
            if($page['page_id'] == $_GET['page']){
                $found = true;
                include $page['include'];
            }
        }
    }
    if (!$found){
        include 'home_content.php';
    }
}
include 'footer.php';
?>