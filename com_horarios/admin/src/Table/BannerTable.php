<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Component\Horarios\Administrator\Helper\UrlHelper;
use Joomla\Database\DatabaseDriver;

/**
 * Banner (faixa de alerta) table.
 */
class BannerTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__horarios_banners', 'id', $db);

        $this->setColumnAlias('published', 'state');
    }

    public function check()
    {
        if (trim((string) $this->image) === '') {
            $this->setError(Text::_('COM_HORARIOS_ERROR_IMAGE_REQUIRED'));

            return false;
        }

        if ($this->publish_up === '') {
            $this->publish_up = null;
        }

        if ($this->publish_down === '') {
            $this->publish_down = null;
        }

        $this->link_url = UrlHelper::sanitize($this->link_url);

        return true;
    }
}
