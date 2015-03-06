<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: properties.json.php 428 2013-08-25 15:26:30Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JeaControllerProperties extends JControllerLegacy
{

    public function search()
    {
        $app = JFactory::getApplication();
        $model = $this->getModel();
        $filters = $model->getFilters();

        // Set the Model state
        foreach ($filters as $name => $value) {
            $model->setState('filter.'.$name, $app->input->get('filter_'.$name, null, 'default'));
        }
        // Deactivate pagination
        $model->setState('list.start', 0);
        $model->setState('list.limit', 0);
        
        // Set language state
        $model->setState('filter.language', $app->getLanguageFilter());

        $items = $model->getItems();

        $result = array();
        $result['total'] = count($items);
        if (JDEBUG) {
            $result['query'] = (string) JFactory::getDbo()->getQuery();
        }
        $result['types'] = array();
        $result['towns'] = array();
        $result['departments'] = array();
        $result['areas'] = array();

        $temp = array();
        $temp['types'] = array();
        $temp['towns'] = array();
        $temp['departments'] = array();
        $temp['areas'] = array();

        foreach ($items as $row) {

            if ($row->type_id && !isset($temp['types'][$row->type_id])) {
                $result['types'][] = array( 'value' => $row->type_id , 'text' =>  $row->type);
                $temp['types'][$row->type_id] = true;
            }

            if ($row->town_id && !isset($temp['towns'][$row->town_id])) {
                $result['towns'][] = array( 'value' => $row->town_id , 'text' =>  $row->town);
                $temp['towns'][$row->town_id] = true;
            }

            if ($row->department_id && !isset($temp['departments'][$row->department_id])) {
                $result['departments'][] = array( 'value' => $row->department_id , 'text' =>  $row->department);
                $temp['departments'][$row->department_id] = true ;
            }
            
            if ($row->area_id && !isset($temp['areas'][$row->area_id])) {
                $result['areas'][] = array( 'value' => $row->area_id , 'text' =>  $row->area);
                $temp['areas'][$row->area_id] = true ;
            }
        }

        // TODO: User preference : Alpha ou order
        if (isset($result['types'])) usort($result['types'], array('JeaControllerProperties', '__ajaxAlphaSort'));
        if (isset($result['departments'])) usort($result['departments'], array('JeaControllerProperties', '__ajaxAlphaSort'));
        if (isset($result['towns'])) usort($result['towns'], array('JeaControllerProperties', '__ajaxAlphaSort'));
        if (isset($result['areas'])) usort($result['areas'], array('JeaControllerProperties', '__ajaxAlphaSort'));

        echo json_encode($result);
    }

    public function __ajaxAlphaSort(&$arg1, &$arg2)
    {
        $val1 = strtolower($arg1['text']);
        $val2 = strtolower($arg2['text']);

        return strnatcmp($val1, $val2);
    }

    /* (non-PHPdoc)
     * @see JController::getModel()
     */
    public function getModel($name = 'Properties', $prefix = 'JeaModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
