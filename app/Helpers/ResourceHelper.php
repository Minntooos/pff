<?php

namespace App\Helpers;

class ResourceHelper
{
    public static function getIconClass($resource)
    {
        if ($resource->resource_image_path) {
            return 'custom-image';
        }

        $iconMap = [
            'document' => [
                'pdf' => 'pdf',
                'doc' => 'doc',
                'docx' => 'doc',
                'xls' => 'xls',
                'xlsx' => 'xls',
                'ppt' => 'ppt',
                'pptx' => 'ppt',
                'txt' => 'txt',
            ],
            'audio' => 'audio',
            'video' => 'video',
            'image' => 'image',
        ];

        if ($resource->type === 'document') {
            $extension = strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION));

            return $iconMap['document'][$extension] ?? 'default';
        }

        return $iconMap[$resource->type] ?? 'default';
    }
}
