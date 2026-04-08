<?php

namespace Payabli\Query\Requests;

use Payabli\Core\Json\JsonSerializableType;

class ListNotificationsRequest extends JsonSerializableType
{
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
     * See [Filters and Conditions Reference](/developers/developer-guides/pay-ops-reporting-engine-overview#filters-and-conditions-reference) for help.
     *
     * List of field names accepted:
     * - `frequency` (in, nin,ne, eq)
     * - `method` (in, nin, eq, ne)
     * - `event` (in, nin, eq, ne)
     * - `target` (ct, nct, eq, ne)
     * - `status` (eq, ne)
     *
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
     * List of parameters accepted:
     * - limitRecord : max number of records for query (default="20", "0" or negative value for all)
     * - fromRecord : initial record in query
     *
     * Example: totalAmount(gt)=20  return all records with totalAmount greater than 20.00
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
     *   fromRecord?: ?int,
     *   limitRecord?: ?int,
     *   parameters?: ?array<string, ?string>,
     *   sortBy?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->fromRecord = $values['fromRecord'] ?? null;
        $this->limitRecord = $values['limitRecord'] ?? null;
        $this->parameters = $values['parameters'] ?? null;
        $this->sortBy = $values['sortBy'] ?? null;
    }
}
