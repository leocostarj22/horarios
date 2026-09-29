<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\View\Municipios;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for a list of Municipios.
 */
class HtmlView extends BaseHtmlView
{
    protected $items;
    protected $pagination;
    protected $state;
    public $filterForm;
    public $activeFilters;

    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (\count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        return parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = \Joomla\CMS\Helper\ContentHelper::getActions('com_horarios');

        ToolbarHelper::title(Text::_('COM_HORARIOS_MANAGER_MUNICIPIOS'), 'map-marker');

        if ($canDo->get('core.create')) {
            ToolbarHelper::addNew('municipio.add');
        }

        if ($canDo->get('core.edit.state')) {
            ToolbarHelper::publish('municipios.publish', 'JTOOLBAR_PUBLISH', true);
            ToolbarHelper::unpublish('municipios.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }

        if ($canDo->get('core.delete')) {
            ToolbarHelper::deleteList('', 'municipios.delete', 'JTOOLBAR_EMPTY_TRASH');
        }

        if ($canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_horarios');
        }
    }
}
