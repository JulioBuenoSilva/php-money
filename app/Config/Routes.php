<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/categoria', 'Categoria::index');
$routes->get('/categoria/index', 'Categoria::index');
$routes->get('/categoria/create', 'Categoria::create');
$routes->post('/categoria/store', 'Categoria::store');
$routes->get('/categoria/(:hash)/edit', 'Categoria::edit/$1');
$routes->get('/categoria/(:hash)/delete', 'Categoria::delete/$1');
$routes->post('/Ajax/Categoria/store', 'Ajax\Categoria::store');
$routes->get('/Ajax/Categoria/get', 'Ajax\Categoria::get');
$routes->get('/lancamento', 'Lancamento::index');
$routes->get('/lancamento/index', 'Lancamento::index');
$routes->post('/lancamento', 'Lancamento::index');
$routes->post('/lancamento/index', 'Lancamento::index');
$routes->get('/lancamento/index/(:hash)/(:hash)', 'Lancamento::index/$1/$2');
$routes->get('/lancamento/(:hash)/edit', 'Lancamento::edit/$1');
$routes->get('/lancamento/(:hash)/delete', 'Lancamento::delete/$1');
$routes->get('/lancamento/(:hash)/(:hash)', 'Lancamento::index/$1/$2');
$routes->post('/lancamento/index/(:hash)/(:hash)', 'Lancamento::index/$1/$2');
$routes->post('/lancamento/(:hash)/(:hash)', 'Lancamento::index/$1/$2');
$routes->get('/lancamento/create', 'Lancamento::create');
$routes->post('/lancamento/store', 'Lancamento::store');
$routes->get('/orcamento', 'Orcamento::index');
$routes->get('/orcamento/index', 'Orcamento::index');
$routes->get('/orcamento/create', 'Orcamento::create');
$routes->post('/orcamento/store', 'Orcamento::store');
$routes->get('/orcamento/(:hash)/edit', 'Orcamento::edit/$1');
$routes->get('/orcamento/(:hash)/delete', 'Orcamento::delete/$1');
$routes->get('/usuario', 'Usuario::index');
$routes->get('/usuario/create', 'Usuario::create');
$routes->post('/usuario/store', 'Usuario::store');
$routes->get('/usuario/(:hash)/edit', 'Usuario::edit/$1');
$routes->get('/usuario/(:hash)/delete', 'Usuario::delete/$1');
$routes->get('/usuario/googleAuth', 'Usuario::googleAuth');
$routes->post('/usuario/storeGoogleAuth', 'Usuario::storeGoogleAuth');
$routes->get('/usuario/desativaAuth2Fatores', 'Usuario::desativaAuth2Fatores');
$routes->get('/usuario/createBackupCodes', 'Usuario::createBackupCodes');
$routes->post('/ajax/usuario/storeFoto', 'Ajax\Usuario::storeFoto');
$routes->get('usuario/getFoto/(:segment)', 'Usuario::getFoto/$1');
$routes->get('/perfil', 'Perfil::index');
$routes->get('/perfil/(:hash)/edit', 'Perfil::edit/$1');
$routes->get('/perfil/(:hash)/delete', 'Perfil::delete/$1');
$routes->post('/perfil/store', 'Perfil::store');
$routes->get('/relatorio', 'Relatorio::index');
$routes->get('/relatorio/getDados', 'Relatorio::getDados');
$routes->get('/admin', 'Admin\Home::index');
$routes->get('/admin/home', 'Admin\Home::index');
$routes->get('/admin/pagina', 'Admin\Pagina::index');
$routes->get('/admin/pagina/create', 'Admin\Pagina::create');
$routes->post('/admin/pagina/store', 'Admin\Pagina::store');
$routes->get('/admin/pagina/(:hash)/edit', 'Admin\Pagina::edit/$1');
$routes->get('/admin/pagina/(:hash)/delete', 'Admin\Pagina::delete/$1');
$routes->get('/admin/usuario', 'Admin\Usuario::index');
$routes->get('/admin/usuario/(:hash)/edit', 'Admin\Usuario::edit/$1');
$routes->get('/admin/usuario/(:hash)/delete', 'Admin\Usuario::delete/$1');
$routes->get('/mensagem/sucesso', 'Mensagem::sucesso');
$routes->get('/mensagem/erro', 'Mensagem::erro');
