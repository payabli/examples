<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class OcrResultData extends JsonSerializableType
{
    /**
     * @var ?string $billNumber
     */
    #[JsonProperty('billNumber')]
    public ?string $billNumber;

    /**
     * @var ?float $netAmount
     */
    #[JsonProperty('netAmount')]
    public ?float $netAmount;

    /**
     * @var ?DateTime $billDate
     */
    #[JsonProperty('billDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $billDate;

    /**
     * @var ?DateTime $dueDate
     */
    #[JsonProperty('dueDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $dueDate;

    /**
     * @var ?string $comments
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @var ?array<OcrBillItem> $billItems
     */
    #[JsonProperty('billItems'), ArrayType([OcrBillItem::class])]
    public ?array $billItems;

    /**
     * @var ?int $mode
     */
    #[JsonProperty('mode')]
    public ?int $mode;

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
     * @var ?OcrBillItemAdditionalData $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?OcrBillItemAdditionalData $additionalData;

    /**
     * @var ?OcrVendor $vendor
     */
    #[JsonProperty('vendor')]
    public ?OcrVendor $vendor;

    /**
     * @var ?DateTime $endDate
     */
    #[JsonProperty('endDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $endDate;

    /**
     * @var ?string $frequency
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?string $terms
     */
    #[JsonProperty('terms')]
    public ?string $terms;

    /**
     * @var ?int $status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?string $lotNumber
     */
    #[JsonProperty('lotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?array<OcrAttachment> $attachments
     */
    #[JsonProperty('attachments'), ArrayType([OcrAttachment::class])]
    public ?array $attachments;

    /**
     * @param array{
     *   billNumber?: ?string,
     *   netAmount?: ?float,
     *   billDate?: ?DateTime,
     *   dueDate?: ?DateTime,
     *   comments?: ?string,
     *   billItems?: ?array<OcrBillItem>,
     *   mode?: ?int,
     *   accountingField1?: ?string,
     *   accountingField2?: ?string,
     *   additionalData?: ?OcrBillItemAdditionalData,
     *   vendor?: ?OcrVendor,
     *   endDate?: ?DateTime,
     *   frequency?: ?string,
     *   terms?: ?string,
     *   status?: ?int,
     *   lotNumber?: ?string,
     *   attachments?: ?array<OcrAttachment>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->billNumber = $values['billNumber'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->billDate = $values['billDate'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->billItems = $values['billItems'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->accountingField1 = $values['accountingField1'] ?? null;
        $this->accountingField2 = $values['accountingField2'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
        $this->endDate = $values['endDate'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->terms = $values['terms'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
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
