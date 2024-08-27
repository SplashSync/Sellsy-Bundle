<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceDates
{
    /**
     * Service Start Date
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        JMS\SerializedName("start"),
        JMS\Type("date"),
        SPL\Field(type: SPL_T_DATE, desc: "Service Start Date"),
    ]
    public string $start = "";

    /**
     * Service End Date
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        JMS\SerializedName("end"),
        JMS\Type("date"),
        SPL\Field(type: SPL_T_DATE, desc: "Service End Date"),
    ]
    public string $end = "";

}