<?php

declare(strict_types=1);

$api->group(
    [
        'prefix' => 'bank'
    ],
    function ($api) {
        $api->get('/', [
            'as' => 'bank.index',
            'uses' => 'App\Api\V1\Controllers\BankController@index',
        ]);
        $api->get('/{uuid}', [
            'as' => 'bank.show',
            'uses' => 'App\Api\V1\Controllers\BankController@show',
        ]);
    }
);
