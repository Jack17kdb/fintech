<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\WalletController;
use App\Controllers\TransferController;
use App\Controllers\DepositController;
use App\Controllers\WithdrawController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/register', 'AuthController::registerForm');
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/register', 'AuthController::register');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes){
	$routes->get('/wallet', 'WalletController::dashboard');
	$routes->get('/wallet/transactions', 'WalletController::transactions');
	$routes->get('/wallet/transaction/(:num)', 'WalletController::transactionDetail/$1');
	$routes->get('/wallet/transfer', 'TransferController::transferForm');
	$routes->post('/wallet/transfer', 'TransferController::transfer');
	$routes->get('/wallet/deposit', 'DepositController::depositForm');
	$routes->post('/wallet/deposit', 'DepositController::deposit');
	$routes->get('/wallet/withdraw', 'WithdrawController::withdrawForm');
        $routes->post('/wallet/withdraw', 'WithdrawController::withdraw');
});
