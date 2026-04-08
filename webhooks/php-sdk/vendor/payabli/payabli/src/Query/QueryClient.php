<?php

namespace Payabli\Query;

use GuzzleHttp\ClientInterface;
use Payabli\Core\Client\RawClient;
use Payabli\Query\Requests\ListBatchDetailsRequest;
use Payabli\QueryTypes\Types\QueryBatchesDetailResponse;
use Payabli\Exceptions\PayabliException;
use Payabli\Exceptions\PayabliApiException;
use Payabli\Core\Json\JsonApiRequest;
use Payabli\Environments;
use Payabli\Core\Client\HttpMethod;
use JsonException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Payabli\Query\Requests\ListBatchDetailsOrgRequest;
use Payabli\Types\QueryResponseSettlements;
use Payabli\Query\Requests\ListBatchesRequest;
use Payabli\QueryTypes\Types\QueryBatchesResponse;
use Payabli\Query\Requests\ListBatchesOrgRequest;
use Payabli\Query\Requests\ListBatchesOutRequest;
use Payabli\Types\QueryBatchesOutResponse;
use Payabli\Query\Requests\ListBatchesOutOrgRequest;
use Payabli\Query\Requests\ListChargebacksRequest;
use Payabli\Types\QueryChargebacksResponse;
use Payabli\Query\Requests\ListChargebacksOrgRequest;
use Payabli\Query\Requests\ListCustomersRequest;
use Payabli\Types\QueryCustomerResponse;
use Payabli\Query\Requests\ListCustomersOrgRequest;
use Payabli\Query\Requests\ListNotificationReportsRequest;
use Payabli\Types\QueryResponseNotificationReports;
use Payabli\Query\Requests\ListNotificationReportsOrgRequest;
use Payabli\Query\Requests\ListNotificationsRequest;
use Payabli\Types\QueryResponseNotifications;
use Payabli\Query\Requests\ListNotificationsOrgRequest;
use Payabli\Query\Requests\ListOrganizationsRequest;
use Payabli\QueryTypes\Types\ListOrganizationsResponse;
use Payabli\Query\Requests\ListPayoutRequest;
use Payabli\Types\QueryPayoutTransaction;
use Payabli\Query\Requests\ListPayoutOrgRequest;
use Payabli\Query\Requests\ListPaypointsRequest;
use Payabli\Types\QueryEntrypointResponse;
use Payabli\Query\Requests\ListSettlementsRequest;
use Payabli\Query\Requests\ListSettlementsOrgRequest;
use Payabli\Query\Requests\ListSubscriptionsRequest;
use Payabli\Types\QuerySubscriptionResponse;
use Payabli\Query\Requests\ListSubscriptionsOrgRequest;
use Payabli\Query\Requests\ListTransactionsRequest;
use Payabli\Types\QueryResponseTransactions;
use Payabli\Query\Requests\ListTransactionsOrgRequest;
use Payabli\Query\Requests\ListTransfersPaypointRequest;
use Payabli\QueryTypes\Types\QueryTransferDetailResponse;
use Payabli\Query\Requests\ListTransfersRequest;
use Payabli\Types\TransferQueryResponse;
use Payabli\Query\Requests\ListTransfersRequestOrg;
use Payabli\Query\Requests\ListUsersOrgRequest;
use Payabli\Types\QueryUserResponse;
use Payabli\Query\Requests\ListUsersPaypointRequest;
use Payabli\Query\Requests\ListVendorsRequest;
use Payabli\Types\QueryResponseVendors;
use Payabli\Query\Requests\ListVendorsOrgRequest;
use Payabli\Query\Requests\ListVcardsRequest;
use Payabli\Types\VCardQueryResponse;
use Payabli\Query\Requests\ListVcardsOrgRequest;

