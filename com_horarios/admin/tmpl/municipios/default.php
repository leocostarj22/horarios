<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Button\PublishedButton;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var \Joomla\Component\Horarios\Administrator\View\Municipios\HtmlView $this */

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder === 'a.ordering';
$user      = Factory::getApplication()->getIdentity();

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_horarios&task=municipios.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_horarios&view=municipios'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container" class="j-main-container">
        <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <?php if (empty($this->items)) : ?>
            <div class="alert alert-info">
                <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table class="table" id="municipiosList">
                <caption class="visually-hidden"><?php echo Text::_('COM_HORARIOS_MANAGER_MUNICIPIOS'); ?></caption>
                <thead>
                    <tr>
                        <td class="w-1 text-center"><?php echo HTMLHelper::_('grid.checkall'); ?></td>
                        <th scope="col" class="w-1 text-center d-none d-md-table-cell">
                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                        </th>
                        <th scope="col" class="w-1 text-center">
                            <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                        </th>
                        <th scope="col">
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_HORARIOS_FIELD_TITLE_LABEL', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th scope="col" class="w-5 text-center d-none d-md-table-cell">
                            <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tbody<?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>"<?php endif; ?>>
                    <?php foreach ($this->items as $i => $item) :
                        $canEdit   = $user->authorise('core.edit', 'com_horarios');
                        $canChange = $user->authorise('core.edit.state', 'com_horarios');
                    ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="text-center d-none d-md-table-cell">
                                <span class="sortable-handler<?php echo $canChange && $saveOrder ? '' : ' inactive'; ?>">
                                    <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                </span>
                                <?php if ($canChange && $saveOrder) : ?>
                                    <input type="text" name="order[]" size="5" value="<?php echo (int) $item->ordering; ?>" class="width-20 text-area-order hidden">
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                echo (new PublishedButton())->render(
                                    (int) $item->state,
                                    $i,
                                    ['task_prefix' => 'municipios.', 'disabled' => !$canChange, 'id' => 'state-' . $item->id]
                                );
                                ?>
                            </td>
                            <th scope="row">
                                <?php if ($canEdit) : ?>
                                    <a href="<?php echo Route::_('index.php?option=com_horarios&task=municipio.edit&id=' . (int) $item->id); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo $this->escape($item->title); ?>
                                <?php endif; ?>
                            </th>
                            <td class="text-center d-none d-md-table-cell">
                                <?php echo (int) $item->id; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php echo $this->pagination->getListFooter(); ?>
        <?php endif; ?>

        <input type="hidden" name="task" value="">
        <input type="hidden" name="boxchecked" value="0">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
