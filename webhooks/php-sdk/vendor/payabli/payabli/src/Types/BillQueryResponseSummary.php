<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BillQueryResponseSummary extends JsonSerializableType
{
    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?int $pageSize
     */
    #[JsonProperty('pageSize')]
    public ?int $pageSize;

    /**
     * @var ?int $total2Approval
     */
    #[JsonProperty('total2approval')]
    public ?int $total2Approval;

    /**
     * @var ?int $totalactive
     */
    #[JsonProperty('totalactive')]
    public ?int $totalactive;

    /**
     * @var ?float $totalAmount Total amount of bills in response.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?float $totalamount2Approval
     */
    #[JsonProperty('totalamount2approval')]
    public ?float $totalamount2Approval;

    /**
     * @var ?float $totalamountactive
     */
    #[JsonProperty('totalamountactive')]
    public ?float $totalamountactive;

    /**
     * @var ?float $totalamountapproved The total amount of approved bills.
     */
    #[JsonProperty('totalamountapproved')]
    public ?float $totalamountapproved;

    /**
     * @var ?float $totalamountcancel
     */
    #[JsonProperty('totalamountcancel')]
    public ?float $totalamountcancel;

    /**
     * @var ?float $totalamountdisapproved The total amount of disapproved bills.
     */
    #[JsonProperty('totalamountdisapproved')]
    public ?float $totalamountdisapproved;

    /**
     * @var ?float $totalamountintransit
     */
    #[JsonProperty('totalamountintransit')]
    public ?float $totalamountintransit;

    /**
     * @var ?float $totalamountoverdue The total amount of bills that are overdue.
     */
    #[JsonProperty('totalamountoverdue')]
    public ?float $totalamountoverdue;

    /**
     * @var ?float $totalamountpaid The total amount of paid bills.
     */
    #[JsonProperty('totalamountpaid')]
    public ?float $totalamountpaid;

    /**
     * @var ?float $totalamountsent2Approval
     */
    #[JsonProperty('totalamountsent2approval')]
    public ?float $totalamountsent2Approval;

    /**
     * @var ?int $totalapproved The total number of bills that were approved.
     */
    #[JsonProperty('totalapproved')]
    public ?int $totalapproved;

    /**
     * @var ?int $totalcancel
     */
    #[JsonProperty('totalcancel')]
    public ?int $totalcancel;

    /**
     * @var ?int $totaldisapproved The number of bills that were disapproved.
     */
    #[JsonProperty('totaldisapproved')]
    public ?int $totaldisapproved;

    /**
     * @var ?int $totalintransit
     */
    #[JsonProperty('totalintransit')]
    public ?int $totalintransit;

    /**
     * @var ?int $totaloverdue The number of bills that are overdue.
     */
    #[JsonProperty('totaloverdue')]
    public ?int $totaloverdue;

    /**
     * @var ?int $totalPages
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $totalpaid The total number of paid bills.
     */
    #[JsonProperty('totalpaid')]
    public ?int $totalpaid;

    /**
     * @var ?int $totalRecords
     */
    #[JsonProperty('totalRecords')]
    public ?int $totalRecords;

    /**
     * @var ?int $totalsent2Approval
     */
    #[JsonProperty('totalsent2approval')]
    public ?int $totalsent2Approval;

    /**
     * @param array{
     *   pageidentifier?: ?string,
     *   pageSize?: ?int,
     *   total2Approval?: ?int,
     *   totalactive?: ?int,
     *   totalAmount?: ?float,
     *   totalamount2Approval?: ?float,
     *   totalamountactive?: ?float,
     *   totalamountapproved?: ?float,
     *   totalamountcancel?: ?float,
     *   totalamountdisapproved?: ?float,
     *   totalamountintransit?: ?float,
     *   totalamountoverdue?: ?float,
     *   totalamountpaid?: ?float,
     *   totalamountsent2Approval?: ?float,
     *   totalapproved?: ?int,
     *   totalcancel?: ?int,
     *   totaldisapproved?: ?int,
     *   totalintransit?: ?int,
     *   totaloverdue?: ?int,
     *   totalPages?: ?int,
     *   totalpaid?: ?int,
     *   totalRecords?: ?int,
     *   totalsent2Approval?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
        $this->total2Approval = $values['total2Approval'] ?? null;
        $this->totalactive = $values['totalactive'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalamount2Approval = $values['totalamount2Approval'] ?? null;
        $this->totalamountactive = $values['totalamountactive'] ?? null;
        $this->totalamountapproved = $values['totalamountapproved'] ?? null;
        $this->totalamountcancel = $values['totalamountcancel'] ?? null;
        $this->totalamountdisapproved = $values['totalamountdisapproved'] ?? null;
        $this->totalamountintransit = $values['totalamountintransit'] ?? null;
        $this->totalamountoverdue = $values['totalamountoverdue'] ?? null;
        $this->totalamountpaid = $values['totalamountpaid'] ?? null;
        $this->totalamountsent2Approval = $values['totalamountsent2Approval'] ?? null;
        $this->totalapproved = $values['totalapproved'] ?? null;
        $this->totalcancel = $values['totalcancel'] ?? null;
        $this->totaldisapproved = $values['totaldisapproved'] ?? null;
        $this->totalintransit = $values['totalintransit'] ?? null;
        $this->totaloverdue = $values['totaloverdue'] ?? null;
        $this->totalPages = $values['totalPages'] ?? null;
        $this->totalpaid = $values['totalpaid'] ?? null;
        $this->totalRecords = $values['totalRecords'] ?? null;
        $this->totalsent2Approval = $values['totalsent2Approval'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
