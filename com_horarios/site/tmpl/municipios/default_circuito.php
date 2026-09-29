<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Component\Horarios\Site\Helper\MediaHelper;

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */

if (!function_exists('horariosFileUrl')) {
    function horariosFileUrl($path)
    {
        return MediaHelper::url($path);
    }
}

if (!function_exists('horariosIsPdf')) {
    function horariosIsPdf($path)
    {
        return MediaHelper::isPdf($path);
    }
}

if (!function_exists('horariosRenderMedia')) {
    function horariosRenderMedia($path, $label)
    {
        if (empty($path)) {
            echo '<p class="horarios-media-empty">' . Text::_('COM_HORARIOS_SEM_CONTEUDO') . '</p>';

            return;
        }

        $url = horariosFileUrl($path);

        if (horariosIsPdf($path)) {
            echo '<div class="horarios-media-pdf">';
            echo '<a class="horarios-media-pdf-link" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">';
            echo '<i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> ' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
            echo '</a>';
            echo '<embed src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" type="application/pdf" class="horarios-media-embed">';
            echo '</div>';
        } else {
            echo '<img class="horarios-media-img" src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" loading="lazy">';
        }
    }
}

if (!function_exists('horariosRenderTabs')) {
    function horariosRenderTabs($item, $uid)
    {
        ?>
        <div class="horarios-tabs">
            <div class="horarios-tab-nav" role="tablist">
                <button class="horarios-tab-btn is-active" type="button" role="tab" data-tab-target="<?php echo $uid; ?>-circuito" aria-selected="true">
                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?php echo Text::_('COM_HORARIOS_TAB_CIRCUITO'); ?>
                </button>
                <button class="horarios-tab-btn" type="button" role="tab" data-tab-target="<?php echo $uid; ?>-horario" aria-selected="false">
                    <i class="bi bi-clock" aria-hidden="true"></i> <?php echo Text::_('COM_HORARIOS_TAB_HORARIO'); ?>
                </button>
                <button class="horarios-tab-btn" type="button" role="tab" data-tab-target="<?php echo $uid; ?>-tarifario" aria-selected="false">
                    <i class="bi bi-tag" aria-hidden="true"></i> <?php echo Text::_('COM_HORARIOS_TAB_TARIFARIO'); ?>
                </button>
            </div>
            <div class="horarios-tab-panels">
                <div class="horarios-tab-panel is-active" id="<?php echo $uid; ?>-circuito" role="tabpanel">
                    <?php horariosRenderMedia($item->circuito_file, Text::_('COM_HORARIOS_TAB_CIRCUITO')); ?>
                </div>
                <div class="horarios-tab-panel" id="<?php echo $uid; ?>-horario" role="tabpanel">
                    <?php horariosRenderMedia($item->horario_file, Text::_('COM_HORARIOS_TAB_HORARIO')); ?>
                </div>
                <div class="horarios-tab-panel" id="<?php echo $uid; ?>-tarifario" role="tabpanel">
                    <?php horariosRenderMedia($item->tarifario_file, Text::_('COM_HORARIOS_TAB_TARIFARIO')); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('horariosRenderFolheto')) {
    function horariosRenderFolheto($path)
    {
        if (empty($path)) {
            return;
        }
        ?>
        <a class="horarios-folheto" href="<?php echo htmlspecialchars(horariosFileUrl($path), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
            <?php echo Text::_('COM_HORARIOS_FOLHETO_INFORMATIVO'); ?>
        </a>
        <?php
    }
}

if (empty($this->circuitos)) {
    return;
}
?>
<div class="horarios-accordion" id="horariosCircuitosAccordion">
    <?php foreach ($this->circuitos as $ci => $circuito) :
        $cUid = 'horarios-c' . (int) $circuito->id;
        $cOpen = $ci === 0;
    ?>
        <div class="horarios-acc-item<?php echo $cOpen ? ' is-open' : ''; ?>">
            <button class="horarios-acc-header" type="button" aria-expanded="<?php echo $cOpen ? 'true' : 'false'; ?>" aria-controls="<?php echo $cUid; ?>-body">
                <span class="horarios-acc-title"><?php echo $this->escape($circuito->title); ?></span>
                <i class="bi bi-chevron-down horarios-acc-chevron" aria-hidden="true"></i>
            </button>
            <div class="horarios-acc-body" id="<?php echo $cUid; ?>-body"<?php echo $cOpen ? '' : ' hidden'; ?>>
                <?php horariosRenderFolheto($circuito->folheto_file); ?>

                <?php horariosRenderTabs($circuito, $cUid); ?>

                <?php if (!empty($circuito->localidades)) : ?>
                    <div class="horarios-accordion horarios-accordion-nested" id="<?php echo $cUid; ?>-localidades">
                        <?php foreach ($circuito->localidades as $localidade) :
                            $lUid = 'horarios-l' . (int) $localidade->id;
                        ?>
                            <div class="horarios-acc-item">
                                <button class="horarios-acc-header" type="button" aria-expanded="false" aria-controls="<?php echo $lUid; ?>-body">
                                    <span class="horarios-acc-title"><?php echo $this->escape($localidade->title); ?></span>
                                    <i class="bi bi-chevron-down horarios-acc-chevron" aria-hidden="true"></i>
                                </button>
                                <div class="horarios-acc-body" id="<?php echo $lUid; ?>-body" hidden>
                                    <?php horariosRenderFolheto($localidade->folheto_file); ?>

                                    <?php horariosRenderTabs($localidade, $lUid); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
