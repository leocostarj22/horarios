<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Circuito / Localidade model.
 */
class CircuitoModel extends AdminModel
{
    public function getTable($type = 'Circuito', $prefix = 'Administrator', $config = [])
    {
        return parent::getTable($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_horarios.circuito', 'circuito', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_horarios.edit.circuito.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function getReorderConditions($table)
    {
        return [
            $this->getDatabase()->quoteName('municipio_id') . ' = ' . (int) $table->municipio_id,
            $this->getDatabase()->quoteName('parent_id') . ' = ' . (int) $table->parent_id,
        ];
    }

    protected function prepareTable($table)
    {
        $user = Factory::getApplication()->getIdentity();
        $date = Factory::getDate()->toSql();

        if (empty($table->id)) {
            $db    = $this->getDatabase();
            $where = $db->quoteName('municipio_id') . ' = ' . (int) $table->municipio_id
                . ' AND ' . $db->quoteName('parent_id') . ' = ' . (int) $table->parent_id;

            $table->created    = $date;
            $table->created_by = $user->id;
            $table->ordering   = $table->getNextOrder($where);
        }

        $table->modified    = $date;
        $table->modified_by = $user->id;
    }
}
