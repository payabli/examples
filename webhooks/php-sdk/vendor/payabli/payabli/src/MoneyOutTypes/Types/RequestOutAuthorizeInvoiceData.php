<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\FileContent;
use Payabli\Core\Types\ArrayType;

class RequestOutAuthorizeInvoiceData extends JsonSerializableType
{
    /**
     * @var ?string $invoiceNumber
     */
    #[JsonProperty('invoiceNumber')]
    public ?string $invoiceNumber;

    /**
     * @var ?string $netAmount
     */
    #[JsonProperty('netAmount')]
    public ?string $netAmount;

    /**
     * @var ?DateTime $invoiceDate Invoice date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDate;

    /**
     * @var ?DateTime $dueDate Invoice due date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('dueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $dueDate;

    /**
     * @var ?string $comments
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @var ?string $lotNumber
     */
    #[JsonProperty('lotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?int $billId
     */
    #[JsonProperty('billId')]
    public ?int $billId;

    /**
     * @var ?float $discount
     */
    #[JsonProperty('discount')]
    public ?float $discount;

    /**
     * @var ?string $terms
     */
    #[JsonProperty('terms')]
    public ?string $terms;

    /**
     * @var ?string $accountingField1
     */
    #[JsonProperty('accountingField1')]
    public ?string $accountingField1;

    /**
     * @var ?string $accountingField2
     */
    #[JsonProperty('accountingField2')]
    public ?string $accountingField2;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

    /**
     * @var ?array<FileContent> $attachments
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

    /**
     * @param array{
     *   invoiceNumber?: ?string,
     *   netAmount?: ?string,
     *   invoiceDate?: ?DateTime,
     *   dueDate?: ?DateTime,
     *   comments?: ?string,
     *   lotNumber?: ?string,
     *   billId?: ?int,
     *   discount?: ?float,
     *   terms?: ?string,
     *   accountingField1?: ?string,
     *   accountingField2?: ?string,
     *   additionalData?: ?string,
     *   attachments?: ?array<FileContent>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->invoiceNumber = $values['invoiceNumber'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->invoiceDate = $values['invoiceDate'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
        $this->billId = $values['billId'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->terms = $values['terms'] ?? null;
        $this->accountingField1 = $values['accountingField1'] ?? null;
        $this->accountingField2 = $values['accountingField2'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
