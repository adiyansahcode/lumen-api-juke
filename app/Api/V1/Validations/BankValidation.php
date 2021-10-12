<?php

declare(strict_types=1);

namespace App\Api\V1\Validations;

use Illuminate\Support\Facades\Validator;

class BankValidation
{
    public function uuidValidation(array $request, string $type): void
    {
        $validator = Validator::make($request, [
            'uuid' => [
                'required',
                'filled',
                'string',
                'uuid',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'uuid is invalid format.',
                'source' => ['parameter' => 'uuid'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request, [
            'uuid' => [
                'exists:App\Models\Bank,uuid',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'uuid resource is not exists.',
                'source' => ['parameter' => 'uuid'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }
    }
}
