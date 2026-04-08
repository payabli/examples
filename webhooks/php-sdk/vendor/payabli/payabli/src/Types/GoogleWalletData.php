<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * The wallet data.
 */
class GoogleWalletData extends JsonSerializableType
{
    /**
     * @var ?string $gatewayMerchantId The Google Pay merchant identifier.
     */
    #[JsonProperty('gatewayMerchantId')]
    public ?string $gatewayMerchantId;

    /**
     * @var ?string $gatewayId The Google Pay gateway identifier.
     */
    #[JsonProperty('gatewayId')]
    public ?string $gatewayId;

    /**
     * @param array{
     *   gatewayMerchantId?: ?string,
     *   gatewayId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->gatewayMerchantId = $values['gatewayMerchantId'] ?? null;
        $this->gatewayId = $values['gatewayId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
