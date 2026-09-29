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
<div class="horarios-carousel">
    <button type="button" class="horarios-carousel-nav horarios-carousel-prev" aria-label="Anterior"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>

    <div class="horarios-carousel-track" id="horariosCarouselTrack">
        <?php foreach ($this->items as $municipio) :
            $isActive = $this->active && (int) $this->active->id === (int) $municipio->id;
            $url      = Route::_('index.php?option=com_horarios&view=municipios&id=' . (int) $municipio->id);
        ?>
            <a
                class="horarios-carousel-item<?php echo $isActive ? ' active' : ''; ?>"
                href="<?php echo $url; ?>"
                data-title="<?php echo $this->escape(strtolower($municipio->title)); ?>"
            >
                <?php if (!empty($municipio->image)) : ?>
                    <img src="<?php echo MediaHelper::url($municipio->image); ?>" alt="<?php echo $this->escape($municipio->title); ?>">
                <?php else : ?>
                    <span class="horarios-carousel-placeholder"><i class="bi bi-building" aria-hidden="true"></i></span>
                <?php endif; ?>
                <span class="horarios-carousel-label"><?php echo $this->escape($municipio->title); ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <button type="button" class="horarios-carousel-nav horarios-carousel-next" aria-label="Seguinte"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
</div>
