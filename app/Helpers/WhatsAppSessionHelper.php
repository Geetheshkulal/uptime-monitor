<?php

// app/Helpers/WhatsAppSessionHelper.php
namespace App\Helpers;

class WhatsAppSessionHelper
{
    public static function isWhatsAppBrowserOpen(): bool
    {
        return file_exists(storage_path('app/whatsapp_browser.lock'));
    }

    public static function markBrowserAsOpen(): void
    {
        file_put_contents(storage_path('app/whatsapp_browser.lock'), '1');
    }

    public static function markBrowserAsClosed(): void
    {
        @unlink(storage_path('app/whatsapp_browser.lock'));
    }
}
