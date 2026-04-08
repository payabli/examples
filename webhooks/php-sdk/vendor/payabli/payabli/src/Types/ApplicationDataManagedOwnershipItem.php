<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\Owners;

class ApplicationDataManagedOwnershipItem extends JsonSerializableType
{
    use Owners;


    /**
     * @param array{
     *   ownername?: ?string,
     *   ownertitle?: ?string,
     *   ownerpercent?: ?int,
     *   ownerssn?: ?string,
     *   ownerdob?: ?string,
     *   ownerphone1?: ?string,
     *   ownerphone2?: ?string,
     *   owneremail?: ?string,
     *   ownerdriver?: ?string,
     *   oaddress?: ?string,
     *   ocity?: ?string,
     *   ocountry?: ?string,
     *   odriverstate?: ?string,
     *   ostate?: ?string,
     *   ozip?: ?string,
     *   additionalData?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ownername = $values['ownername'] ?? null;
        $this->ownertitle = $values['ownertitle'] ?? null;
        $this->ownerpercent = $values['ownerpercent'] ?? null;
        $this->ownerssn = $values['ownerssn'] ?? null;
        $this->ownerdob = $values['ownerdob'] ?? null;
        $this->ownerphone1 = $values['ownerphone1'] ?? null;
        $this->ownerphone2 = $values['ownerphone2'] ?? null;
        $this->owneremail = $values['owneremail'] ?? null;
        $this->ownerdriver = $values['ownerdriver'] ?? null;
        $this->oaddress = $values['oaddress'] ?? null;
        $this->ocity = $values['ocity'] ?? null;
        $this->ocountry = $values['ocountry'] ?? null;
        $this->odriverstate = $values['odriverstate'] ?? null;
        $this->ostate = $values['ostate'] ?? null;
        $this->ozip = $values['ozip'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
