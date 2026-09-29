<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Horarios\Administrator\View\Reserva\HtmlView $this */

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
?>
<form
    action="<?php echo Route::_('index.php?option=com_horarios&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post"
    name="adminForm"
    id="reserva-form"
    class="form-validate"
>
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <?php echo $this->form->renderFieldset('details'); ?>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-header"><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></div>
                <div class="card-body">
                    <?php echo $this->form->renderFieldset('jmetadata'); ?>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
