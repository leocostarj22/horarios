<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\View\Circuito;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for a single Circuito (edit form).
 */
class HtmlView extends BaseHtmlView
{
    protected $item;
    protected $form;
    protected $state;

    public function display($tpl = null)
    {
        $this->item  = $this->get('Item');
        $this->form  = $this->get('Form');
        $this->state = $this->get('State');

        if (\count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        return parent::display($tpl);
    }

    protected function addToolbar()
    {
        $isNew = ((int) $this->item->id === 0);

        ToolbarHelper::title(Text::_($isNew ? 'COM_HORARIOS_MANAGER_CIRCUITO_NEW' : 'COM_HORARIOS_MANAGER_CIRCUITO_EDIT'), 'list');

        ToolbarHelper::saveGroup(
            [
                ['save', 'circuito.save'],
                ['save2new', 'circuito.save2new'],
            ],
            'btn-success'
        );

        if (!$isNew) {
            ToolbarHelper::saveGroup(
                [['save2copy', 'circuito.save2copy']],
                'btn-success'
            );
        }

        ToolbarHelper::cancel('circuito.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
