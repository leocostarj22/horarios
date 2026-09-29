<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Circuito table (also used for Localidades, via parent_id).
 */
class CircuitoTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__horarios_circuitos', 'id', $db);

        $this->setColumnAlias('published', 'state');
    }

    public function check()
    {
        if (trim($this->title) === '') {
            $this->setError(Text::_('COM_HORARIOS_ERROR_TITLE_REQUIRED'));

            return false;
        }

        if ((int) $this->municipio_id <= 0) {
            $this->setError(Text::_('COM_HORARIOS_ERROR_MUNICIPIO_REQUIRED'));

            return false;
        }

        if ((int) $this->parent_id === (int) $this->id && (int) $this->id > 0) {
            $this->setError(Text::_('COM_HORARIOS_ERROR_PARENT_INVALID'));

            return false;
        }

        if (trim($this->alias) === '') {
            $this->alias = $this->title;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->title);

        return true;
    }
}
