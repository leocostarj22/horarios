<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\MVCComponent;

/**
 * Component class for com_horarios.
 *
 * Extends the generic MVCComponent with router support, so the site side
 * can produce SEF URLs for the "municipios" view.
 */
class HorariosComponent extends MVCComponent implements RouterServiceInterface
{
    use RouterServiceTrait;
}
