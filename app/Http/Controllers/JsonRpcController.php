<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonRpcResponse;
use App\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

/**
 * Class JsonRpcController
 * @package App\Http\Controllers
 */
class JsonRpcController extends Controller
{
    /**
     * List of allowed methods with validation rules
     * @var \string[][][][]
     */
    protected $allowedMethods = [
        'SearchNearestPharmacy' => [
            'rules' => [
                'currentLocation.latitude' => ['required', 'numeric'],
                'currentLocation.longitude' => ['required', 'numeric'],
                'range' => ['required', 'numeric', 'min:0'],
                'limit' => ['required', 'numeric', 'min:-1']
            ]
        ]
    ];

    /**
     * @param Request $request
     * @return array
     */
    public function handleRequest(Request $request)
    {
        $errorCode = 0;
        $errors = null;
        $response = new JsonRpcResponse($request->post('jsonrpc'), $request->post('id'));
        if(!empty($request->post())) {
            $method = $request->post('method');
            $params = $request->post('params');
            $allowedMethods = array_keys($this->allowedMethods);
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
                'jsonrpc' => ['required'],
                'method' => ['required', Rule::in($allowedMethods)],
                'params' => ['required']
            ]);
            if (!$validator->fails()) {
                if ($this->validateMethodParams($method, $params, $errors)) {
                    $result = $this->call($method, $params);
                    $response->setResult($result);
                } else {
                    $errorCode = JsonRpcResponse::ERROR_INVALID_PARAMS;
                }
            } else {
                $errors = $validator->errors();
                if ($errors->has('method'))
                    $errorCode = JsonRpcResponse::ERROR_METHOD_NOT_FOUND;
                else {
                    $errorCode = JsonRpcResponse::ERROR_INVALID_REQUEST;
                }
            }
        }
        else {
            $errorCode = JsonRpcResponse::ERROR_PARSE_ERROR;
        }
        if($errorCode !== 0) {
            $data = $errors ? $errors->all() : [];
            $response->setError($errorCode, $data);
        }
        return $response->json();
    }

    /**
     * @param string $method
     * @param array $params
     * @param MessageBag|null $errors
     * @return bool
     */
    protected function validateMethodParams(string $method, array $params, MessageBag &$errors = null)
    {
        $valid = true;
        $rules = key_exists('rules', $this->allowedMethods[$method]) ? $this->allowedMethods[$method]['rules'] : null;
        if(!is_null($rules)) {
            $validator = Validator::make($params, $rules);
            if(!($valid = !$validator->fails()))
                $errors = $validator->errors();
        }
        return $valid;
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    protected function call(string $method, array $params)
    {
        return call_user_func([$this, $method], $params);
    }

    /**** METHODS ****/

    /**
     * @param array $params
     * @return array
     */
    public function SearchNearestPharmacy(array $params)
    {
        $latitude = $params['currentLocation']['latitude'];
        $longitude = $params['currentLocation']['longitude'];
        $range = $params['range'] ?? null;
        $limit = $params['limit'] ?? null;
        return ['pharmacies' => Pharmacy::near($latitude, $longitude, $range, $limit)];
    }
}
