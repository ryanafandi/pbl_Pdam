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

Route :: prefix('/direktur')->group(function(){
    include "direktur/backend.php";
});


Route :: prefix('/perencanaan')->group(function(){
    include "perencanaan/backend.php";
});


Route :: prefix('/kasir')->group(function(){
    include "kasir/backend.php";
});

Route :: prefix('/trandis')->group(function(){
    include "trandis/backend.php";
});