<?php

namespace Payabli\MoneyOut\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SendVCardLinkRequest extends JsonSerializableType
{
    /**
     * @var string $transId The transaction ID of the virtual card payout. The ID is returned as `ReferenceId` in the response when you authorize a payout with POST /MoneyOut/authorize.
     */
    #[JsonProperty('transId')]
    public string $transId;

    /**
     * @param array{
     *   transId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->transId = $values['transId'];
    }
}
