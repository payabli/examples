<?php

namespace Payabli\Query\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ExportFormat;

class ListTransfersPaypointRequest extends JsonSerializableType
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
     * @var ?int $limitRecord
     */
    public ?int $limitRecord;

    /**
     *
     * Collection of field names, conditions, and values used to filter
     * the query.
     *
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
     *
     * See [Filters and Conditions
     * Reference](/developers/developer-guides/pay-ops-reporting-engine-overview#filters-and-conditions-reference)
     * for more information.
     *
     *
     * **List of field names accepted:**
     *
     *   - `grossAmount` (gt, ge, lt, le, eq, ne)
     *   - `chargeBackAmount` (gt, ge, lt, le, eq, ne)
     *   - `returnedAmount` (gt, ge, lt, le, eq, ne)
     *   - `billingFeeAmount` (gt, ge, lt, le, eq, ne)
     *   - `thirdPartyPaidAmount` (gt, ge, lt, le, eq, ne)
     *   - `netFundedAmount` (gt, ge, lt, le, eq, ne)
     *   - `adjustmentAmount` (gt, ge, lt, le, eq, ne)
     *   - `splitFundingAmount` (gt, ge, lt, le, eq, ne)
     *   - `operation` (in, nin, eq, ne)
     *   - `transactionId` (eq, ne, in, nin)
     *   - `category` (eq, ne, ct, nct)
     *   - `type` (eq, ne, in, nin)
     *   - `method` (eq, ne, in, nin)
     *
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
