<?php

session_start();
session_destroy();

header("location: " . str_replace("Sair.php", "", $_SERVER["SCRIPT_NAME"]));