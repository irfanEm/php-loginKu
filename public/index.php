<?php


require_once __DIR__ . "/../vendor/autoload.php";
use PROGAMERANYARAN\PHP\LOGIN\App\Route;
use PROGAMERANYARAN\PHP\LOGIN\Controller\HomeController;

Route::route("GET","/", HomeController::class, "index", []);
Route::route("GET", "/contoh", HomeController::class,"contoh", []);
Route::route("GET","/tentang", HomeController::class, "tentang", []);

Route::gas();