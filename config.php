<?php
session_start();


define("host", "localhost");
define("dbuser", 'root');
define("dbpassword", "");
define("dbname", "tampos");





$conn = mysqli_connect(host, dbuser, dbpassword, dbname) or die();