class QueryClient
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
     * Retrieve a list of batches and their details, including settled and
     * unsettled transactions for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListBatchDetailsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryBatchesDetailResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatchDetails(string $entry, ListBatchDetailsRequest $request = new ListBatchDetailsRequest(), ?array $options = null): QueryBatchesDetailResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batchDetails/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryBatchesDetailResponse::fromJson($json);
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
     * Retrieve a list of batches and their details, including settled and unsettled transactions for an organization. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListBatchDetailsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseSettlements
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatchDetailsOrg(int $orgId, ListBatchDetailsOrgRequest $request = new ListBatchDetailsOrgRequest(), ?array $options = null): QueryResponseSettlements
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batchDetails/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseSettlements::fromJson($json);
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
     * Retrieve a list of batches for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListBatchesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryBatchesResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatches(string $entry, ListBatchesRequest $request = new ListBatchesRequest(), ?array $options = null): QueryBatchesResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batches/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryBatchesResponse::fromJson($json);
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
     * Retrieve a list of batches for an org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListBatchesOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryBatchesResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatchesOrg(int $orgId, ListBatchesOrgRequest $request = new ListBatchesOrgRequest(), ?array $options = null): QueryBatchesResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batches/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryBatchesResponse::fromJson($json);
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
     * Retrieve a list of MoneyOut batches for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListBatchesOutRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryBatchesOutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatchesOut(string $entry, ListBatchesOutRequest $request = new ListBatchesOutRequest(), ?array $options = null): QueryBatchesOutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batchesOut/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryBatchesOutResponse::fromJson($json);
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
     * Retrieve a list of MoneyOut batches for an org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListBatchesOutOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryBatchesOutResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listBatchesOutOrg(int $orgId, ListBatchesOutOrgRequest $request = new ListBatchesOutOrgRequest(), ?array $options = null): QueryBatchesOutResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/batchesOut/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryBatchesOutResponse::fromJson($json);
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
     * Retrieves a list of chargebacks and returned transactions for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListChargebacksRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryChargebacksResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listChargebacks(string $entry, ListChargebacksRequest $request = new ListChargebacksRequest(), ?array $options = null): QueryChargebacksResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/chargebacks/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryChargebacksResponse::fromJson($json);
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
     * Retrieve a list of chargebacks and returned transactions for an org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListChargebacksOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryChargebacksResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listChargebacksOrg(int $orgId, ListChargebacksOrgRequest $request = new ListChargebacksOrgRequest(), ?array $options = null): QueryChargebacksResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/chargebacks/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryChargebacksResponse::fromJson($json);
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
     * Retrieves a list of customers for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListCustomersRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryCustomerResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listCustomers(string $entry, ListCustomersRequest $request = new ListCustomersRequest(), ?array $options = null): QueryCustomerResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/customers/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryCustomerResponse::fromJson($json);
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
     * Retrieves a list of customers for an org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListCustomersOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryCustomerResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listCustomersOrg(int $orgId, ListCustomersOrgRequest $request = new ListCustomersOrgRequest(), ?array $options = null): QueryCustomerResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/customers/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryCustomerResponse::fromJson($json);
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
     * Returns a list of all reports generated in the last 60 days for a single entrypoint. Use filters to limit results.
     *
     * @param string $entry
     * @param ListNotificationReportsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseNotificationReports
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listNotificationReports(string $entry, ListNotificationReportsRequest $request = new ListNotificationReportsRequest(), ?array $options = null): QueryResponseNotificationReports
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/notificationReports/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseNotificationReports::fromJson($json);
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
     * Returns a list of all reports generated in the last 60 days for an organization. Use filters to limit results.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListNotificationReportsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseNotificationReports
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listNotificationReportsOrg(int $orgId, ListNotificationReportsOrgRequest $request = new ListNotificationReportsOrgRequest(), ?array $options = null): QueryResponseNotificationReports
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/notificationReports/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseNotificationReports::fromJson($json);
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
     * Returns a list of notifications for an entrypoint. Use filters to limit results.
     *
     * @param string $entry
     * @param ListNotificationsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseNotifications
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listNotifications(string $entry, ListNotificationsRequest $request = new ListNotificationsRequest(), ?array $options = null): QueryResponseNotifications
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/notifications/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseNotifications::fromJson($json);
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
     * Return a list of notifications for an organization. Use filters to limit results.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListNotificationsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseNotifications
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listNotificationsOrg(int $orgId, ListNotificationsOrgRequest $request = new ListNotificationsOrgRequest(), ?array $options = null): QueryResponseNotifications
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/notifications/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseNotifications::fromJson($json);
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
     * Retrieves a list of an organization's suborganizations and their full details such as orgId, users, and settings. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListOrganizationsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ListOrganizationsResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listOrganizations(int $orgId, ListOrganizationsRequest $request = new ListOrganizationsRequest(), ?array $options = null): ListOrganizationsResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/organizations/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return ListOrganizationsResponse::fromJson($json);
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
     * Retrieves a list of money out transactions (payouts) for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListPayoutRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryPayoutTransaction
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listPayout(string $entry, ListPayoutRequest $request = new ListPayoutRequest(), ?array $options = null): QueryPayoutTransaction
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/payouts/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryPayoutTransaction::fromJson($json);
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
     * Retrieves a list of money out transactions (payouts) for an organization. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListPayoutOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryPayoutTransaction
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listPayoutOrg(int $orgId, ListPayoutOrgRequest $request = new ListPayoutOrgRequest(), ?array $options = null): QueryPayoutTransaction
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/payouts/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryPayoutTransaction::fromJson($json);
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
     * Returns a list of paypoints in an organization. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListPaypointsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryEntrypointResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listPaypoints(int $orgId, ListPaypointsRequest $request = new ListPaypointsRequest(), ?array $options = null): QueryEntrypointResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/paypoints/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryEntrypointResponse::fromJson($json);
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
     * Retrieve a list of settled transactions for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListSettlementsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseSettlements
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listSettlements(string $entry, ListSettlementsRequest $request = new ListSettlementsRequest(), ?array $options = null): QueryResponseSettlements
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/settlements/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseSettlements::fromJson($json);
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
     * Retrieve a list of settled transactions for an organization. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListSettlementsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseSettlements
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listSettlementsOrg(int $orgId, ListSettlementsOrgRequest $request = new ListSettlementsOrgRequest(), ?array $options = null): QueryResponseSettlements
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/settlements/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseSettlements::fromJson($json);
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
     * Returns a list of subscriptions for a single paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListSubscriptionsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QuerySubscriptionResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listSubscriptions(string $entry, ListSubscriptionsRequest $request = new ListSubscriptionsRequest(), ?array $options = null): QuerySubscriptionResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/subscriptions/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QuerySubscriptionResponse::fromJson($json);
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
     * Returns a list of subscriptions for a single org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListSubscriptionsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QuerySubscriptionResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listSubscriptionsOrg(int $orgId, ListSubscriptionsOrgRequest $request = new ListSubscriptionsOrgRequest(), ?array $options = null): QuerySubscriptionResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/subscriptions/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QuerySubscriptionResponse::fromJson($json);
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
     * Retrieve a list of transactions for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     * By default, this endpoint returns only transactions from the last 60 days. To query transactions outside of this period, include `transactionDate` filters.
     * For example, this request parameters filter for transactions between April 01, 2024 and April 09, 2024.
     * ``` curl --request GET \
     *   --url https://sandbox.payabli.com/api/Query/transactions/org/1?limitRecord=20&fromRecord=0&transactionDate(ge)=2024-04-01T00:00:00&transactionDate(le)=2024-04-09T23:59:59\
     *   --header 'requestToken: <api-key>'
     *
     *   ```
     *
     * @param string $entry
     * @param ListTransactionsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseTransactions
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listTransactions(string $entry, ListTransactionsRequest $request = new ListTransactionsRequest(), ?array $options = null): QueryResponseTransactions
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/transactions/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseTransactions::fromJson($json);
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
     * Retrieve a list of transactions for an organization. Use filters to
     * limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     *
     * By default, this endpoint returns only transactions from the last 60 days. To query transactions outside of this period, include `transactionDate` filters.
     *
     * For example, this request parameters filter for transactions between April 01, 2024 and April 09, 2024.
     *
     * ```
     * curl --request GET \
     *   --url https://sandbox.payabli.com/api/Query/transactions/org/1?limitRecord=20&fromRecord=0&transactionDate(ge)=2024-04-01T00:00:00&transactionDate(le)=2024-04-09T23:59:59\
     *   --header 'requestToken: <api-key>'
     *
     *   ```
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListTransactionsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseTransactions
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listTransactionsOrg(int $orgId, ListTransactionsOrgRequest $request = new ListTransactionsOrgRequest(), ?array $options = null): QueryResponseTransactions
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/transactions/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseTransactions::fromJson($json);
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
     * Retrieve a list of transfer details records for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param int $transferId The numeric identifier for the transfer, assigned by Payabli.
     * @param ListTransfersPaypointRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryTransferDetailResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listTransferDetails(string $entry, int $transferId, ListTransfersPaypointRequest $request = new ListTransfersPaypointRequest(), ?array $options = null): QueryTransferDetailResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/transferDetails/{$entry}/{$transferId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryTransferDetailResponse::fromJson($json);
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
     * Retrieve a list of transfers for a paypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListTransfersRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return TransferQueryResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listTransfers(string $entry, ListTransfersRequest $request = new ListTransfersRequest(), ?array $options = null): TransferQueryResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/transfers/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return TransferQueryResponse::fromJson($json);
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
     * Retrieve a list of transfers for an org. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId
     * @param ListTransfersRequestOrg $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return TransferQueryResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listTransfersOrg(int $orgId, ListTransfersRequestOrg $request = new ListTransfersRequestOrg(), ?array $options = null): TransferQueryResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/transfers/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return TransferQueryResponse::fromJson($json);
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
     * Get list of users for an org. Use filters to limit results.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListUsersOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryUserResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listUsersOrg(int $orgId, ListUsersOrgRequest $request = new ListUsersOrgRequest(), ?array $options = null): QueryUserResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/users/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryUserResponse::fromJson($json);
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
     * Get list of users for a paypoint. Use filters to limit results.
     *
     * @param string $entry The paypoint's entrypoint identifier. [Learn more](/api-reference/api-overview#entrypoint-vs-entry)
     * @param ListUsersPaypointRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryUserResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listUsersPaypoint(string $entry, ListUsersPaypointRequest $request = new ListUsersPaypointRequest(), ?array $options = null): QueryUserResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/users/point/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryUserResponse::fromJson($json);
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
     * Retrieve a list of vendors for an entrypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry The paypoint's entrypoint identifier. [Learn more](/api-reference/api-overview#entrypoint-vs-entry)
     * @param ListVendorsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseVendors
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listVendors(string $entry, ListVendorsRequest $request = new ListVendorsRequest(), ?array $options = null): QueryResponseVendors
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/vendors/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseVendors::fromJson($json);
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
     * Retrieve a list of vendors for an organization. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListVendorsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return QueryResponseVendors
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listVendorsOrg(int $orgId, ListVendorsOrgRequest $request = new ListVendorsOrgRequest(), ?array $options = null): QueryResponseVendors
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/vendors/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return QueryResponseVendors::fromJson($json);
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
     * Retrieve a list of vcards (virtual credit cards) issued for an entrypoint. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param string $entry
     * @param ListVcardsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return VCardQueryResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listVcards(string $entry, ListVcardsRequest $request = new ListVcardsRequest(), ?array $options = null): VCardQueryResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/vcards/{$entry}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return VCardQueryResponse::fromJson($json);
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
     * Retrieve a list of vcards (virtual credit cards) issued for an organization. Use filters to limit results. Include the `exportFormat` query parameter to return the results as a file instead of a JSON response.
     *
     * @param int $orgId The numeric identifier for organization, assigned by Payabli.
     * @param ListVcardsOrgRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return VCardQueryResponse
     * @throws PayabliException
     * @throws PayabliApiException
     */
    public function listVcardsOrg(int $orgId, ListVcardsOrgRequest $request = new ListVcardsOrgRequest(), ?array $options = null): VCardQueryResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->exportFormat != null) {
            $query['exportFormat'] = $request->exportFormat;
        }
        if ($request->fromRecord != null) {
            $query['fromRecord'] = $request->fromRecord;
        }
        if ($request->limitRecord != null) {
            $query['limitRecord'] = $request->limitRecord;
        }
        if ($request->parameters != null) {
            $query['parameters'] = $request->parameters;
        }
        if ($request->sortBy != null) {
            $query['sortBy'] = $request->sortBy;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Sandbox->value,
                    path: "Query/vcards/org/{$orgId}",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return VCardQueryResponse::fromJson($json);
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
