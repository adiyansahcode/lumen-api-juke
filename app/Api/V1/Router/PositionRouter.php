<?php

declare(strict_types=1);

$api->group(
    [
        'prefix' => 'position'
    ],
    function ($api) {
        $api->get('/', [
            'as' => 'position.index',
            'uses' => 'App\Api\V1\Controllers\PositionController@index',
        ]);
        $api->get('/{uuid}', [
            'as' => 'position.show',
            'uses' => 'App\Api\V1\Controllers\PositionController@show',
        ]);
    }
);
