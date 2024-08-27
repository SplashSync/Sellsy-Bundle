<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

interface RowInterface
{
    public const DATATYPE = "single";

    /**
     * Get Row ID
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get Row Type
     *
     * @return string|null
     */
    public function getType(): ?string;
}