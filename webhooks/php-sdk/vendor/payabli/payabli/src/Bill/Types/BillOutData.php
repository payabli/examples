<?php

namespace Payabli\Bill\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\FileContent;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\BillItem;
use Payabli\Types\Frequency;
use Payabli\Types\VendorData;

class BillOutData extends JsonSerializableType
{
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
     * @var ?array<FileContent> $attachments An array of bill images. Attachments aren't required, but we strongly recommend including them. Including a bill image can make payouts smoother and prevent delays. You can include either the Base64-encoded file content, or you can include an fURL to a public file. The maximum file size for image uploads is 30 MB.
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

    /**
     * @var ?DateTime $billDate Date of bill. Accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('billDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $billDate;

    /**
     * @var ?array<BillItem> $billItems
     */
    #[JsonProperty('billItems'), ArrayType([BillItem::class])]
    public ?array $billItems;

    /**
     * @var ?string $billNumber Unique identifier for the bill. Required when adding a bill.
     */
    #[JsonProperty('billNumber')]
    public ?string $billNumber;

    /**
     * @var ?string $comments
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @var ?float $discount Discount amount applied to the bill.
     */
    #[JsonProperty('discount')]
    public ?float $discount;

    /**
     * @var ?DateTime $dueDate Due date of bill. Accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('dueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $dueDate;

    /**
     * @var ?DateTime $endDate End Date for scheduled bills. Applied only in `Mode` = 1. Accepted formats: YYYY-MM-DD, MM/DD/YYYY
     */
    #[JsonProperty('endDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $endDate;

    /**
     * @var ?value-of<Frequency> $frequency Frequency for scheduled bills. Applied only in `Mode` = 1.
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?string $lotNumber Lot number associated with the bill.
     */
    #[JsonProperty('lotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?int $mode Bill mode: value `0` for one-time bills, `1` for scheduled bills.
     */
    #[JsonProperty('mode')]
    public ?int $mode;

    /**
     * @var ?float $netAmount Net Amount owed in bill. Required when adding a bill.
     */
    #[JsonProperty('netAmount')]
    public ?float $netAmount;

    /**
     * @var ?BillOutDataScheduledOptions $scheduledOptions Options for scheduled bills.
     */
    #[JsonProperty('scheduledOptions')]
    public ?BillOutDataScheduledOptions $scheduledOptions;

    /**
     * @var ?int $status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?string $terms
     */
    #[JsonProperty('terms')]
    public ?string $terms;

    /**
     * @var ?float $totalAmount Total amount of the bill.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?VendorData $vendor The vendor associated with the bill. Although you can create a vendor in a create bill request, Payabli recommends creating a vendor separately and passing a valid `vendorNumber` here. At minimum, the `vendorNumber` is required.
     */
    #[JsonProperty('vendor')]
    public ?VendorData $vendor;

    /**
     * @param array{
     *   accountingField1?: ?string,
     *   accountingField2?: ?string,
     *   additionalData?: ?string,
     *   attachments?: ?array<FileContent>,
     *   billDate?: ?DateTime,
     *   billItems?: ?array<BillItem>,
     *   billNumber?: ?string,
     *   comments?: ?string,
     *   discount?: ?float,
     *   dueDate?: ?DateTime,
     *   endDate?: ?DateTime,
     *   frequency?: ?value-of<Frequency>,
     *   lotNumber?: ?string,
     *   mode?: ?int,
     *   netAmount?: ?float,
     *   scheduledOptions?: ?BillOutDataScheduledOptions,
     *   status?: ?int,
     *   terms?: ?string,
     *   totalAmount?: ?float,
     *   vendor?: ?VendorData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountingField1 = $values['accountingField1'] ?? null;
        $this->accountingField2 = $values['accountingField2'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
        $this->billDate = $values['billDate'] ?? null;
        $this->billItems = $values['billItems'] ?? null;
        $this->billNumber = $values['billNumber'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->endDate = $values['endDate'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->scheduledOptions = $values['scheduledOptions'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->terms = $values['terms'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
