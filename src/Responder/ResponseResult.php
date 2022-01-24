<?php

namespace App\Responder;

use App\Exceptions\Exception;
use Fig\Http\Message\StatusCodeInterface;
use Phalcon\Http\ResponseInterface;

final class ResponseResult implements StatusCodeInterface
{
    const CODE_SUCCEED = 0;
    const CODE_FAILURE = 400;
    const CODE_NOT_FOUND = 404;
    const CODE_SERVER_ERROR = 500;

    protected int $code;
    protected string $message;
    protected array $data;
    protected int $httpStatusCode;

    protected array $payload;
    private ResponseInterface $response;

    /**
     * @param int $code
     * @param string $message
     * @param array|null $data
     * @param int $httpStatusCode
     */
    public function __construct(int $code, string $message, ?array $data = null, int $httpStatusCode = self::STATUS_OK)
    {
        $this->response = di('response');
        $this->response->setContentType('application/json');
        $this->code = $code;
        $this->message = $message;
        $this->data = $data ?: [];
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
    public static function asSuccess(
        array $content = [],
        string $message = 'success.',
        int $httpStatusCode = self::STATUS_OK
    ): ResponseInterface {
        $result = new ResponseResult(self::CODE_SUCCEED, $message, $content, $httpStatusCode);
        $response = $result->response;
        $response
            ->setStatusCode($result->httpStatusCode)
            ->setJsonContent($result->getPayload());

        return $response;
    }

    /**
     * The response result is failure
     *
     * @param string $message
     * @param array|null $content
     * @param int $code
     * @param int $httpStatusCode
     * @return ResponseInterface
     * @throws Exception
     */
    public static function asFailure(
        string $message = 'failure.',
        ?array $content = null,
        int $code = self::CODE_FAILURE,
        int $httpStatusCode = self::STATUS_INTERNAL_SERVER_ERROR
    ): ResponseInterface {
        $result = new ResponseResult($code, $message, null, $httpStatusCode);
        $result->checkErrorCode();
        $payload = $result->getPayload();
        unset($payload['data']);
        $payload = array_merge($payload, $content ?: []);
        $response = $result->response;
        $response
            ->setStatusCode($result->httpStatusCode)
            ->setJsonContent($payload);

        return $response;
    }

    /**
     * @param string $message
     * @param array|null $content
     * @param int $code
     * @param int $httpStatusCode
     * @return void
     */
    public static function asException(
        int $code = self::CODE_SERVER_ERROR,
        string $message = 'exception.',
        ?array $content = null,
        int $httpStatusCode = self::STATUS_INTERNAL_SERVER_ERROR
    ): void {
        try {
            self::asFailure($message, $content, $code, $httpStatusCode)->send();
        } catch (\Exception $e) {
        }
    }

    /**
     * Not Found
     *
     * @param string|null $path
     * @return ResponseInterface
     * @throws Exception
     */
    public static function notFound(?string $path = null): ResponseInterface
    {
        return self::asFailure(
            sprintf('%s not found.', $path ?: 'Route'),
            null,
            self::CODE_NOT_FOUND,
            self::STATUS_NOT_FOUND
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    private function checkErrorCode()
    {
        if ($this->code == self::CODE_SUCCEED) {
            throw new Exception('invalid error code.');
        }
    }
}
