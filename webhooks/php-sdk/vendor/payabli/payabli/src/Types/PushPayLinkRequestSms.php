<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;

class PushPayLinkRequestSms extends JsonSerializableType
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
