<?php

$route[] = ['/user/create', 'UserController@create'];
$route[] = ['/user/store', 'UserController@store'];

$route[] = ['/login', 'UserController@login'];
$route[] = ['/login/auth', 'UserController@auth'];
$route[] = ['/logout', 'UserController@logout'];

$route[] = ['/', 'HomeController@index'];

$route[] = ['/products', 'ProductsController@index'];
$route[] = ['/product/{id}/show', 'ProductsController@show'];
$route[] = ['/product/create', 'ProductsController@create', 'auth'];
$route[] = ['/product/store', 'ProductsController@store', 'auth'];
$route[] = ['/product/{id}/edit', 'ProductsController@edit', 'auth'];
$route[] = ['/product/{id}/update', 'ProductsController@update', 'auth'];
$route[] = ['/product/{id}/delete', 'ProductsController@delete', 'auth'];

$route[] = ['/categorys', 'CategorysController@index'];
$route[] = ['/category/{id}/show', 'CategorysController@show'];
$route[] = ['/category/create', 'CategorysController@create', 'auth'];
$route[] = ['/category/store', 'CategorysController@store', 'auth'];
$route[] = ['/category/{id}/edit', 'CategorysController@edit', 'auth'];
$route[] = ['/category/{id}/update', 'CategorysController@update', 'auth'];
$route[] = ['/category/{id}/delete', 'CategorysController@delete', 'auth'];


return $route;