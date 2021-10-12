<?php

declare(strict_types=1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\ProvinceTransformer as DataTransformer;
use App\Api\V1\Transformers\RegencyTransformer;
use App\Api\V1\Validations\ProvinceValidation as DataValidation;
use App\Models\Province as DataDb;
use App\Models\Regency;
use App\Traits\FetchDataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProvinceController extends ApiController
{
    use FetchDataTrait;

    /**
     * request variable.
     *
     * @var Request
     */
    private $request;

    /**
     * The version of resources.
     *
     * @var string
     */
    private $version;

    /**
     * The name of resources.
     *
     * @var string
     */
    private $type;

    /**
     * The url of resources.
     *
     * @var string
     */
    private $url;

    /**
     * The name of the model.
     *
     * @var string
     */
    private $model;

    /**
     * The name of the fractal tranform.
     *
     * @var string
     */
    private $transformer;

    /**
     * __construct function.
     */
    public function __construct(DataValidation $validation)
    {
        $this->request = request();
        $this->version = 'v1';
        $this->type = 'province';
        $this->url = 'province';
        $this->model = DataDb::class;
        $this->validation = $validation;
        $this->transformer = DataTransformer::class;
    }

    public function index(): object
    {
        $data = new $this->model();
        $data = $this->hasSort($data);
        $data = $this->hasFilter($data);
        $data = $this->hasPaginate($data);

        return $this->response->paginator(
            $data,
            new $this->transformer(),
            [
                'key' => $this->type,
            ]
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function show(string $id): object
    {
        $requestData = ['id' => $id];
        $this->validation->idValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->where('id', $id)->first();

        if (empty($data)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return $this->response->item(
            $data,
            new $this->transformer(),
            [
                'key' => $this->type,
            ]
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function showSelfRegency(string $id): object
    {
        $type = 'regency';

        $requestData = ['id' => $id];
        $this->validation->idValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->where('id', $id)->first();

        $dataDb = [];
        if (isset($data) && isset($data->regency) && $data->regency->isNotEmpty()) {
            foreach ($data->regency as $dataDetail) {
                $dataDb[] = [
                    'type' => $type,
                    'id' => (string) $dataDetail->id
                ];
            }
        }

        $responseData = [
            'data' => $dataDb,
            'links' => [
                'self' => request()->url(),
                'related' => trim(Str::replaceFirst('relationships/', '', request()->url())),
            ],
        ];

        if (empty($data)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return $this->response->array(
            $responseData,
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function showRelatedRegency(string $id): object
    {
        $type = 'regency';
        $dataDb = [];

        $requestData = ['id' => $id];
        $this->validation->idValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->where('id', $id)->first();
        if (isset($data) && isset($data->regency) && $data->regency->isNotEmpty()) {
            $this->model = Regency::class;
            $dataDb = $data->regency();
            $dataDb = $this->hasSort($dataDb);
            $dataDb = $this->hasFilter($dataDb);
            $dataDb = $this->hasPaginate($dataDb);
        }

        if (empty($data)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return $this->response->paginator(
            $dataDb,
            new RegencyTransformer(),
            [
                'key' => $type,
            ]
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }
}
