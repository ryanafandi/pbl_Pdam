<?php

use Illuminate\Support\Facades\Route;

Route :: prefix('backend')->group(function(){
    include "_/backend.php";
});

Route :: prefix('/')->group(function(){
    include "_/frontend.php";
});

Route :: prefix('/admin')->group(function(){
    include "admin/backend.php";
});