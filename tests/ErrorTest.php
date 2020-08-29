<?php


class ErrorTest extends TestCase
{
    const JSON_REQUEST_PARSE_ERROR = '{"jsonrpc":"2.0","id":"1"';
    const JSON_REQUEST_INVALID_REQUEST = '{"id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"1","longitude":"1"},"range":"1000","limit":"2"}}';
    const JSON_REQUEST_METHOD_NOT_FOUND = '{"jsonrpc":"2.0","id":"1","method":"Search","params":{"currentLocation":{"latitude":"1","longitude":"1"},"range":"1000","limit":"2"}}';
    const JSON_REQUEST_INVALID_PARAMS = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"1","longitude":"1"}}}';
    const JSON_REQUEST_INVALID_PARAMS_NEGATIVE_RANGE = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"1","longitude":"1"},"range":"-500","limit":"10"}}';
    const JSON_REQUEST_INVALID_PARAMS_NEGATIVE_LIMIT = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"1","longitude":"1"},"range":"500","limit":"-2"}}';

    const JSON_RESPONSE_PARSE_ERROR = '{"id":null,"jsonrpc":null,"error":{"code":-32700,"message":"Parse error","data":[]}}';
    const JSON_RESPONSE_INVALID_REQUEST = '{"id":"1","jsonrpc":null,"error":{"code":-32600,"message":"Invalid Request","data":["The jsonrpc field is required."]}}';
    const JSON_RESPONSE_METHOD_NOT_FOUND = '{"id":"1","jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found","data":["The selected method is invalid."]}}';
    const JSON_RESPONSE_INVALID_PARAMS = '{"id":"1","jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params","data":["The range field is required.","The limit field is required."]}}';
    const JSON_RESPONSE_INVALID_PARAMS_NEGATIVE_RANGE = '{"id":"1","jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params","data":["The range must be at least 0."]}}';
    const JSON_RESPONSE_INVALID_PARAMS_NEGATIVE_LIMIT = '{"id":"1","jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params","data":["The limit must be at least -1."]}}';


    public function testErrorParseError()
    {
        $this->json('POST', '/', [])
            ->seeJson(json_decode(static::JSON_RESPONSE_PARSE_ERROR, true));
    }

    public function testErrorInvalidRequest()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_INVALID_REQUEST, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_INVALID_REQUEST, true));
    }

    public function testErrorMethodNotFound()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_METHOD_NOT_FOUND, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_METHOD_NOT_FOUND, true));
    }

    public function testErrorInvalidParams()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_INVALID_PARAMS, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_INVALID_PARAMS, true));
    }

    public function testErrorInvalidParamsNegativeRange()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_INVALID_PARAMS_NEGATIVE_RANGE, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_INVALID_PARAMS_NEGATIVE_RANGE, true));
    }

    public function testErrorInvalidParamsNegativeLimit()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_INVALID_PARAMS_NEGATIVE_LIMIT, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_INVALID_PARAMS_NEGATIVE_LIMIT, true));
    }
}
