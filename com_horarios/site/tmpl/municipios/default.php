<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Horarios\Site\Helper\MediaHelper;

if (!function_exists('horariosRenderModulePosition')) {
    /**
     * Renders every published module assigned to a position (and to the
     * current menu item / access level - Joomla checks that internally).
     * Lets editors add extra image banners or WYSIWYG text blocks via
     * Content > Site Modules > New > Custom, without touching the component.
     */
    function horariosRenderModulePosition($position)
    {
        $modules = ModuleHelper::getModules($position);

        if (empty($modules)) {
            return;
        }

        echo '<div class="horarios-modules horarios-modules-' . htmlspecialchars($position, ENT_QUOTES, 'UTF-8') . '">';

        foreach ($modules as $module) {
            echo '<div class="horarios-module">' . ModuleHelper::renderModule($module) . '</div>';
        }

        echo '</div>';
    }
}

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */

$pageTitle = trim((string) $this->params->get('page_title', ''));

if ($pageTitle === '') {
    $pageTitle = Text::_('COM_HORARIOS_PAGE_TITLE');
}

$pageDescription = trim((string) $this->params->get('page_description', ''));
$notice          = trim((string) $this->params->get('notice_text', ''));

$mapLinkUrl     = $this->active ? MediaHelper::safeUrl($this->active->map_link) : '';
$reservasUrlUrl = $this->active ? MediaHelper::safeUrl($this->active->reservas_url) : '';
?>
<div class="horarios-page">
    <?php echo $this->loadTemplate('banner'); ?>
    <?php echo $this->loadTemplate('reservas'); ?>
    <?php horariosRenderModulePosition('horarios-topo'); ?>

    <div class="horarios-container">
        <nav class="horarios-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo Route::_('index.php'); ?>"><?php echo Text::_('COM_HORARIOS_BREADCRUMB_HOME'); ?></a>
            <span class="horarios-breadcrumb-sep" aria-hidden="true">&gt;</span>
            <span><?php echo Text::_('COM_HORARIOS_BREADCRUMB'); ?></span>
        </nav>

        <h1 class="horarios-title"><?php echo $this->escape($pageTitle); ?></h1>

        <?php if ($pageDescription !== '') : ?>
            <p class="horarios-description"><?php echo $this->escape($pageDescription); ?></p>
        <?php endif; ?>

        <?php if ($notice !== '') : ?>
            <div class="horarios-notice">
                <i class="bi bi-clock-history" aria-hidden="true"></i>
                <span><?php echo $this->escape($notice); ?></span>
            </div>
        <?php endif; ?>

        <?php echo $this->loadTemplate('search'); ?>
        <?php if ((int) $this->params->get('show_carousel', 1)) : ?>
            <?php echo $this->loadTemplate('carousel'); ?>
        <?php endif; ?>

        <div class="horarios-layout">
            <?php echo $this->loadTemplate('sidebar'); ?>

            <div class="horarios-main">
                <?php if ($this->active) : ?>
                    <div class="horarios-main-header">
                        <h2><?php echo $this->escape($this->active->title); ?></h2>

                        <?php if ($mapLinkUrl !== '') : ?>
                            <a class="horarios-map-link" href="<?php echo htmlspecialchars($mapLinkUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                                <?php echo Text::_('COM_HORARIOS_VER_MAPA_REGIAO'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($this->active->mapa_file) || !empty($this->active->brochura_file) || $reservasUrlUrl !== '') : ?>
                        <div class="horarios-quicklinks">
                            <?php if (!empty($this->active->mapa_file)) : ?>
                                <a class="horarios-quicklink" href="<?php echo MediaHelper::url($this->active->mapa_file); ?>" target="_blank" rel="noopener">
                                    <i class="bi bi-map" aria-hidden="true"></i>
                                    <?php echo Text::_('COM_HORARIOS_MAPA'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($this->active->brochura_file)) : ?>
                                <a class="horarios-quicklink" href="<?php echo MediaHelper::url($this->active->brochura_file); ?>" target="_blank" rel="noopener">
                                    <i class="bi bi-book" aria-hidden="true"></i>
                                    <?php echo Text::_('COM_HORARIOS_BROCHURA'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($reservasUrlUrl !== '') : ?>
                                <a class="horarios-quicklink" href="<?php echo htmlspecialchars($reservasUrlUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                    <i class="bi bi-link-45deg" aria-hidden="true"></i>
                                    <?php echo Text::_('COM_HORARIOS_RESERVAS_ONLINE'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($this->circuitos)) : ?>
                        <p class="horarios-empty"><?php echo Text::_('COM_HORARIOS_SEM_CIRCUITOS'); ?></p>
                    <?php else : ?>
                        <?php echo $this->loadTemplate('circuito'); ?>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="horarios-empty"><?php echo Text::_('COM_HORARIOS_SEM_MUNICIPIOS'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php horariosRenderModulePosition('horarios-rodape'); ?>
    </div>
</div>
