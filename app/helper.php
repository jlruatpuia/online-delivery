<?php

use App\Models\User;

function mapNavigationUrl(?array $mapLocation): ?string
{
    if (
        empty($mapLocation) ||
        !isset($mapLocation['lat'], $mapLocation['lng'])
    ) {
        return null;
    }

    return "https://www.google.com/maps/dir/?api=".config('services.google.maps_key')
        . "&destination={$mapLocation['lat']},{$mapLocation['lng']}"
        . "&travelmode=driving";
}

function navigationUrlFromMap(?array $mapLocation): ?string
{
    //dd($mapLocation);
    if (
        empty($mapLocation) ||
        !isset($mapLocation['lat'], $mapLocation['lng'])
    ) {
        return null;
    }

    return "https://www.google.com/maps/dir/?api=".config('services.google.maps_key')
        . "&destination={$mapLocation['lat']},{$mapLocation['lng']}"
        . "&travelmode=driving";
}

function MarkDeliveryRequestAsRead(User $admin, int $deliveryId, string $type) : void{
    \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $admin->id)
        ->whereNull('read_at')
        ->where('data->delivery_id', $deliveryId)
        ->where('data->request_type', $type)
        ->update(['read_at' => now()]);
}
