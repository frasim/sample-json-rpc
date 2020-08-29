<?php


namespace App\Http\Responses;


class JsonRpcResponse
{
    const ERROR_PARSE_ERROR = -32700;
    const ERROR_INVALID_REQUEST = -32600;
    const ERROR_METHOD_NOT_FOUND = -32601;
    const ERROR_INVALID_PARAMS = -32602;
    const ERROR_INTERNAL_ERROR = -32603;
    const ERROR_SERVER_ERROR = -32000;

    public static $ERRORS = [
        self::ERROR_PARSE_ERROR => 'Parse error',
        self::ERROR_INVALID_REQUEST => 'Invalid Request',
        self::ERROR_METHOD_NOT_FOUND => 'Method not found',
        self::ERROR_INVALID_PARAMS => 'Invalid params',
        self::ERROR_INTERNAL_ERROR => 'Internal error',
        self::ERROR_SERVER_ERROR => 'Server error',
    ];

    protected $_result;
    protected $_error;

    public $id;
    public $jsonrpc;

    /**
     * JsonRpcResponse constructor.
     * @param string $jsonrpc
     * @param null $id
     * @param null $result
     * @param null $error
     */
    public function __construct($jsonrpc = "2.0", $id = null)
    {
        $this->id = $id;
        $this->jsonrpc = $jsonrpc;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param array $result
     */
    public function setResult(array $result)
    {
        $this->_result = $result;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param $code
     * @param array $data
     */
    public function setError($code, $data = [])
    {
        $this->_result = null;
        $this->_error = [
            'code' => $code,
            'message' => $this->getErrorMessage($code),
            'data' => $data
        ];
    }

    public function getErrorMessage($code)
    {
        return key_exists($code, static::$ERRORS) ? static::$ERRORS[$code] : null;
    }

    /**
     * Clear $_error property
     */
    public function clearError()
    {
        $this->_error = null;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return !is_null($this->_error);
    }

    /**
     * @return array
     */
    public function json()
    {
        $json = [
            'id' => $this->id,
            'jsonrpc' => $this->jsonrpc,
        ];
        if(!$this->hasError()) {
            $json['result'] = $this->getResult();
        }
        else {
            $json['error'] = $this->getError();
        }
        return $json;
    }

    public function error($code, $data = [])
    {
        $this->setError($code, $data);
        return $this->json();
    }
}
