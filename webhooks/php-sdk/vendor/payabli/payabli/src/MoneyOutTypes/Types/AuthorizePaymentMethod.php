<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\AchHolderType;

/**
 * Payment method object for vendor payouts.
 * - `{ method: "managed" }` - Managed payment method
 * - `{ method: "vcard" }` - Virtual card payment method
 * - `{ method: "check" }` - Check payment method
 * - `{ method: "ach", achHolder: "...", achRouting: "...", achAccount: "...", achAccountType: "..." }` - ACH payment method with bank details
 * - `{ method: "ach", storedMethodId: "..." }` - ACH payment method using stored method ID
 */
class AuthorizePaymentMethod extends JsonSerializableType
{
    /**
     * @var string $method Payment method type - "managed", "vcard", "check", or "ach"
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?string $achHolder Account holder name for ACH payments. Required when method is "ach" and not using `storedMethodId`.
     */
    #[JsonProperty('achHolder')]
    public ?string $achHolder;

    /**
     * @var ?string $achRouting Bank routing number for ACH payments. Required when method is "ach" and not using `storedMethodId`.
     */
    #[JsonProperty('achRouting')]
    public ?string $achRouting;

    /**
     * @var ?string $achAccount Bank account number for ACH payments. Required when method is "ach" and not using `storedMethodId`.
     */
    #[JsonProperty('achAccount')]
    public ?string $achAccount;

    /**
     * @var ?string $achAccountType Account type for ACH payments ("checking" or "savings"). Required when method is "ach" and not using `storedMethodId`.
     */
    #[JsonProperty('achAccountType')]
    public ?string $achAccountType;

    /**
     * @var ?string $achCode
     */
    #[JsonProperty('achCode')]
    public ?string $achCode;

    /**
     * @var ?value-of<AchHolderType> $achHolderType
     */
    #[JsonProperty('achHolderType')]
    public ?string $achHolderType;

    /**
     * @var ?string $storedMethodId ID of the stored ACH payment method. Only applicable when method is `ach`. Use this to reference a previously saved ACH method instead of providing bank details directly.
     */
    #[JsonProperty('storedMethodId')]
    public ?string $storedMethodId;

    /**
     * @var ?string $initiator
     */
    #[JsonProperty('initiator')]
    public ?string $initiator;

    /**
     * @var ?string $storedMethodUsageType
     */
    #[JsonProperty('storedMethodUsageType')]
    public ?string $storedMethodUsageType;

    /**
     * @param array{
     *   method: string,
     *   achHolder?: ?string,
     *   achRouting?: ?string,
     *   achAccount?: ?string,
     *   achAccountType?: ?string,
     *   achCode?: ?string,
     *   achHolderType?: ?value-of<AchHolderType>,
     *   storedMethodId?: ?string,
     *   initiator?: ?string,
     *   storedMethodUsageType?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
        $this->achHolder = $values['achHolder'] ?? null;
        $this->achRouting = $values['achRouting'] ?? null;
        $this->achAccount = $values['achAccount'] ?? null;
        $this->achAccountType = $values['achAccountType'] ?? null;
        $this->achCode = $values['achCode'] ?? null;
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->storedMethodId = $values['storedMethodId'] ?? null;
        $this->initiator = $values['initiator'] ?? null;
        $this->storedMethodUsageType = $values['storedMethodUsageType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
