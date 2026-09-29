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
 * Methods supporting a list of Reserva (caixa de reservas) records.
 */
class ReservasModel extends ListModel
{
    public function __construct($config = [], $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'title', 'a.title',
                'state', 'a.state',
                'ordering', 'a.ordering',
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

        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select($this->getState('list.select', 'a.*'))
            ->from($db->quoteName('#__horarios_reservas', 'a'));

        $published = (string) $this->getState('filter.published');

        if ($published !== '' && $published !== '*') {
            $publishedInt = (int) $published;
            $query->where($db->quoteName('a.state') . ' = :state')
                ->bind(':state', $publishedInt, ParameterType::INTEGER);
        } elseif ($published === '') {
            $query->whereIn($db->quoteName('a.state'), [0, 1]);
        }

        $search = (string) $this->getState('filter.search');

        if ($search !== '') {
            $searchTerm = '%' . str_replace(' ', '%', trim($search)) . '%';
            $query->where($db->quoteName('a.title') . ' LIKE :search')
                ->bind(':search', $searchTerm);
        }

        $orderCol  = $this->state->get('list.ordering', 'a.ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
}
