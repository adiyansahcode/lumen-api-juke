<?php

declare(strict_types=1);

namespace App\Api\V1\Validations;

use Illuminate\Support\Facades\Validator;

class ProvinceValidation
{
    public function idValidation(array $request, string $type): void
    {
        $validator = Validator::make($request, [
            'id' => [
                'required',
                'filled',
                'numeric',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg = $validator->errors()->toArray();
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request, [
            'id' => [
                'exists:App\Models\Province,id',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'id resource is not exists.',
                'source' => ['parameter' => 'id'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }
    }
}
