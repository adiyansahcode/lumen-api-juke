<?php

declare(strict_types=1);

namespace App\Api\V1\Transformers;

use App\Models\Province as DataDb;
use App\Traits\TransformerTrait;
use League\Fractal\TransformerAbstract;

class ProvinceTransformer extends TransformerAbstract
{
    use TransformerTrait;

    public $type = 'province';

    public $url = 'province';

    protected $availableIncludes = [
        'regency'
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

    public function includeRegency(DataDb $data): ?object
    {
        if (isset($data) && isset($data->regency)) {
            return $this->collection($data->regency, new RegencyTransformer(), 'regency');
        }

        return $this->null();
    }
}
