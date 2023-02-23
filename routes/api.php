<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register", [AuthController::class, "register"])->name("register");
Route::post("login", [AuthController::class, "login"])->name("login");

Route::middleware("auth:api")->group(function () {

    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::get("/getProfile", [AuthController::class, "getProfile"])->name("get_profile");

    Route::prefix("customer")->group(function() {
        Route::get("/", [CustomerController::class, "getCustomers"])->name("get_customers");
        Route::get("/{id}", [CustomerController::class, "getCustomerDetails"])->name("get_customer");
        Route::post("/create", [CustomerController::class, "create"])->name("create_customer");
        Route::post("/edit/{id}", [CustomerController::class, "edit"])->name("edit_customer");
        Route::delete("/delete/{id}", [CustomerController::class, "delete"])->name("delete_customer");
    });

    Route::prefix("product")->group(function() {
        Route::get("/", [ProductController::class, "getProducts"])->name("get_products");
        Route::get("/{id}", [ProductController::class, "getProduct"])->name("get_product");
        Route::post("/create", [ProductController::class, "create"])->name("create_product");
        Route::post("/edit/{id}", [ProductController::class, "edit"])->name("edit_product");
        Route::delete("/delete/{id}", [ProductController::class, "delete"])->name("delete_product");
    });

    Route::prefix("invoice")->group(function() {
        Route::get("/", [InvoiceController::class, "getInvoices"])->name("get_invoices");
        Route::get("/{id}", [InvoiceController::class, "getInvoice"])->name("get_invoice");
        Route::post("/create", [InvoiceController::class, "create"])->name("create_invoice");
        Route::post("/edit/{id}", [InvoiceController::class, "edit"])->name("edit_invoice");
        Route::delete("/delete/{id}", [InvoiceController::class, "delete"])->name("delete_invoice");
    });
});