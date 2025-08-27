<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/categoria', 'Categoria::index');
$routes->get('/categoria/create', 'Categoria::create');
$routes->post('/categoria/store', 'Categoria::store');
$routes->get('/categoria/(:hash)/edit', 'Categoria::edit/$1');
$routes->get('/categoria/(:hash)/delete', 'Categoria::delete/$1');
$routes->post('/Ajax/Categoria/store', 'Ajax\Categoria::store');
$routes->get('/Ajax/Categoria/get', 'Ajax\Categoria::get');
$routes->get('/lancamento', 'Lancamento::index');
$routes->get('/orcamento', 'Orcamento::index');
$routes->get('/orcamento/create', 'Orcamento::create');
$routes->post('/orcamento/store', 'Orcamento::store');
$routes->get('/orcamento/(:hash)/edit', 'Orcamento::edit/$1');
$routes->get('/orcamento/(:hash)/delete', 'Orcamento::delete/$1');
$routes->get('/usuario', 'Usuario::index');
$routes->get('/perfil', 'Perfil::index');
$routes->get('/relatorio', 'Relatorio::index');
$routes->get('/mensagem/sucesso', 'Mensagem::sucesso');
$routes->get('/mensagem/erro', 'Mensagem::erro');
