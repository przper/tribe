<?php

require_once './vendor/autoload.php';

use Przper\Tribe\FoodRecipies\Domain\Recipie;

echo "hello world\n";

$recipie = new Recipie();

echo json_encode($recipie) . "\n";