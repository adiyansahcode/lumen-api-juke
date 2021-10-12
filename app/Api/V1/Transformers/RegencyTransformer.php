<?php

declare(strict_types=1);

namespace App\Api\V1\Transformers;

use App\Models\Regency as DataDb;
use App\Traits\TransformerTrait;
use League\Fractal\TransformerAbstract;

class RegencyTransformer extends TransformerAbstract
{
    use TransformerTrait;

    public $type = 'regency';

    public $url = 'regency';

    protected $availableIncludes = [
        'province',
    ];

    /**
     * transform function
     *
     * @param DataDb $data
     * @return array
     */
    public function transform(DataDb $data): array
    {
        $linkSelf = $this->getLinkSelf($this->url, (string) $data->id);

        return [
            'id' => (string) $data->id,
            'name' => (string) $data->name,
            'altName' => (string) $data->alt_name,
            'latitude' => (float) $data->latitude,
            'longitude' => (float) $data->longitude,
            'links' => [
                'self' => $linkSelf,
            ],
        ];
    }

    public function includeProvince(DataDb $data): ?object
    {
        if (isset($data) && isset($data->province)) {
            return $this->item($data->province, new ProvinceTransformer(), 'province');
        }

        return $this->null();
    }
}
