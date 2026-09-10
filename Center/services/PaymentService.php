<?php

declare(strict_types=1);

class PaymentService
{
    private string $baseUrl;
    private string $merchantId;
    private string $apiKey;
    private string $secret;
    private string $hashResponseKey;
    protected string $hashRequestKey;
    private int $timeout;
    public function __construct()
    {
        $config = require __DIR__ . '/config.php';
        //$config = require __DIR__ . '/config/config.php';
        $this->baseUrl = $config['base_url'];
        $this->merchantId = $config['merchant_id'];
        $this->hashRequestKey = $config['hash_request_key'];
        $this->hashResponseKey = $config['hash_response_key'];
        //$this->apiKey = $config['api_key'];
        //$this->secret = $config['api_secret'];
        $this->timeout = $config['timeout'];
    }

    /**
     * Send Payment Request
     */
    public function createPayment(array $data): array
    {
        ///_payment
        //$url = $this->baseUrl . "/payment/create";
        $url = $this->baseUrl . "/_payment";
        $payload = json_encode($data);

        $headers = [
            "Content-Type: application/json",
            "Accept: application/json",
            "MerchantId: {$this->merchantId}",
            "ApiKey: {$this->hashResponseKey}"
            //"ApiKey: {$this->apiKey}"

        ];
        // echo $payload;
        // exit();
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2

        ]);
        $response = curl_exec($ch);
        print_r($response);
        exit();
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'status' => $statusCode,
            'response' => json_decode($response, true)

        ];
    }
}
