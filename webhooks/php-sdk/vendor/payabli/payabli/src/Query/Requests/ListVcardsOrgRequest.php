<?php

namespace Payabli\Query\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ExportFormat;

class ListVcardsOrgRequest extends JsonSerializableType
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
     * Collection of field names, conditions, and values used to filter the query.
     * <Info>
     *   **You must remove `parameters=` from the request before you send it, otherwise Payabli will ignore the filters.**
     *
     *   Because of a technical limitation, you can't make a request that includes filters from the API console on this page. The response won't be filtered. Instead, copy the request, remove `parameters=` and run the request in a different client.
     *
     *   For example:
     *
     *   --url https://api-sandbox.payabli.com/api/Query/transactions/org/236?parameters=totalAmount(gt)=1000&limitRecord=20
     *
     *   should become:
     *
     *   --url https://api-sandbox.payabli.com/api/Query/transactions/org/236?totalAmount(gt)=1000&limitRecord=20
     * </Info>
     * List of field names accepted:
     *
     *   - `status` (in, nin, eq, ne)
     *   - `createdAt` (gt, ge, lt, le, eq, ne)
     *   - `cardToken` (ct, nct, eq, ne)
     *   - `lastFour` (ct, nct, eq, ne)
     *   - `expirationDate` (ct, nct, eq, ne)
     *   - `payoutId` (ct, nct, eq, ne, in, nin)
     *   - `vendorId` (ct, nct, eq, ne, in, nin)
     *   - `miscData1` (ct, nct, eq, ne)
     *   - `miscData2` (ct, nct, eq, ne)
     *   - `currentUses` (gt, ge, lt, le, eq, ne)
     *   - `amount` (gt, ge, lt, le, eq, ne)
     *   - `balance` (gt, ge, lt, le, eq, ne)
     *   - `paypointLegal` (ne, eq, ct, nct)
     *   - `paypointDba` (ne, eq, ct, nct)
     *   - `orgName` (ne, eq, ct, nct)
     *   - `externalPaypointId` (ct, nct, eq, ne)
     *   - `paypointId` (in, nin, eq, ne)
     *
     * List of comparison accepted - enclosed between parentheses:
     *
     *   - eq or empty => equal
     *   - gt => greater than
     *   - ge => greater or equal
     *   - lt => less than
     *   - le => less or equal
     *   - ne => not equal
     *   - ct => contains
     *   - nct => not contains
     *   - in => inside array separated by "|"
     *   - nin => not inside array separated by "|"
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
