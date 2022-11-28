<?php

namespace App\Rules\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

abstract class Media
{
    // Define the general file types
    public const UNKNOWN = 'unknown';
    public const AUDIO = 'audio';
    public const DOCUMENT = 'document';
    public const IMAGE = 'image';
    public const VIDEO = 'video';

    // Define the supported extensions
    public const AUDIO_EXTENSIONS = ['mp3', 'wav', 'dvf', 'vox'];
    public const DOCUMENT_EXTENSIONS = [
        'pdf',
        'docx',
        'doc',
        'xls',
        'xslx',
        'csv',
        'ppt',
        'pptx',
    ];
    public const IMAGE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'jfif',
        'png',
        'bmp',
        'gif',
        'tiff',
        'svg',
        'webp',
    ];
    public const VIDEO_EXTENSIONS = [
        'mp4',
        'mkv',
        'wmv',
        'webm',
        'flv',
        'vob',
        'ogv',
        'ogg',
        'avi',
    ];
    public const SPREADSHEET_EXTENSIONS = ['xls', 'xlsx', 'csv'];

    // Define the general extensions filesize limit
    public const MAX_AUDIO_SIZE = 1024 * 10; // 10 MB
    public const MAX_DOCUMENT_SIZE = 1024 * 10; // 10 MB
    public const MAX_IMAGE_SIZE = 1024 * 5; // 5  MB
    public const MAX_VIDEO_SIZE = 1024 * 20; // 20 MB
    public const MAX_SPREADSHEET_SIZE = 1024 * 50; // 50 MB

    // Define the function helper
    public static function randomFilename(UploadedFile $file): string
    {
        return sprintf(
            '%s.%s',
            md5(Str::random(5)),
            $file->getClientOriginalExtension()
        );
    }

    public static function randomCustomFilename(string $extension): string
    {
        return sprintf('%s.%s', md5(Str::random(5)), trim($extension));
    }

    public static function audioExtensions(): string
    {
        return implode(',', self::AUDIO_EXTENSIONS);
    }

    public static function documentExtensions(): string
    {
        return implode(',', self::DOCUMENT_EXTENSIONS);
    }

    public static function imageExtensions(): string
    {
        return implode(',', self::IMAGE_EXTENSIONS);
    }

    public static function videoExtensions(): string
    {
        return implode(',', self::VIDEO_EXTENSIONS);
    }

    public static function spreadsheetExtensions(): string
    {
        return implode(',', self::SPREADSHEET_EXTENSIONS);
    }
}
