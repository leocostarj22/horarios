<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Site\View\Municipios;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * Site view: full "Circuitos, horários e tarifários" page.
 */
class HtmlView extends BaseHtmlView
{
    protected $items;
    protected $active;
    protected $circuitos;
    protected $banner;
    protected $reservas;
    protected $params;

    public function display($tpl = null)
    {
        $this->params = ComponentHelper::getParams('com_horarios');
        $this->items  = $this->get('Items');
        $this->active = $this->get('ActiveMunicipio');

        $model           = $this->getModel();
        $this->circuitos = $this->active ? $model->getCircuitos($this->active->id) : [];
        $this->banner    = $model->getBanner();
        $this->reservas  = $model->getReservas();

        if (\count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->_prepareDocument();

        return parent::display($tpl);
    }

    protected function _prepareDocument()
    {
        $app = Factory::getApplication();

        $pageTitle = trim((string) $this->params->get('page_title', ''));

        if ($pageTitle === '') {
            $pageTitle = Text::_('COM_HORARIOS_PAGE_TITLE');
        }

        $this->document->setTitle($pageTitle);

        $description = trim((string) $this->params->get('page_description', ''));

        if ($description !== '') {
            $this->document->setDescription($description);
        }

        $pathway = $app->getPathway();

        if (\is_object($pathway) && method_exists($pathway, 'addItem')) {
            $pathway->addItem(Text::_('COM_HORARIOS_BREADCRUMB'), '');
        }

        $this->document->addStyleSheet(
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'
        );

        // Loaded directly (not through the WebAssetManager registry): on some
        // setups the registry-based useStyle()/useScript() silently fails to
        // resolve media/com_horarios/joomla.asset.json, so this is the more
        // reliable path - the same approach used above for the icon font.
        // The "?v=" uses each file's own mtime, so browsers always fetch the
        // latest copy after a reinstall without needing a manual version bump.
        $mediaBase  = rtrim(Uri::root(true), '/') . '/media/com_horarios/';
        $cssPath    = JPATH_ROOT . '/media/com_horarios/css/horarios.css';
        $jsPath     = JPATH_ROOT . '/media/com_horarios/js/horarios.js';
        $cssVersion = is_file($cssPath) ? filemtime($cssPath) : time();
        $jsVersion  = is_file($jsPath) ? filemtime($jsPath) : time();

        $this->document->addStyleSheet($mediaBase . 'css/horarios.css?v=' . $cssVersion);
        $this->document->addScript($mediaBase . 'js/horarios.js?v=' . $jsVersion, [], ['defer' => true]);

        $customCss = trim((string) $this->params->get('custom_css', ''));

        if ($customCss !== '') {
            $this->document->addStyleDeclaration($customCss);
        }
    }
}
