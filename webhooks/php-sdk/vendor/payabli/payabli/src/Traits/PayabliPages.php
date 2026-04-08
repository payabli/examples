<?php

namespace Payabli\Traits;

use Payabli\Types\PayabliCredentials;
use DateTime;
use Payabli\Types\PageContent;
use Payabli\Types\PageSetting;
use Payabli\Types\ReceiptContent;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;
use Payabli\Core\Types\Date;

/**
 * @property ?array<string, ?array<string, mixed>> $additionalData
 * @property ?array<PayabliCredentials> $credentials
 * @property ?DateTime $lastAccess
 * @property ?PageContent $pageContent
 * @property ?string $pageIdentifier
 * @property ?PageSetting $pageSettings
 * @property ?int $published
 * @property ?ReceiptContent $receiptContent
 * @property ?string $subdomain
 * @property ?float $totalAmount
 * @property ?string $validationCode
 */
trait PayabliPages
{
    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('AdditionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?array<PayabliCredentials> $credentials Array of credential objects with active services for the page
     */
    #[JsonProperty('credentials'), ArrayType([PayabliCredentials::class])]
    public ?array $credentials;

    /**
     * @var ?DateTime $lastAccess Timestamp of last access to page structure
     */
    #[JsonProperty('lastAccess'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastAccess;

    /**
     * @var ?PageContent $pageContent Sections of page
     */
    #[JsonProperty('pageContent')]
    public ?PageContent $pageContent;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var ?PageSetting $pageSettings Settings of page
     */
    #[JsonProperty('pageSettings')]
    public ?PageSetting $pageSettings;

    /**
     * @var ?int $published Flag indicating if page is active to accept payments. `0` for false, `1` for true.
     */
    #[JsonProperty('published')]
    public ?int $published;

    /**
     * @var ?ReceiptContent $receiptContent Sections of payment receipt
     */
    #[JsonProperty('receiptContent')]
    public ?ReceiptContent $receiptContent;

    /**
     * @var ?string $subdomain Page identifier. Must be unique in platform.
     */
    #[JsonProperty('subdomain')]
    public ?string $subdomain;

    /**
     * @var ?float $totalAmount Total amount to pay in this page
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?string $validationCode Base64 encoded image of CAPTCHA associated to this page load
     */
    #[JsonProperty('validationCode')]
    public ?string $validationCode;
}
