<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Helper;

defined('_JEXEC') or die;

/**
 * Defense-in-depth: neutralizes dangerous URI schemes (javascript:, data:,
 * vbscript:, ...) in free-text URL fields (map_link, reservas_url, link_url)
 * at save time, on top of the render-time check the site templates already
 * apply via Site\Helper\MediaHelper::safeUrl().
 */
abstract class UrlHelper
{
    public static function sanitize($value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $value, $m) && !\in_array(strtolower($m[1]), ['http', 'https'], true)) {
            return '';
        }

        return $value;
    }
}
