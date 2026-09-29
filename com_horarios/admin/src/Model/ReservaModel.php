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
 * Reserva (caixa de reservas) model.
 */
class ReservaModel extends AdminModel
{
    public function getTable($type = 'Reserva', $prefix = 'Administrator', $config = [])
    {
        return parent::getTable($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_horarios.reserva', 'reserva', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_horarios.edit.reserva.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
        $user = Factory::getApplication()->getIdentity();
        $date = Factory::getDate()->toSql();

        if (empty($table->id)) {
            $table->created    = $date;
            $table->created_by = $user->id;
            $table->ordering   = $table->getNextOrder();
        }

        $table->modified    = $date;
        $table->modified_by = $user->id;
    }
}
