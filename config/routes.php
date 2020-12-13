<?php

return [
    '/' => function() {
        return view('welcome');
    },
    'form' => function() {
        return view('form');
    },
    'action' => "POST:UserController@action"
];
