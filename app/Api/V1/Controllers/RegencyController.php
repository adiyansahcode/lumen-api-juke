<?php

declare(strict_types=1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\ProvinceTransformer;
use App\Api\V1\Transformers\RegencyTransformer as DataTransformer;
use App\Api\V1\Validations\RegencyValidation as DataValidation;
use App\Models\Regency as DataDb;
use App\Traits\FetchDataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegencyController extends ApiController
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
        $this->type = 'regency';
        $this->url = 'regency';
        $this->model = DataDb::class;
        $this->validation = $validation;
        $this->transformer = DataTransformer::class;
    }

    public function index()
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
        )->setStatusCode(200)->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function show(string $id)
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
        )->setStatusCode(200)->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function showSelfProvince(string $id): object
    {
        $type = 'province';

        $requestData = ['id' => $id];
        $this->validation->idValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->where('id', $id)->first();

        $dataDb = null;
        if (isset($data) && isset($data->province)) {
            $dataDb = [
                'type' => $type,
                'id' => (string) $data->province->id
            ];
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
        )->setStatusCode(200)->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }

    public function showRelatedProvince(string $id): object
    {
        $type = 'province';

        $requestData = ['id' => $id];
        $this->validation->idValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->where('id', $id)->first();

        $dataDb = null;
        if (isset($data) && isset($data->province)) {
            $dataDb = $data->province;
        }

        if (empty($dataDb)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return $this->response->item(
            $dataDb,
            new ProvinceTransformer(),
            [
                'key' => $type,
            ]
        )->setStatusCode(200)->withHeader('Allow', 'GET,HEAD,OPTIONS');
    }
}
