<?php

declare(strict_types=1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\BankTransformer as DataTransformer;
use App\Api\V1\Validations\BankValidation as DataValidation;
use App\Models\Bank as DataDb;
use App\Traits\FetchDataTrait;
use Illuminate\Http\Request;

class BankController extends ApiController
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
        $this->type = 'bank';
        $this->url = 'bank';
        $this->model = DataDb::class;
        $this->validation = $validation;
        $this->transformer = DataTransformer::class;
    }

    public function index()
    {
        $data = new $this->model();
        $data = $this->hasInclude($data);
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

    public function show(string $uuid)
    {
        $requestData = ['uuid' => $uuid];
        $this->validation->uuidValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->firstWhere('uuid', $uuid);

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
}
