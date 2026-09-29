<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\MediaHelper as CoreMediaHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Resolves a stored media-field value (local upload OR a plain external URL
 * typed directly into the field) into a usable, absolute URL.
 */
abstract class MediaHelper
{
    public static function cleanValue($value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        return CoreMediaHelper::getCleanMediaFieldValue($value);
    }

    public static function isExternal(string $value): bool
    {
        return (bool) preg_match('#^(https?:)?//#i', $value);
    }

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
}
