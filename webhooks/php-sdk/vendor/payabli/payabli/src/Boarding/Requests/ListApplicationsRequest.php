<?php

namespace Payabli\Boarding\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ExportFormat;

class ListApplicationsRequest extends JsonSerializableType
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
     * - `createdAt` (gt, ge, lt, le, eq, ne)
     * - `startDate` (gt, ge, lt, le, eq, ne)
     * - `dbaname` (ct, nct)
     * - `legalname` (ct, nct)
     * - `ein` (ct, nct)
     * - `address` (ct, nct)
     * - `city` (ct, nct)
     * - `state` (ct, nct)
     * - `phone` (ct, nct)
     * - `mcc` (ct, nct)
     * - `owntype` (ct, nct)
     * - `ownerName` (ct, nct)
     * - `contactName` (ct, nct)
     * - `status` (in, nin, eq,ne)
     * - `orgParentname` (ct, nct)
     * - `externalpaypointID` (ct, nct, eq, ne)
     * - `repCode` (ct, nct, eq, ne)
     * - `repName` (ct, nct, eq, ne)
     * - `repOffice` (ct, nct, eq, ne)
     * List of comparison accepted - enclosed between parentheses:
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
