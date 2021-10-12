<?php

declare(strict_types=1);

namespace App\Api\V1\Transformers;

use App\Models\Bank as DataDb;
use App\Traits\TransformerTrait;
use League\Fractal\TransformerAbstract;

class BankTransformer extends TransformerAbstract
{
    use TransformerTrait;

    public $type = 'bank';

    public $url = 'bank';

    protected $availableIncludes = [];

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
            'name' => (string) $data->name,
            'links' => [
                'self' => $linkSelf,
            ],
        ];
    }
}
