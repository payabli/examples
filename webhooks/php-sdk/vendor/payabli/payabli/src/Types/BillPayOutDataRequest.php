<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class BillPayOutDataRequest extends JsonSerializableType
{
    /**
     * @var ?int $billId Bill ID in Payabli.
     */
    #[JsonProperty('billId')]
    public ?int $billId;

    /**
     * @var ?string $comments Any comments about bill. **For managed payouts, this field has a limit of 100 characters**.
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @var ?DateTime $dueDate Bill due date in format YYYY-MM-DD or MM/DD/YYYY.
     */
    #[JsonProperty('dueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $dueDate;

    /**
     * @var ?DateTime $invoiceDate Bill date in format YYYY-MM-DD or MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDate;

    /**
     * @var ?string $invoiceNumber Custom number identifying the bill. Must be unique in paypoint. **Required** for new bill and when `billId` isn't provided.
     */
    #[JsonProperty('invoiceNumber')]
    public ?string $invoiceNumber;

    /**
     * @var ?string $netAmount Net Amount owed in bill. Required when adding a bill.
     */
    #[JsonProperty('netAmount')]
    public ?string $netAmount;

    /**
     * @var ?string $discount Bill discount amount.
     */
    #[JsonProperty('discount')]
    public ?string $discount;

    /**
     * @var ?string $terms Description of payment terms.
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
     * @var ?array<FileContent> $attachments Bill image attachment. Send the bill image as Base64-encoded string, or as a publicly accessible link. For full details on using this field with a payout authorization, see [the documentation](/developers/developer-guides/pay-out-manage-payouts).
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

    /**
     * @param array{
     *   billId?: ?int,
     *   comments?: ?string,
     *   dueDate?: ?DateTime,
     *   invoiceDate?: ?DateTime,
     *   invoiceNumber?: ?string,
     *   netAmount?: ?string,
     *   discount?: ?string,
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
        $this->billId = $values['billId'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->invoiceDate = $values['invoiceDate'] ?? null;
        $this->invoiceNumber = $values['invoiceNumber'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
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
