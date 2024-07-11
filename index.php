<?php

use Symfony\Component\Dotenv\Dotenv;

require_once './vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load('.env');
