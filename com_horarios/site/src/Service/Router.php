<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\Database\DatabaseInterface;

/**
 * Routing class for com_horarios.
 *
 * Registers the "municipios" view as a plain (keyless) view: the SEF path
 * resolves to the menu item's own alias (e.g. /horarios), and the selected
 * municipio travels as a normal "id" query string parameter
 * (e.g. /horarios?id=5). We deliberately do NOT call setKey('id') here,
 * because doing so requires implementing matching getMunicipiosId()/
 * getMunicipiosSegment() methods to build a per-id path segment; without
 * them, Joomla's MenuRules ends up building an array-shaped Itemid lookup
 * that crashes when Route::_() is used for a specific municipio link
 * ("Cannot access offset of type array in isset or empty").
 *
 * Joomla's RouterFactory always instantiates this class as
 * new Router($application, $menu, $categoryFactory, $db) - the last two
 * arguments are accepted (and ignored) even though com_horarios has no
 * categories, to match that fixed signature.
 */
class Router extends RouterView
{
    public function __construct(
        SiteApplication $app,
        AbstractMenu $menu,
        ?CategoryFactoryInterface $categoryFactory = null,
        ?DatabaseInterface $db = null
    ) {
        $this->registerView(new RouterViewConfiguration('municipios'));

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }
}
