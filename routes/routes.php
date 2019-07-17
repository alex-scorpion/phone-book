<?php

/**
 * Спиок маршрутов
 * @var Aura\Router\Map $routes
 */

$routes->attach('api.', '/api', function(Aura\Router\Map $routes) {
    $routes->get('contacts.list', '/contacts', "ContactsController@list");
    $routes->get('contacts.show', '/contacts/{id}', "ContactsController@show");
    $routes->post('contacts.store', '/contacts', "ContactsController@store");
    $routes->post('contacts.update', '/contacts/{id}', "ContactsController@update");
    $routes->delete('contacts.destroy', '/contacts/{id}', "ContactsController@destroy");
});