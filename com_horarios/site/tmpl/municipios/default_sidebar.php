<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\Component\Horarios\Site\Helper\MediaHelper;

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */

if (empty($this->items)) {
    return;
}
?>
<div class="horarios-sidebar" id="horariosSidebar">
    <ul class="horarios-sidebar-list">
        <?php foreach ($this->items as $municipio) :
            $isActive     = $this->active && (int) $this->active->id === (int) $municipio->id;
            $url          = Route::_('index.php?option=com_horarios&view=municipios&id=' . (int) $municipio->id);
            $sidebarImage = !empty($municipio->sidebar_image) ? MediaHelper::url($municipio->sidebar_image) : '';
        ?>
            <li>
                <?php if ($sidebarImage !== '') : ?>
                    <a
                        class="horarios-sidebar-link horarios-sidebar-link-image<?php echo $isActive ? ' active' : ''; ?>"
                        href="<?php echo $url; ?>"
                        data-title="<?php echo $this->escape(strtolower($municipio->title)); ?>"
                    >
                        <img src="<?php echo htmlspecialchars($sidebarImage, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $this->escape($municipio->title); ?>">
                    </a>
                <?php else : ?>
                    <a
                        class="horarios-sidebar-link<?php echo $isActive ? ' active' : ''; ?>"
                        href="<?php echo $url; ?>"
                        data-title="<?php echo $this->escape(strtolower($municipio->title)); ?>"
                    >
                        <span><?php echo $this->escape($municipio->title); ?></span>
                        <i class="bi bi-chevron-right horarios-sidebar-arrow" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
