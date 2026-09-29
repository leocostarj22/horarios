<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\MediaHelper as CoreMediaHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Resolves a stored media-field value (local upload OR a plain external URL
 * typed directly into the field) into a usable, absolute URL.
 */
abstract class MediaHelper
{
    /**
     * Strips the Joomla media field's "#joomlaImage://..." suffix, if present.
     */
    public static function cleanValue($value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        return CoreMediaHelper::getCleanMediaFieldValue($value);
    }

    /**
     * True if the value is already an absolute (external or protocol-relative) URL.
     */
    public static function isExternal(string $value): bool
    {
        return (bool) preg_match('#^(https?:)?//#i', $value);
    }

    /**
     * Builds the URL to use in src/href attributes: external URLs are returned
     * as-is, local uploads are resolved relative to the site root.
     */
    public static function url($value): string
    {
        $clean = static::cleanValue($value);

        if ($clean === '') {
            return '';
        }

        if (static::isExternal($clean)) {
            return $clean;
        }

        return Uri::root() . ltrim($clean, '/');
    }

    /**
     * True if the resolved value points to a PDF file (by extension).
     */
    public static function isPdf($value): bool
    {
        $clean = static::cleanValue($value);

        if ($clean === '') {
            return false;
        }

        $path = parse_url($clean, PHP_URL_PATH) ?: $clean;

        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * Sanitizes a plain URL field (map_link, reservas_url, link_url) before it
     * is used in an href attribute. These fields are free text typed by any
     * user with edit rights on the component - without this check, a value
     * like "javascript:alert(1)" would be stored and later executed in every
     * visitor's browser when they click the link (stored XSS).
     *
     * Only http(s), protocol-relative ("//host/...") and scheme-less
     * (relative/local) values are allowed through; anything else
     * (javascript:, data:, vbscript:, etc.) is rejected.
     */
    public static function safeUrl($value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $value, $m) && strtolower($m[1]) !== 'http' && strtolower($m[1]) !== 'https') {
            return '';
        }

        return $value;
    }
}
