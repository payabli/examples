<?php

namespace Payabli\MoneyOut;

use GuzzleHttp\ClientInterface;
use Payabli\Core\Client\RawClient;
use Payabli\MoneyOut\Requests\MoneyOutTypesRequestOutAuthorize;
use Payabli\MoneyOutTypes\Types\AuthCapturePayoutResponse;
use Payabli\Exceptions\PayabliException;
use Payabli\Exceptions\PayabliApiException;
use Payabli\Core\Json\JsonApiRequest;
use Payabli\Environments;
use Payabli\Core\Client\HttpMethod;
use JsonException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Payabli\MoneyOutTypes\Types\CaptureAllOutResponse;
use Payabli\Core\Json\JsonSerializer;
use Payabli\Types\PayabliApiResponse0000;
use Payabli\MoneyOut\Requests\CaptureAllOutRequest;
use Payabli\MoneyOut\Requests\CaptureOutRequest;
use Payabli\Types\BillDetailResponse;
use Payabli\MoneyOutTypes\Types\VCardGetResponse;
use Payabli\MoneyOut\Requests\SendVCardLinkRequest;
use Payabli\MoneyOutTypes\Types\OperationResult;
use Payabli\Core\Json\JsonDecoder;

class MoneyOutClient
{
    /**
     * @var array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    private array $options;

    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param RawClient $client
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        RawClient $client,
        ?array $options = null,
    ) {
        $this->client = $client;
        $this->options = $options ?? [];
    }

    /**
     * Authorizes transaction for payout. Authorized transactions aren't flagged for settlement until captured. Use `referenceId` returned in the response to capture the transaction.
     *
     * @param MoneyOutTypesRequestOutAuthorize $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return AuthCapturePayoutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function authorizeOut(MoneyOutTypesRequestOutAuthorize $request, ?array $options = null): AuthCapturePayoutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->allowDuplicatedBills != null) {
            $query['allowDuplicatedBills'] = $request->allowDuplicatedBills;
        }
        if ($request->doNotCreateBills != null) {
            $query['doNotCreateBills'] = $request->doNotCreateBills;
        }
        if ($request->forceVendorCreation != null) {
            $query['forceVendorCreation'] = $request->forceVendorCreation;
        }
        $headers = [];
        if ($request->idempotencyKey != null) {
            $headers['idempotencyKey'] = $request->idempotencyKey;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/authorize",
                    method: HttpMethod::POST,
                    headers: $headers,
                    query: $query,
                    body: $request->body,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return AuthCapturePayoutResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Cancels an array of payout transactions.
     *
     * @param array<string> $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return CaptureAllOutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function cancelAllOut(array $request, ?array $options = null): CaptureAllOutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/cancelAll",
                    method: HttpMethod::POST,
                    body: JsonSerializer::serializeArray($request, ['string']),
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return CaptureAllOutResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Cancel a payout transaction by ID.
     *
     * @param string $referenceId The ID for the payout transaction.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return PayabliApiResponse0000
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function cancelOut(string $referenceId, ?array $options = null): PayabliApiResponse0000
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/cancel/{$referenceId}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return PayabliApiResponse0000::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Captures an array of authorized payout transactions for settlement.
     *
     * @param CaptureAllOutRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return CaptureAllOutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function captureAllOut(CaptureAllOutRequest $request, ?array $options = null): CaptureAllOutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        if ($request->idempotencyKey != null) {
            $headers['idempotencyKey'] = $request->idempotencyKey;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/captureAll",
                    method: HttpMethod::POST,
                    headers: $headers,
                    body: $request->body,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return CaptureAllOutResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Captures a single authorized payout transaction by ID.
     *
     * @param string $referenceId The ID for the payout transaction.
     * @param CaptureOutRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return AuthCapturePayoutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function captureOut(string $referenceId, CaptureOutRequest $request = new CaptureOutRequest(), ?array $options = null): AuthCapturePayoutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        if ($request->idempotencyKey != null) {
            $headers['idempotencyKey'] = $request->idempotencyKey;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/capture/{$referenceId}",
                    method: HttpMethod::GET,
                    headers: $headers,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return AuthCapturePayoutResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Returns details for a processed money out transaction.
     *
     * @param string $transId ReferenceId for the transaction (PaymentId).
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return BillDetailResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function payoutDetails(string $transId, ?array $options = null): BillDetailResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/details/{$transId}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return BillDetailResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Retrieves vCard details for a single card in an entrypoint.
     *
     * @param string $cardToken ID for a virtual card.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return VCardGetResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function vCardGet(string $cardToken, ?array $options = null): VCardGetResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/vcard/{$cardToken}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return VCardGetResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Sends a virtual card link via email to the vendor associated with the `transId`.
     *
     * @param SendVCardLinkRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return OperationResult
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function sendVCardLink(SendVCardLinkRequest $request, ?array $options = null): OperationResult
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "vcard/send-card-link",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return OperationResult::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Retrieve the image of a check associated with a processed transaction.
     * The check image is returned in the response body as a base64-encoded string.
     * The check image is only available for payouts that have been processed.
     *
     * Name of the check asset to retrieve. This is returned as `filename` in the `CheckData` object
     * in the response when you make a GET request to `/MoneyOut/details/{transId}`.
     * ```
     *     "CheckData": {
     *       "ftype": "PDF",
     *       "filename": "check133832686289732320_01JKBNZ5P32JPTZY8XXXX000000.pdf",
     *       "furl": "",
     *       "fContent": ""
     *   }
     * ```
     *
     * @param string $assetName
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return string
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function getCheckImage(string $assetName, ?array $options = null): string
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "MoneyOut/checkimage/{$assetName}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return JsonDecoder::decodeString($json);
            }
        } catch (JsonException $e) {
            throw new PayabliException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PayabliException(message: $e->getMessage(), previous: $e);
            }
            throw new PayabliApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
        } catch (ClientExceptionInterface $e) {
            throw new PayabliException(message: $e->getMessage(), previous: $e);
        }
        throw new PayabliApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }
}
