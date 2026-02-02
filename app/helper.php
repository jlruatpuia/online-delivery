<?php
function mapNavigationUrl(?array $mapLocation): ?string
{
    if (
        empty($mapLocation) ||
        !isset($mapLocation['lat'], $mapLocation['lng'])
    ) {
        return null;
    }

    return "https://www.google.com/maps/dir/?api=1"
        . "&destination={$mapLocation['lat']},{$mapLocation['lng']}"
        . "&travelmode=driving";
}

function navigationUrlFromMap(?array $mapLocation): ?string
{
    if (
        empty($mapLocation) ||
        !isset($mapLocation['lat'], $mapLocation['lng'])
    ) {
        return null;
    }

    return "https://www.google.com/maps/dir/?api=1"
        . "&destination={$mapLocation['lat']},{$mapLocation['lng']}"
        . "&travelmode=driving";
}
