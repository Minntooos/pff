<?php

if (! function_exists('getIconBackgroundColor')) {
    function getIconBackgroundColor($resourceType)
    {
        return match ($resourceType) {
            'document' => 'bg-blue-100',
            'image' => 'bg-green-100',
            'video' => 'bg-red-100',
            'audio' => 'bg-yellow-100',
            default => 'bg-gray-100',
        };
    }
}
