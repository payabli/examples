<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OSection extends JsonSerializableType
{
    /**
     * @var ?CList $contactList
     */
    #[JsonProperty('contact_list')]
    public ?CList $contactList;

    /**
     * @var ?OList $ownList
     */
    #[JsonProperty('own_list')]
    public ?OList $ownList;

    /**
     * @param array{
     *   contactList?: ?CList,
     *   ownList?: ?OList,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactList = $values['contactList'] ?? null;
        $this->ownList = $values['ownList'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
