<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

/**
 * Site model: municipios, their circuitos/localidades tree, banner and reservas boxes.
 */
class MunicipiosModel extends ListModel
{
    public function __construct($config = [], $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = ['id', 'a.id', 'title', 'a.title', 'ordering', 'a.ordering'];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'a.ordering', $direction = 'ASC')
    {
        $app = Factory::getApplication();

        $id = $app->getInput()->getInt('id', 0);
        $this->setState('municipio.id', $id);

        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('municipio.id');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('a.*')
            ->from($db->quoteName('#__horarios_municipios', 'a'))
            ->where($db->quoteName('a.state') . ' = 1')
            ->order($db->quoteName('a.ordering') . ' ASC');

        return $query;
    }

    /**
     * Returns the currently selected municipio (by state 'municipio.id'), or the first published one.
     */
    public function getActiveMunicipio()
    {
        $items = $this->getItems();

        if (empty($items)) {
            return null;
        }

        $id = (int) $this->getState('municipio.id');

        if ($id > 0) {
            foreach ($items as $item) {
                if ((int) $item->id === $id) {
                    return $item;
                }
            }
        }

        return $items[0];
    }

    /**
     * Returns the tree of Circuitos (parent_id = 0) with nested Localidades (parent_id > 0)
     * for a given municipio.
     */
    public function getCircuitos($municipioId)
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $municipioId = (int) $municipioId;

        $query->select('*')
            ->from($db->quoteName('#__horarios_circuitos'))
            ->where($db->quoteName('municipio_id') . ' = :mid')
            ->where($db->quoteName('state') . ' = 1')
            ->bind(':mid', $municipioId, ParameterType::INTEGER)
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query);
        $rows = $db->loadObjectList() ?: [];

        $circuitos = [];
        $children  = [];

        foreach ($rows as $row) {
            if ((int) $row->parent_id === 0) {
                $row->localidades      = [];
                $circuitos[$row->id]   = $row;
            } else {
                $children[$row->parent_id][] = $row;
            }
        }

        foreach ($circuitos as $id => $circuito) {
            $circuitos[$id]->localidades = $children[$id] ?? [];
        }

        return array_values($circuitos);
    }

    /**
     * Returns the currently active alert banner (published + inside its publish window), or null.
     */
    public function getBanner()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $now   = Factory::getDate()->toSql();

        $query->select('*')
            ->from($db->quoteName('#__horarios_banners'))
            ->where($db->quoteName('state') . ' = 1')
            ->where('(' . $db->quoteName('publish_up') . ' IS NULL OR ' . $db->quoteName('publish_up') . ' <= :now1)')
            ->where('(' . $db->quoteName('publish_down') . ' IS NULL OR ' . $db->quoteName('publish_down') . ' >= :now2)')
            ->bind(':now1', $now)
            ->bind(':now2', $now)
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query, 0, 1);

        return $db->loadObject() ?: null;
    }

    /**
     * Returns the published "Reservas" boxes.
     */
    public function getReservas()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__horarios_reservas'))
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList() ?: [];
    }
}
