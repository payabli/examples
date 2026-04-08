<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\Bank;

class ApplicationDataPayInBankData extends JsonSerializableType
{
    use Bank;


    /**
     * @param array{
     *   id?: ?int,
     *   accountId?: ?string,
     *   nickname?: ?string,
     *   bankName?: ?string,
     *   routingAccount?: ?string,
     *   accountNumber?: ?string,
     *   typeAccount?: ?value-of<TypeAccount>,
     *   bankAccountHolderName?: ?string,
     *   bankAccountHolderType?: ?value-of<BankAccountHolderType>,
     *   bankAccountFunction?: ?int,
     *   verified?: ?bool,
     *   status?: ?int,
     *   services?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->accountId = $values['accountId'] ?? null;
        $this->nickname = $values['nickname'] ?? null;
        $this->bankName = $values['bankName'] ?? null;
        $this->routingAccount = $values['routingAccount'] ?? null;
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->typeAccount = $values['typeAccount'] ?? null;
        $this->bankAccountHolderName = $values['bankAccountHolderName'] ?? null;
        $this->bankAccountHolderType = $values['bankAccountHolderType'] ?? null;
        $this->bankAccountFunction = $values['bankAccountFunction'] ?? null;
        $this->verified = $values['verified'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->services = $values['services'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
