<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php 
    require_once "../config/controllerUserData.php"; 
    require "../config/session.php";
    require_once "../config/config.php";
    require 'slugify.php';
    $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);

    if($status !== "verified" && !isset($_SESSION['email']) && !isset($_SESSION['password'])){
      header("location: ../index.php");
      exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/images/logo4.png">
  <title>
    Blockchain-Based EVS
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!--Simple Notify-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.css" />
  <!--Jquery steps-->
  <link rel="stylesheet" href="../assets/css/jquery-steps.css">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
</head>
<style>
:root {
    --sw-border-color:  #eeeeee;
    --sw-toolbar-btn-color:  #ffffff;
    --sw-toolbar-btn-background-color:  #00008b;
    --sw-anchor-default-primary-color:  #f8f9fa;
    --sw-anchor-default-secondary-color:  #b0b0b1;
    --sw-anchor-active-primary-color:  #00008b;
    --sw-anchor-active-secondary-color:  #ffffff;
    --sw-anchor-done-primary-color:  #00008b;
    --sw-anchor-done-secondary-color:  #fff;
    --sw-anchor-disabled-primary-color:  #f8f9fa;
    --sw-anchor-disabled-secondary-color:  #dbe0e5;
    --sw-anchor-error-primary-color:  #dc3545;
    --sw-anchor-error-secondary-color:  #ffffff;
    --sw-anchor-warning-primary-color:  #ffc107;
    --sw-anchor-warning-secondary-color:  #ffffff;
    --sw-loader-color:  #009EF7;
    --sw-loader-background-color:  #f8f9fa;
    --sw-loader-background-wrapper-color:  rgba(255, 255, 255, 0.7);
  }
  .toolbar {
      display: flex;
  }
  .sw-btn-finish {
    display: inline-block !important;
    text-decoration: none !important;
    text-align: center !important;
    text-transform: none !important;
    vertical-align: middle !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    user-select: none !important;
    margin-left: 0.2rem !important;
    margin-right: 0.2rem !important;
    cursor: pointer !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.25rem !important;
    font-weight: 400 !important;
    font-size: 13px !important;
    color: var(--sw-toolbar-btn-color) !important;
    background-color: var(--sw-toolbar-btn-background-color);
    border: 1px solid var(--sw-toolbar-btn-background-color);
  }
  .list-group {
    display: none; /* Hide the list group by default */
  }
  #platform-content {
    min-height: 500px;
  }
  #platform-content h1, h2, h3, h4, h5, h6, p, ul, ol {
    color: #000 !important;
  }
  #loading-spinner {
    display: none;
    background-color: rgba(0, 0, 0, 0.5);
    position: absolute;
    width: 100%;
    height: 100%;
  }
  #loading-spinner img {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>

<body class="g-sidenav-show  bg-gray-200">
    <?php include("modals.php");?>
    <?php include("sidebar.php");?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include("navbar.php");?>