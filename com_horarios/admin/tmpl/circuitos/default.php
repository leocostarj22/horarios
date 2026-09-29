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

/** @var \Joomla\Component\Horarios\Administrator\View\Circuitos\HtmlView $this */

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder === 'a.ordering';
$user      = Factory::getApplication()->getIdentity();

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_horarios&task=circuitos.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_horarios&view=circuitos'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container" class="j-main-container">
        <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <?php if (empty($this->items)) : ?>
            <div class="alert alert-info"><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></div>
        <?php else : ?>
            <table class="table" id="circuitosList">
                <caption class="visually-hidden"><?php echo Text::_('COM_HORARIOS_MANAGER_CIRCUITOS'); ?></caption>
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
                        <th scope="col" class="d-none d-md-table-cell">
                            <?php echo Text::_('COM_HORARIOS_FIELD_MUNICIPIO_LABEL'); ?>
                        </th>
                        <th scope="col" class="d-none d-md-table-cell">
                            <?php echo Text::_('COM_HORARIOS_FIELD_TIPO_LABEL'); ?>
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
                        <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo (int) $item->municipio_id . '-' . (int) $item->parent_id; ?>">
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
                                    ['task_prefix' => 'circuitos.', 'disabled' => !$canChange, 'id' => 'state-' . $item->id]
                                );
                                ?>
                            </td>
                            <th scope="row">
                                <?php if ((int) $item->parent_id > 0) : ?>
                                    <span class="text-muted">&mdash;</span>
                                <?php endif; ?>
                                <?php if ($canEdit) : ?>
                                    <a href="<?php echo Route::_('index.php?option=com_horarios&task=circuito.edit&id=' . (int) $item->id); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo $this->escape($item->title); ?>
                                <?php endif; ?>
                                <?php if ((int) $item->parent_id > 0 && !empty($item->parent_title)) : ?>
                                    <div class="small text-muted"><?php echo Text::sprintf('COM_HORARIOS_LOCALIDADE_OF', $this->escape($item->parent_title)); ?></div>
                                <?php endif; ?>
                            </th>
                            <td class="d-none d-md-table-cell">
                                <?php echo $this->escape($item->municipio_title ?? ''); ?>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php if ((int) $item->parent_id > 0) : ?>
                                    <span class="badge bg-info"><?php echo Text::_('COM_HORARIOS_TIPO_LOCALIDADE'); ?></span>
                                <?php else : ?>
                                    <span class="badge bg-secondary"><?php echo Text::_('COM_HORARIOS_TIPO_CIRCUITO'); ?></span>
                                <?php endif; ?>
                            </td>
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
