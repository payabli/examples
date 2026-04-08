<?php

namespace Payabli\Invoice\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ExportFormat;

class ListInvoicesOrgRequest extends JsonSerializableType
{
    /**
     * @var ?value-of<ExportFormat> $exportFormat
     */
    public ?string $exportFormat;

    /**
     * @var ?int $fromRecord The number of records to skip before starting to collect the result set.
     */
    public ?int $fromRecord;

    /**
     * @var ?int $limitRecord Max number of records to return for the query. Use `0` or negative value to return all records.
     */
    public ?int $limitRecord;

    /**
     * Collection of field names, conditions, and values used to filter the query
     *
     * See [Filters and Conditions Reference](/developers/developer-guides/pay-ops-reporting-engine-overview#filters-and-conditions-reference) for help.
     *
     * List of field names accepted:
     *
     * - `invoiceDate` (gt, ge, lt, le, eq, ne)
     * - `dueDate` (gt, ge, lt, le, eq, ne)
     * - `sentDate` (gt, ge, lt, le, eq, ne)
     * - `frequency` (in, nin,ne, eq)
     * - `invoiceType` (eq, ne)
     * - `payTerms` (in, nin, eq, ne)
     * - `paypointId` (ne, eq)
     * - `totalAmount` (gt, ge, lt, le, eq, ne)
     * - `paidAmount` (gt, ge, lt, le, eq, ne)
     * - `status` (in, nin, eq, ne)
     * - `invoiceNumber` (ct, nct, eq, ne)
     * - `purchaseOrder` (ct, nct, eq, ne)
     * - `itemProductCode` (ct, nct)
     * - `itemDescription` (ct, nct)
     * - `customerFirstname` (ct, nct, eq, ne)
     * - `customerLastname` (ct, nct, eq, ne)
     * - `customerName` (ct, nct)
     * - `customerId` (eq, ne)
     * - `customerNumber` (ct, nct, eq, ne)
     * - `customerCompanyname` (ct, nct, eq, ne)
     * - `customerAddress` (ct, nct, eq, ne)
     * - `customerCity` (ct, nct, eq, ne)
     * - `customerZip` (ct, nct, eq, ne)
     * - `customerState` (ct, nct, eq, ne)
     * - `customerCountry` (ct, nct, eq, ne)
     * - `customerPhone` (ct, nct, eq, ne)
     * - `customerEmail` (ct, nct, eq, ne)
     * - `customerShippingAddress` (ct, nct, eq, ne)
     * - `customerShippingCity` (ct, nct, eq, ne)
     * - `customerShippingZip` (ct, nct, eq, ne)
     * - `customerShippingState` (ct, nct, eq, ne)
     * - `customerShippingCountry` (ct, nct, eq, ne)
     * - `orgId` (eq)
     * - `paylinkId` (ne, eq)
     * - `paypointLegal` (ne, eq, ct, nct)
     * - `paypointDba` (ne, eq, ct, nct)
     * - `orgName` (ne, eq, ct, nct)
     * - `additional-xxx` (ne, eq, ct, nct) where xxx is the additional field name
     *
     * List of comparison accepted - enclosed between parentheses:
     *
     * - eq or empty => equal
     * - gt => greater than
     * - ge => greater or equal
     * - lt => less than
     * - le => less or equal
     * - ne => not equal
     * - ct => contains
     * - nct => not contains
     * - in => inside array
     * - nin => not inside array
     *
     * List of parameters accepted:
     * - limitRecord : max number of records for query (default="20", "0" or negative value for all)
     * - fromRecord : initial record in query
     *
     * Example: totalAmount(gt)=20 return all records with totalAmount greater than 20.00
     *
     * @var ?array<string, ?string> $parameters
     */
    public ?array $parameters;

    /**
     * @var ?string $sortBy The field name to use for sorting results. Use `desc(field_name)` to sort descending by `field_name`, and use `asc(field_name)` to sort ascending by `field_name`.
     */
    public ?string $sortBy;

    /**
     * @param array{
     *   exportFormat?: ?value-of<ExportFormat>,
     *   fromRecord?: ?int,
     *   limitRecord?: ?int,
     *   parameters?: ?array<string, ?string>,
     *   sortBy?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->exportFormat = $values['exportFormat'] ?? null;
        $this->fromRecord = $values['fromRecord'] ?? null;
        $this->limitRecord = $values['limitRecord'] ?? null;
        $this->parameters = $values['parameters'] ?? null;
        $this->sortBy = $values['sortBy'] ?? null;
    }
}
