<?php

declare(strict_types=1);

namespace App\Api\V1\Validations;

use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeValidation
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
                'exists:App\Models\Employee,uuid',
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

    public function createValidation(object $request, string $type): void
    {
        // validate data
        $validator = Validator::make($request->json()->all(), [
            'data' => [
                'required',
                'array',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => "missing 'data' parameter at request.",
                'source' => ['pointer' => ''],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        // validate attributes
        $validator = Validator::make($request->json('data'), [
            'attributes' => [
                'required',
                'array',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => "missing 'attributes' parameter at request.",
                'source' => ['pointer' => ''],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        // validate data type
        $validator = Validator::make($request->json('data'), [
            'type' => [
                'required',
                'filled',
                'string',
                Rule::in([$type]),
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'type resource is invalid.',
                'source' => ['pointer' => 'data/type'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request->json('data'), [
            'id' => [
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
                'detail' => 'id resource is invalid format.',
                'source' => ['pointer' => 'data/id'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request->json('data'), [
            'id' => [
                Rule::unique('App\Models\Employee', 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'id resource is not exists.',
                'source' => ['pointer' => 'data/id'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request->json('data.attributes'), [
            'firstName' => [
                'required',
                'filled',
                'string',
                'between:2,100'
            ],
            'lastName' => [
                'required',
                'filled',
                'string',
                'between:2,100'
            ],
            'dateOfBirth' => [
                'filled',
                'date',
                'date_format:Y-m-d',
            ],
            'email' => [
                'filled',
                'string',
                'email:rfc,dns',
                Rule::unique('App\Models\Employee', 'email')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = Employee::whereRaw('LOWER(email) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
                'max:100'
            ],
            'phone' => [
                'required',
                'filled',
                'numeric',
                Rule::unique('App\Models\Employee', 'phone')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'address' => [
                'filled',
                'string',
            ],
            'zipCode' => [
                'filled',
                'string',
            ],
            'ktp' => [
                'required',
                'filled',
                'numeric',
                Rule::unique('App\Models\Employee', 'ktp')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'bankAccount' => [
                'required',
                'filled',
                'numeric',
            ],
            'image' => [
                'filled',
                'string'
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg = $validator->errors()->toArray();
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 422);
        }

        if ($request->has('data.relationships')) {
            $validator = Validator::make($request->json('data.relationships.bank.data'), [
                'id' => [
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
                    'detail' => 'bank id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/bank/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.bank.data'), [
                'id' => [
                    'exists:App\Models\Bank,uuid',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'bank is not exists.',
                    'source' => ['pointer' => 'data/relationships/bank/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.position.data'), [
                'id' => [
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
                    'detail' => 'position id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/position/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.position.data'), [
                'id' => [
                    'exists:App\Models\Position,uuid',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'position is not exists.',
                    'source' => ['pointer' => 'data/relationships/position/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.regency.data'), [
                'id' => [
                    'filled',
                    'string',
                    'numeric',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'regency id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/regency/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.regency.data'), [
                'id' => [
                    'exists:App\Models\Regency,id',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'regency is not exists.',
                    'source' => ['pointer' => 'data/relationships/regency/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }
        }
    }

    public function updateValidation(object $request, string $type): void
    {
        // validate data
        $validator = Validator::make($request->json()->all(), [
            'data' => [
                'required',
                'array',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => "missing 'data' parameter at request.",
                'source' => ['pointer' => ''],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        // validate attributes
        $validator = Validator::make($request->json('data'), [
            'attributes' => [
                'required',
                'array',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => "missing 'attributes' parameter at request.",
                'source' => ['pointer' => ''],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        // validate data type
        $validator = Validator::make($request->json('data'), [
            'type' => [
                'required',
                'filled',
                'string',
                Rule::in([$type]),
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'type resource is invalid.',
                'source' => ['pointer' => 'data/type'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request->json('data'), [
            'id' => [
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
                'detail' => 'id resource is invalid format.',
                'source' => ['pointer' => 'data/id'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $validator = Validator::make($request->json('data'), [
            'id' => [
                'exists:App\Models\Employee,uuid',
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '400',
                'code' => '400',
                'title' => 'invalid request',
                'detail' => 'id resource is invalid format.',
                'source' => ['pointer' => 'data/id'],
            ];
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
        }

        $uuid = $request->json('data.id');

        $validator = Validator::make($request->json('data.attributes'), [
            'firstName' => [
                'filled',
                'string',
                'between:2,100'
            ],
            'lastName' => [
                'filled',
                'string',
                'between:2,100'
            ],
            'dateOfBirth' => [
                'filled',
                'date',
                'date_format:Y-m-d',
            ],
            'email' => [
                'filled',
                'string',
                'email:rfc,dns',
                Rule::unique('App\Models\Employee', 'email')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) use ($uuid) {
                    // check unique case sensitive
                    $data = Employee::where('uuid', '<>', $uuid)->whereRaw('LOWER(email) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
                'max:100'
            ],
            'phone' => [
                'filled',
                'numeric',
                Rule::unique('App\Models\Employee', 'phone')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'address' => [
                'filled',
                'string',
            ],
            'zipCode' => [
                'filled',
                'string',
            ],
            'ktp' => [
                'filled',
                'numeric',
                Rule::unique('App\Models\Employee', 'ktp')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'bankAccount' => [
                'filled',
                'numeric',
            ],
            'image' => [
                'filled',
                'string'
            ],
        ]);
        if ($validator->fails()) {
            $errorMsg = $validator->errors()->toArray();
            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 422);
        }

        if ($request->has('data.relationships')) {
            $validator = Validator::make($request->json('data.relationships.bank.data'), [
                'id' => [
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
                    'detail' => 'bank id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/bank/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.bank.data'), [
                'id' => [
                    'exists:App\Models\Bank,uuid',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'bank is not exists.',
                    'source' => ['pointer' => 'data/relationships/bank/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.position.data'), [
                'id' => [
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
                    'detail' => 'position id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/position/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.position.data'), [
                'id' => [
                    'exists:App\Models\Position,uuid',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'position is not exists.',
                    'source' => ['pointer' => 'data/relationships/position/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.regency.data'), [
                'id' => [
                    'filled',
                    'string',
                    'numeric',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'regency id resource is invalid format.',
                    'source' => ['pointer' => 'data/relationships/regency/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }

            $validator = Validator::make($request->json('data.relationships.regency.data'), [
                'id' => [
                    'exists:App\Models\Regency,id',
                ],
            ]);
            if ($validator->fails()) {
                $errorMsg['errors'][] = [
                    'id' => (int) mt_rand(1000, 9999),
                    'status' => '400',
                    'code' => '400',
                    'title' => 'invalid request',
                    'detail' => 'regency is not exists.',
                    'source' => ['pointer' => 'data/relationships/regency/data/id'],
                ];
                throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 400);
            }
        }
    }
}
