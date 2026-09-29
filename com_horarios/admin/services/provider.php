<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\Horarios\Administrator\Extension\HorariosComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Note: Joomla always loads THIS file (administrator/components/com_horarios/services/provider.php)
 * to boot the component, for both the site and the administrator applications
 * (see Joomla\CMS\Extension\ExtensionManagerTrait::bootComponent()). There is
 * no separate site-side services/provider.php.
 */
return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Horarios'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Horarios'));
        $container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Horarios'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new HorariosComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
