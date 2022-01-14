<?php

namespace App\Responder;

use App\Exceptions\Exception;
use Phalcon\Http\ResponseInterface;

final class ResponseResult
{
    protected int $code;
    protected string $message;
    protected array $data;
    protected int $httpStatusCode;

    protected array $payload;
    private ResponseInterface $response;

    /**
     * @param int $code
     * @param string $message
     * @param array $data
     * @param int $httpStatusCode
     */
    public function __construct(int $code, string $message, array $data = [], int $httpStatusCode = 200)
    {
        $this->response = di('response');
        $this->response->setContentType('application/json');
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->httpStatusCode = $httpStatusCode;
        $this->setPayload();
    }

    private function setPayload()
    {
        $this->payload = [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * The response result is succeed
     *
     * @param array $content
     * @param string $message
     * @param int $httpStatusCode
     * @return ResponseInterface
     */
    public static function success(
        array $content = [],
        string $message = 'success.',
        int $httpStatusCode = 200
    ): ResponseInterface {
        $result = new ResponseResult(0, $message, $content, $httpStatusCode);
        $response = $result->response;
        $response
            ->setStatusCode($result->httpStatusCode)
            ->setJsonContent($result->getPayload());

        return $response;
    }

    /**
     * The response result is failure
     *
     * @param int $code
     * @param string $message
     * @param array $content
     * @param int $httpStatusCode
     * @return ResponseInterface
     * @throws Exception
     */
    public static function failure(
        int $code,
        string $message = 'failure.',
        array $content = [],
        int $httpStatusCode = 500
    ): ResponseInterface {
        $result = new ResponseResult($code, $message, $content, $httpStatusCode);
        $result->checkErrorCode();
        $response = $result->response;
        $response
            ->setStatusCode($result->httpStatusCode)
            ->setJsonContent($result->getPayload());

        return $response;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function checkErrorCode()
    {
        if ($this->code == 0) {
            throw new Exception('invalid error code.');
        }
    }
}
