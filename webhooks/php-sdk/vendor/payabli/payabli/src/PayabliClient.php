<?php

namespace Payabli;

use Payabli\Bill\BillClient;
use Payabli\Boarding\BoardingClient;
use Payabli\ChargeBacks\ChargeBacksClient;
use Payabli\CheckCapture\CheckCaptureClient;
use Payabli\Cloud\CloudClient;
use Payabli\Customer\CustomerClient;
use Payabli\Export\ExportClient;
use Payabli\HostedPaymentPages\HostedPaymentPagesClient;
use Payabli\Import\ImportClient;
use Payabli\Invoice\InvoiceClient;
use Payabli\LineItem\LineItemClient;
use Payabli\MoneyIn\MoneyInClient;
use Payabli\MoneyOut\MoneyOutClient;
use Payabli\Notification\NotificationClient;
use Payabli\Notificationlogs\NotificationlogsClient;
use Payabli\Ocr\OcrClient;
use Payabli\Organization\OrganizationClient;
use Payabli\PaymentLink\PaymentLinkClient;
use Payabli\PaymentMethodDomain\PaymentMethodDomainClient;
use Payabli\Paypoint\PaypointClient;
use Payabli\Query\QueryClient;
use Payabli\Statistic\StatisticClient;
use Payabli\Subscription\SubscriptionClient;
use Payabli\Templates\TemplatesClient;
use Payabli\TokenStorage\TokenStorageClient;
use Payabli\User\UserClient;
use Payabli\Vendor\VendorClient;
use Payabli\Wallet\WalletClient;
use GuzzleHttp\ClientInterface;
use Payabli\Core\Client\RawClient;

class PayabliClient
{
    /**
     * @var BillClient $bill
     */
    public BillClient $bill;

    /**
     * @var BoardingClient $boarding
     */
    public BoardingClient $boarding;

    /**
     * @var ChargeBacksClient $chargeBacks
     */
    public ChargeBacksClient $chargeBacks;

    /**
     * @var CheckCaptureClient $checkCapture
     */
    public CheckCaptureClient $checkCapture;

    /**
     * @var CloudClient $cloud
     */
    public CloudClient $cloud;

    /**
     * @var CustomerClient $customer
     */
    public CustomerClient $customer;

    /**
     * @var ExportClient $export
     */
    public ExportClient $export;

    /**
     * @var HostedPaymentPagesClient $hostedPaymentPages
     */
    public HostedPaymentPagesClient $hostedPaymentPages;

    /**
     * @var ImportClient $import
     */
    public ImportClient $import;

    /**
     * @var InvoiceClient $invoice
     */
    public InvoiceClient $invoice;

    /**
     * @var LineItemClient $lineItem
     */
    public LineItemClient $lineItem;

    /**
     * @var MoneyInClient $moneyIn
     */
    public MoneyInClient $moneyIn;

    /**
     * @var MoneyOutClient $moneyOut
     */
    public MoneyOutClient $moneyOut;

    /**
     * @var NotificationClient $notification
     */
    public NotificationClient $notification;

    /**
     * @var NotificationlogsClient $notificationlogs
     */
    public NotificationlogsClient $notificationlogs;

    /**
     * @var OcrClient $ocr
     */
    public OcrClient $ocr;

    /**
     * @var OrganizationClient $organization
     */
    public OrganizationClient $organization;

    /**
     * @var PaymentLinkClient $paymentLink
     */
    public PaymentLinkClient $paymentLink;

    /**
     * @var PaymentMethodDomainClient $paymentMethodDomain
     */
    public PaymentMethodDomainClient $paymentMethodDomain;

    /**
     * @var PaypointClient $paypoint
     */
    public PaypointClient $paypoint;

    /**
     * @var QueryClient $query
     */
    public QueryClient $query;

    /**
     * @var StatisticClient $statistic
     */
    public StatisticClient $statistic;

    /**
     * @var SubscriptionClient $subscription
     */
    public SubscriptionClient $subscription;

    /**
     * @var TemplatesClient $templates
     */
    public TemplatesClient $templates;

    /**
     * @var TokenStorageClient $tokenStorage
     */
    public TokenStorageClient $tokenStorage;

    /**
     * @var UserClient $user
     */
    public UserClient $user;

    /**
     * @var VendorClient $vendor
     */
    public VendorClient $vendor;

    /**
     * @var WalletClient $wallet
     */
    public WalletClient $wallet;

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
     * @param ?string $apiKey The apiKey to use for authentication.
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        ?string $apiKey = null,
        ?array $options = null,
    ) {
        $defaultHeaders = [
            'X-Fern-Language' => 'PHP',
            'X-Fern-SDK-Name' => 'Payabli',
            'X-Fern-SDK-Version' => '0.0.376',
            'User-Agent' => 'payabli/payabli/0.0.376',
        ];
        if ($apiKey != null) {
            $defaultHeaders['requestToken'] = $apiKey;
        }

        $this->options = $options ?? [];
        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );

        $this->bill = new BillClient($this->client, $this->options);
        $this->boarding = new BoardingClient($this->client, $this->options);
        $this->chargeBacks = new ChargeBacksClient($this->client, $this->options);
        $this->checkCapture = new CheckCaptureClient($this->client, $this->options);
        $this->cloud = new CloudClient($this->client, $this->options);
        $this->customer = new CustomerClient($this->client, $this->options);
        $this->export = new ExportClient($this->client, $this->options);
        $this->hostedPaymentPages = new HostedPaymentPagesClient($this->client, $this->options);
        $this->import = new ImportClient($this->client, $this->options);
        $this->invoice = new InvoiceClient($this->client, $this->options);
        $this->lineItem = new LineItemClient($this->client, $this->options);
        $this->moneyIn = new MoneyInClient($this->client, $this->options);
        $this->moneyOut = new MoneyOutClient($this->client, $this->options);
        $this->notification = new NotificationClient($this->client, $this->options);
        $this->notificationlogs = new NotificationlogsClient($this->client, $this->options);
        $this->ocr = new OcrClient($this->client, $this->options);
        $this->organization = new OrganizationClient($this->client, $this->options);
        $this->paymentLink = new PaymentLinkClient($this->client, $this->options);
        $this->paymentMethodDomain = new PaymentMethodDomainClient($this->client, $this->options);
        $this->paypoint = new PaypointClient($this->client, $this->options);
        $this->query = new QueryClient($this->client, $this->options);
        $this->statistic = new StatisticClient($this->client, $this->options);
        $this->subscription = new SubscriptionClient($this->client, $this->options);
        $this->templates = new TemplatesClient($this->client, $this->options);
        $this->tokenStorage = new TokenStorageClient($this->client, $this->options);
        $this->user = new UserClient($this->client, $this->options);
        $this->vendor = new VendorClient($this->client, $this->options);
        $this->wallet = new WalletClient($this->client, $this->options);
    }
}
