<?php

declare(strict_types=1);

$api->group(
    [
        'prefix' => 'regency'
    ],
    function ($api) {
        $api->get('/', [
            'as' => 'regency.index',
            'uses' => 'App\Api\V1\Controllers\RegencyController@index',
        ]);
        $api->get('/{id}', [
            'as' => 'regency.show',
            'uses' => 'App\Api\V1\Controllers\RegencyController@show',
        ]);
        $api->get('/{id}/relationships/province', [
            'as' => 'regency.self.province',
            'uses' => 'App\Api\V1\Controllers\RegencyController@showSelfProvince',
        ]);
        $api->get('/{id}/province', [
            'as' => 'regency.related.province',
            'uses' => 'App\Api\V1\Controllers\RegencyController@showRelatedProvince',
        ]);
    }
);
