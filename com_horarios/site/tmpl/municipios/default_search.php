<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */
?>
<div class="horarios-search">
    <div class="horarios-search-field">
        <i class="bi bi-search" aria-hidden="true"></i>
        <input
            type="text"
            id="horariosSearchInput"
            class="form-control"
            placeholder="<?php echo Text::_('COM_HORARIOS_SEARCH_PLACEHOLDER'); ?>"
            autocomplete="off"
        >
    </div>

    <select id="horariosSearchSelect" class="horarios-search-select form-select">
        <option value=""><?php echo Text::_('COM_HORARIOS_SEARCH_ALL_MUNICIPIOS'); ?></option>
        <?php foreach ($this->items as $municipio) : ?>
            <option
                value="<?php echo (int) $municipio->id; ?>"
                data-url="<?php echo Route::_('index.php?option=com_horarios&view=municipios&id=' . (int) $municipio->id); ?>"
                <?php echo ($this->active && (int) $this->active->id === (int) $municipio->id) ? 'selected' : ''; ?>
            >
                <?php echo $this->escape($municipio->title); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="button" id="horariosSearchBtn" class="horarios-search-btn">
        <i class="bi bi-search" aria-hidden="true"></i>
        <?php echo Text::_('COM_HORARIOS_SEARCH_BUTTON'); ?>
    </button>
</div>
