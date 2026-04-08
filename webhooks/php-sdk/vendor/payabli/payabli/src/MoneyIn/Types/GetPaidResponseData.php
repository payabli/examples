<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Response data for GetPaid transactions
 */
class GetPaidResponseData extends JsonSerializableType
{
    /**
     * @var ?string $authCode
     */
    #[JsonProperty('authCode')]
    public ?string $authCode;

    /**
     * @var string $referenceId
     */
    #[JsonProperty('referenceId')]
    public string $referenceId;

    /**
     * @var int $resultCode
     */
    #[JsonProperty('resultCode')]
    public int $resultCode;

    /**
     * @var string $resultText
     */
    #[JsonProperty('resultText')]
    public string $resultText;

    /**
     * @var string $avsResponseText
     */
    #[JsonProperty('avsResponseText')]
    public string $avsResponseText;

    /**
     * @var string $cvvResponseText
     */
    #[JsonProperty('cvvResponseText')]
    public string $cvvResponseText;

    /**
     * @var int $customerId
     */
    #[JsonProperty('customerId')]
    public int $customerId;

    /**
     * @var ?string $methodReferenceId
     */
    #[JsonProperty('methodReferenceId')]
    public ?string $methodReferenceId;

    /**
     * @param array{
     *   referenceId: string,
     *   resultCode: int,
     *   resultText: string,
     *   avsResponseText: string,
     *   cvvResponseText: string,
     *   customerId: int,
     *   authCode?: ?string,
     *   methodReferenceId?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->authCode = $values['authCode'] ?? null;
        $this->referenceId = $values['referenceId'];
        $this->resultCode = $values['resultCode'];
        $this->resultText = $values['resultText'];
        $this->avsResponseText = $values['avsResponseText'];
        $this->cvvResponseText = $values['cvvResponseText'];
        $this->customerId = $values['customerId'];
        $this->methodReferenceId = $values['methodReferenceId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
