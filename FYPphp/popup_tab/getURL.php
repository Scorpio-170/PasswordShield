<?php
session_start();
$currentSiteUrl = $_GET['currentSiteUrl'];
$_SESSION['urlName']=$currentSiteUrl;
?>