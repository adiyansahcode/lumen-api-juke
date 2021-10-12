<?php

declare(strict_types=1);

$api->group(
    [
        'prefix' => 'province'
    ],
    function ($api) {
        $api->get('/', [
            'as' => 'province.index',
            'uses' => 'App\Api\V1\Controllers\ProvinceController@index',
        ]);
        $api->get('/{id}', [
            'as' => 'province.show',
            'uses' => 'App\Api\V1\Controllers\ProvinceController@show',
        ]);
        $api->get('/{id}/relationships/regency', [
            'as' => 'province.self.regency',
            'uses' => 'App\Api\V1\Controllers\ProvinceController@showSelfRegency',
        ]);
        $api->get('/{id}/regency', [
            'as' => 'province.related.regency',
            'uses' => 'App\Api\V1\Controllers\ProvinceController@showRelatedRegency',
        ]);
    }
);
