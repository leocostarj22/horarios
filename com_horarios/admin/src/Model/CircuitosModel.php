<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

namespace Joomla\Component\Horarios\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

/**
 * Methods supporting a list of Circuito/Localidade records.
 */
class CircuitosModel extends ListModel
{
    public function __construct($config = [], $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'title', 'a.title',
                'state', 'a.state',
                'ordering', 'a.ordering',
                'municipio_id', 'a.municipio_id',
            ];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'a.ordering', $direction = 'ASC')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $municipioId = $this->getUserStateFromRequest($this->context . '.filter.municipio_id', 'filter_municipio_id', '');
        $this->setState('filter.municipio_id', $municipioId);

        $tipo = $this->getUserStateFromRequest($this->context . '.filter.tipo', 'filter_tipo', '');
        $this->setState('filter.tipo', $tipo);

        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.municipio_id');
        $id .= ':' . $this->getState('filter.tipo');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select(
            [
                $db->quoteName('a') . '.*',
                $db->quoteName('m.title', 'municipio_title'),
                $db->quoteName('p.title', 'parent_title'),
            ]
        )
            ->from($db->quoteName('#__horarios_circuitos', 'a'))
            ->join('LEFT', $db->quoteName('#__horarios_municipios', 'm') . ' ON ' . $db->quoteName('m.id') . ' = ' . $db->quoteName('a.municipio_id'))
            ->join('LEFT', $db->quoteName('#__horarios_circuitos', 'p') . ' ON ' . $db->quoteName('p.id') . ' = ' . $db->quoteName('a.parent_id'));

        $published = (string) $this->getState('filter.published');

        if ($published !== '' && $published !== '*') {
            $publishedInt = (int) $published;
            $query->where($db->quoteName('a.state') . ' = :state')
                ->bind(':state', $publishedInt, ParameterType::INTEGER);
        } elseif ($published === '') {
            $query->whereIn($db->quoteName('a.state'), [0, 1]);
        }

        $municipioId = (int) $this->getState('filter.municipio_id');

        if ($municipioId > 0) {
            $query->where($db->quoteName('a.municipio_id') . ' = :municipioid')
                ->bind(':municipioid', $municipioId, ParameterType::INTEGER);
        }

        $tipo = (string) $this->getState('filter.tipo');

        if ($tipo === 'circuito') {
            $query->where($db->quoteName('a.parent_id') . ' = 0');
        } elseif ($tipo === 'localidade') {
            $query->where($db->quoteName('a.parent_id') . ' > 0');
        }

        $search = (string) $this->getState('filter.search');

        if ($search !== '') {
            if (stripos($search, 'id:') === 0) {
                $searchId = (int) substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :searchid')
                    ->bind(':searchid', $searchId, ParameterType::INTEGER);
            } else {
                $searchTerm = '%' . str_replace(' ', '%', trim($search)) . '%';
                $query->where($db->quoteName('a.title') . ' LIKE :search')
                    ->bind(':search', $searchTerm);
            }
        }

        $orderCol  = $this->state->get('list.ordering', 'a.ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
}
