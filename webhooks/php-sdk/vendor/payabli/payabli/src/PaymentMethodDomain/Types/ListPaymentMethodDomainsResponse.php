<?php

namespace Payabli\PaymentMethodDomain\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PaymentMethodDomainApiResponse;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\QuerySummaryNoAmt;

class ListPaymentMethodDomainsResponse extends JsonSerializableType
{
    /**
     * @var array<PaymentMethodDomainApiResponse> $records
     */
    #[JsonProperty('records'), ArrayType([PaymentMethodDomainApiResponse::class])]
    public array $records;

    /**
     * @var QuerySummaryNoAmt $summary
     */
    #[JsonProperty('summary')]
    public QuerySummaryNoAmt $summary;

    /**
     * @param array{
     *   records: array<PaymentMethodDomainApiResponse>,
     *   summary: QuerySummaryNoAmt,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->records = $values['records'];
        $this->summary = $values['summary'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
