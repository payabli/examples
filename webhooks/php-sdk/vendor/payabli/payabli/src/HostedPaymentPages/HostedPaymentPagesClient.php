<?php

namespace Payabli\HostedPaymentPages;

use GuzzleHttp\ClientInterface;
use Payabli\Core\Client\RawClient;
use Payabli\Types\PayabliPages;
use Payabli\Exceptions\PayabliException;
use Payabli\Exceptions\PayabliApiException;
use Payabli\Core\Json\JsonApiRequest;
use Payabli\Environments;
use Payabli\Core\Client\HttpMethod;
use JsonException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Payabli\HostedPaymentPages\Requests\NewPageRequest;
use Payabli\Types\PayabliApiResponse00Responsedatanonobject;

class HostedPaymentPagesClient
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
     * Loads all of a payment page's details including `pageIdentifier` and `validationCode`. This endpoint requires an `application` API token.
     *
     * @param string $entry The paypoint's entrypoint identifier. [Learn more](/api-reference/api-overview#entrypoint-vs-entry)
     * @param string $subdomain Payment page identifier. The subdomain value is the last part of the payment page URL. For example, in`https://paypages-sandbox.payabli.com/513823dc10/pay-your-fees-1`, the subdomain is `pay-your-fees-1`.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return PayabliPages
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function loadPage(string $entry, string $subdomain, ?array $options = null): PayabliPages
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Paypoint/load/{$entry}/{$subdomain}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return PayabliPages::fromJson($json);
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
     *
     * Creates a new payment page for a paypoint.
     * Note: this operation doesn't create a new paypoint, just a payment page for an existing paypoint. Paypoints are created by the Payabli team when a boarding application is approved.
     *
     * @param string $entry The paypoint's entrypoint identifier. [Learn more](/api-reference/api-overview#entrypoint-vs-entry)
     * @param NewPageRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return PayabliApiResponse00Responsedatanonobject
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function newPage(string $entry, NewPageRequest $request, ?array $options = null): PayabliApiResponse00Responsedatanonobject
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
                    path: "Paypoint/{$entry}",
                    method: HttpMethod::POST,
                    headers: $headers,
                    body: $request->body,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return PayabliApiResponse00Responsedatanonobject::fromJson($json);
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
     * Updates a payment page in a paypoint.
     *
     * @param string $entry The paypoint's entrypoint identifier. [Learn more](/api-reference/api-overview#entrypoint-vs-entry)
     * @param string $subdomain Payment page identifier. The subdomain value is the last part of the payment page URL. For example, in`https://paypages-sandbox.payabli.com/513823dc10/pay-your-fees-1`, the subdomain is `pay-your-fees-1`.
     * @param PayabliPages $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return PayabliApiResponse00Responsedatanonobject
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function savePage(string $entry, string $subdomain, PayabliPages $request, ?array $options = null): PayabliApiResponse00Responsedatanonobject
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Paypoint/{$entry}/{$subdomain}",
                    method: HttpMethod::PUT,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return PayabliApiResponse00Responsedatanonobject::fromJson($json);
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
