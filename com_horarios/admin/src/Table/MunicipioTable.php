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
use Joomla\Component\Horarios\Administrator\Helper\UrlHelper;
use Joomla\Database\DatabaseDriver;

/**
 * Municipio table.
 */
class MunicipioTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__horarios_municipios', 'id', $db);

        $this->setColumnAlias('published', 'state');
    }

    public function check()
    {
        if (trim($this->title) === '') {
            $this->setError(Text::_('COM_HORARIOS_ERROR_TITLE_REQUIRED'));

            return false;
        }

        if (trim($this->alias) === '') {
            $this->alias = $this->title;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->title);

        $this->map_link     = UrlHelper::sanitize($this->map_link);
        $this->reservas_url = UrlHelper::sanitize($this->reservas_url);

        return true;
    }
}
