<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Response object for bill details. Contains basic information about a bill.
 */
class BillDetailsResponse extends JsonSerializableType
{
    /**
     * @var ?int $billId
     */
    #[JsonProperty('billId')]
    public ?int $billId;

    /**
     * @var ?string $lotNumber Lot number of the bill.
     */
    #[JsonProperty('lotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?string $invoiceNumber Custom number identifying the bill.
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
     * @var ?string $comments Any comments about bill. **For managed payouts, this field has a limit of 100 characters**.
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @param array{
     *   billId?: ?int,
     *   lotNumber?: ?string,
     *   invoiceNumber?: ?string,
     *   netAmount?: ?string,
     *   discount?: ?string,
     *   dueDate?: ?DateTime,
     *   invoiceDate?: ?DateTime,
     *   comments?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->billId = $values['billId'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
        $this->invoiceNumber = $values['invoiceNumber'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->invoiceDate = $values['invoiceDate'] ?? null;
        $this->comments = $values['comments'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
