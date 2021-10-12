<?php

declare(strict_types=1);

$api->group(
    [
        'prefix' => 'employee'
    ],
    function ($api) {
        $api->get('/', [
            'as' => 'employee.index',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@index',
        ]);
        $api->get('/{uuid}', [
            'as' => 'employee.show',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@show',
        ]);
        $api->post('/', [
            'middleware' => [
                'jsonValidation'
            ],
            'as' => 'employee.create',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@create',
        ]);
        $api->put('/{uuid}', [
            'middleware' => [
                'jsonValidation'
            ],
            'as' => 'employee.update',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@update',
        ]);
        $api->patch('/{uuid}', [
            'middleware' => [
                'jsonValidation'
            ],
            'as' => 'employee.update',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@update',
        ]);
        $api->delete('/{uuid}', [
            'as' => 'employee.delete',
            'uses' => 'App\Api\V1\Controllers\EmployeeController@delete',
        ]);
    }
);
