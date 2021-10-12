<?php

declare(strict_types=1);

namespace App\Api\V1\Transformers;

use App\Models\Employee as DataDb;
use App\Traits\TransformerTrait;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    use TransformerTrait;

    public $type = 'employee';

    public $url = 'employee';

    protected $availableIncludes = [
        'bank',
        'position',
        'regency',
    ];

    /**
     * transform function
     *
     * @param InboxType $data
     * @return array
     */
    public function transform(DataDb $data): array
    {
        $linkSelf = $this->getLinkSelf($this->url, $data->uuid);

        return [
            'id' => (string) $data->uuid,
            'createdAt' => (string) $data->created_at,
            'updatedAt' => (string) $data->updated_at,
            'firstName' => (string) $data->first_name,
            'lastName' => (string) $data->last_name,
            'dateOfBirth' => (string) $data->date_of_birth,
            'email' => (string) $data->email,
            'phone' => (string) $data->phone,
            'address' => (string) $data->address,
            'zipCode' => (string) $data->zip_code,
            'ktp' => (string) $data->ktp,
            'ktpImage' => (string) $data->ktp_image,
            'ktpImageUrl' => (string) $data->ktp_image_url,
            'bankAccount' => (string) $data->bank_account,
            'links' => [
                'self' => $linkSelf,
            ],
        ];
    }

    public function includerBank(DataDb $data): ?object
    {
        if (isset($data) && isset($data->bank)) {
            return $this->item($data->bank, new BankTransformer(), 'bank');
        }

        return $this->null();
    }

    public function includerPosition(DataDb $data): ?object
    {
        if (isset($data) && isset($data->position)) {
            return $this->item($data->position, new RegencyTransformer(), 'position');
        }

        return $this->null();
    }

    public function includerRegency(DataDb $data): ?object
    {
        if (isset($data) && isset($data->regency)) {
            return $this->item($data->regency, new RegencyTransformer(), 'regency');
        }

        return $this->null();
    }
}
