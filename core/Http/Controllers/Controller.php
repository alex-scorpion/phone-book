<?php

namespace Core\Http\Controllers;

use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class Controller
{
    protected function emptyResponse($status, array $headers = [])
    {
        return new EmptyResponse($status, $headers);
    }

    protected function htmlResponse($html, $status = 200, array $headers = []): HtmlResponse
    {
        return new HtmlResponse($html, $status, $headers);
    }

    protected function jsonResponse($data, $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function jsonResponseData($data, $status = 200, array $headers = []): JsonResponse
    {
        return $this->jsonResponse(['data' => $data], $status, $headers);
    }

    protected function jsonResponseErrors($errors, $status = 200, array $headers = []): JsonResponse
    {
        return $this->jsonResponse(['errors' => $errors], $status, $headers);
    }
}
