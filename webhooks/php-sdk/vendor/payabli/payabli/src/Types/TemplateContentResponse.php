<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TemplateContentResponse extends JsonSerializableType
{
    /**
     * @var ?BusinessSection $businessData
     */
    #[JsonProperty('businessData')]
    public ?BusinessSection $businessData;

    /**
     * @var ?DocumentSection $documentsData
     */
    #[JsonProperty('documentsData')]
    public ?DocumentSection $documentsData;

    /**
     * @var ?OwnersSection $ownershipData
     */
    #[JsonProperty('ownershipData')]
    public ?OwnersSection $ownershipData;

    /**
     * @var ?ProcessingSection $processingData
     */
    #[JsonProperty('processingData')]
    public ?ProcessingSection $processingData;

    /**
     * @var ?SalesSection $salesData
     */
    #[JsonProperty('salesData')]
    public ?SalesSection $salesData;

    /**
     * @var ?ServicesSection $servicesData
     */
    #[JsonProperty('servicesData')]
    public ?ServicesSection $servicesData;

    /**
     * @var ?UnderwritingDataResponse $underwritingData
     */
    #[JsonProperty('underwritingData')]
    public ?UnderwritingDataResponse $underwritingData;

    /**
     * @param array{
     *   businessData?: ?BusinessSection,
     *   documentsData?: ?DocumentSection,
     *   ownershipData?: ?OwnersSection,
     *   processingData?: ?ProcessingSection,
     *   salesData?: ?SalesSection,
     *   servicesData?: ?ServicesSection,
     *   underwritingData?: ?UnderwritingDataResponse,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->businessData = $values['businessData'] ?? null;
        $this->documentsData = $values['documentsData'] ?? null;
        $this->ownershipData = $values['ownershipData'] ?? null;
        $this->processingData = $values['processingData'] ?? null;
        $this->salesData = $values['salesData'] ?? null;
        $this->servicesData = $values['servicesData'] ?? null;
        $this->underwritingData = $values['underwritingData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
