<?php

return [
    '/' => function() {
        return view('welcome');
    },
    'est/{name}' => "GET:UserController@test"
];
