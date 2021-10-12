<?php

declare(strict_types=1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\EmployeeTransformer as DataTransformer;
use App\Api\V1\Validations\EmployeeValidation as DataValidation;
use App\Models\Bank;
use App\Models\Employee as DataDb;
use App\Models\Position;
use App\Models\Regency;
use App\Traits\Base64ToImageTrait;
use App\Traits\FetchDataTrait;
use App\Traits\TransformerTrait;
use Dingo\Api\Http\Response as DingoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends ApiController
{
    use FetchDataTrait;
    use Base64ToImageTrait;
    use TransformerTrait;

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
        $this->type = 'employee';
        $this->url = 'employee';
        $this->model = DataDb::class;
        $this->validation = $validation;
        $this->transformer = DataTransformer::class;
    }

    public function index(): DingoResponse
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
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
    }

    public function show(string $uuid): DingoResponse
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
        )->setStatusCode(200)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
    }

    public function create(): DingoResponse
    {
        $this->validation->createValidation($this->request, $this->type);

        try {
            $data = new $this->model();
            $data->uuid = $this->request->json('data.id');
            $data->first_name = $this->request->json('data.attributes.firstName');
            $data->last_name = $this->request->json('data.attributes.lastName');
            $data->date_of_birth = $this->request->json('data.attributes.dateOfBirth');
            $data->email = $this->request->json('data.attributes.email');
            $data->phone = $this->request->json('data.attributes.phone');
            $data->address = $this->request->json('data.attributes.address');
            $data->zip_code = $this->request->json('data.attributes.zipCode');
            $data->ktp = $this->request->json('data.attributes.ktp');
            $data->bank_account = $this->request->json('data.attributes.bankAccount');

            if ($this->request->has('data.attributes.ktpImage')) {
                $base64Image = $this->request->json('data.attributes.ktpImage');
                $fileName = md5($this->request->json('data.id') . time());
                $image = $this->base64ToImage($base64Image, $fileName, 'data/attributes/ktpImage');
                $data->ktp_image = $image->name;
                $data->ktp_image_url = $image->url;
            }

            if ($this->request->has('data.relationships.bank.data.id')) {
                $bank = Bank::where('uuid', $this->request->json('data.relationships.bank.data.id'))->first();
                $data->bank()->associate($bank);
            }

            if ($this->request->has('data.relationships.position.data.id')) {
                $position = Position::where('uuid', $this->request->json('data.relationships.position.data.id'))->first();
                $data->position()->associate($position);
            }

            if ($this->request->has('data.relationships.regency.data.id')) {
                $regency = Regency::where('id', $this->request->json('data.relationships.regency.data.id'))->first();
                $data->regency()->associate($regency);
            }

            $data->save();

            $linkLocation = $this->getLinkSelf($this->url, $data->uuid);

            return $this->response->item(
                $data,
                new $this->transformer(),
                [
                    'key' => $this->type,
                ]
            )
                ->setStatusCode(201)
                ->withHeader('Location', $linkLocation)
                ->withHeader('Allow', 'GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
        } catch (\Exception $e) {
            $errorMsg['errors'][] = [
                'id' => (int) mt_rand(1000, 9999),
                'status' => '500',
                'code' => '101',
                'title' => 'internal server error',
                'detail' => 'save failed, something was wrong',
            ];

            throw new \Dingo\Api\Exception\ValidationHttpException($errorMsg, null, [], 500);
        }
    }

    public function update(string $uuid): DingoResponse
    {
        $requestData = ['uuid' => $uuid];
        $this->validation->uuidValidation($requestData, $this->type);

        $this->validation->updateValidation($this->request, $this->type);

        try {
            DB::beginTransaction();

            $data = new $this->model();
            $data = $data->firstWhere('uuid', $uuid);

            if ($this->request->has('data.attributes.firstName')) {
                $data->first_name = $this->request->json('data.attributes.firstName');
            }

            if ($this->request->has('data.attributes.lastName')) {
                $data->last_name = $this->request->json('data.attributes.lastName');
            }

            if ($this->request->has('data.attributes.dateOfBirth')) {
                $data->date_of_birth = $this->request->json('data.attributes.dateOfBirth');
            }

            if ($this->request->has('data.attributes.email')) {
                $data->email = $this->request->json('data.attributes.email');
            }

            if ($this->request->has('data.attributes.phone')) {
                $data->phone = $this->request->json('data.attributes.phone');
            }

            if ($this->request->has('data.attributes.address')) {
                $data->address = $this->request->json('data.attributes.address');
            }

            if ($this->request->has('data.attributes.zipCode')) {
                $data->zip_code = $this->request->json('data.attributes.zipCode');
            }

            if ($this->request->has('data.attributes.ktp')) {
                $data->ktp = $this->request->json('data.attributes.ktp');
            }

            if ($this->request->has('data.attributes.bankAccount')) {
                $data->bank_account = $this->request->json('data.attributes.bankAccount');
            }

            if ($this->request->has('data.attributes.ktpImage')) {
                $base64Image = $this->request->json('data.attributes.ktpImage');
                $fileName = md5($this->request->json('data.id') . time());
                $image = $this->base64ToImage($base64Image, $fileName, 'data/attributes/ktpImage');
                $data->ktp_image = $image->name;
                $data->ktp_image_url = $image->url;
            }

            if ($this->request->has('data.relationships.bank.data.id')) {
                $bank = Bank::where('uuid', $this->request->json('data.relationships.bank.data.id'))->first();
                $data->bank()->associate($bank);
            }

            if ($this->request->has('data.relationships.position.data.id')) {
                $position = Position::where('uuid', $this->request->json('data.relationships.position.data.id'))->first();
                $data->position()->associate($position);
            }

            if ($this->request->has('data.relationships.regency.data.id')) {
                $regency = Regency::where('id', $this->request->json('data.relationships.regency.data.id'))->first();
                $data->regency()->associate($regency);
            }

            $data->save();

            DB::commit();

            return $this->response->item(
                $data,
                new $this->transformer(),
                [
                    'key' => $this->type,
                ]
            )
                ->setStatusCode(200)
                ->withHeader('Allow', 'GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete(string $uuid): DingoResponse
    {
        $requestData = ['uuid' => $uuid];
        $this->validation->uuidValidation($requestData, $this->type);

        $data = new $this->model();
        $data = $data->firstWhere('uuid', $uuid);

        if (empty($data)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        $data->delete();

        return $this->response->noContent()
            ->setStatusCode(204)
            ->withHeader('Allow', 'GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
    }
}
