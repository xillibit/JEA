<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: jea.php 459 2014-01-26 22:42:11Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_jea
 */

class JeaHelper
{

    /**
     * Configure the Linkbar.
     *
     * @param   string  $viewName  The name of the active view.
     *
     * @return  void
     */
    public static function addSubmenu($viewName)
    {
        $menu = JToolBar::getInstance('submenu');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('m.*')
              ->from('#__menu AS m')
              ->innerJoin('#__menu AS m2 ON m.parent_id = m2.id')
              ->where("m2.link='index.php?option=com_jea'")
              ->order('id ASC');

        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $item) {
            $active = false;
            switch ($item->title) {
                case 'com_jea_properties' :
                    $item->title = 'COM_JEA_PROPERTIES_MANAGEMENT';
                    break;
                case 'com_jea_features' :
                    $item->title = 'COM_JEA_FEATURES_MANAGEMENT';
                    break;
            }
            if (preg_match('#&view=([a-z]+)#', $item->link, $matches)) {
               $active = $matches[1] == $viewName;
            }
            if ((float) JVERSION > 3)  {
                JHtmlSidebar::addEntry(JText::_($item->title),$item->link, $active);
            } else {
                $menu->appendButton(JText::_($item->title), $item->link, $active);
            }
        }
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @param  int    The property ID.
     * @return  JObject
     */
    public static function getActions($propertyId = 0)
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        if (empty($propertyId)) {
            $assetName = 'com_jea';
        }  else {
            $assetName = 'com_jea.property.'.(int) $propertyId;
        }

        $actions = array(
            'core.admin',
            'core.manage',
            'core.create',
            'core.edit',
            'core.edit.own',
            'core.edit.state',
            'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
    
    /**
     * Gets the list of tools icons.
     *
     */
    public static function getToolsIcons()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select(array('link', 'title AS text', 'icon AS image', 'access'));
        $query->from('#__jea_tools');
        $query->order('id ASC');
        $db->setQuery($query);
        $buttons = $db->loadAssocList();

        foreach ($buttons as &$button) {
            $button['text'] = JText::_($button['text']);

            if ((float) JVERSION > 3) {
                $button['image'] = str_replace(array('.png', 'icon-'), '', basename($button['image']));
                parse_str($button['link'], $output);
                if(!empty($output['view'])) {
                    $button['image'] = '48-'.$output['view'];
                    $button['name'] = $output['view'];
                } else {
                    $button['name'] = '';
                }
            }

            if (!empty($button['access'])) {
                $button['access'] = json_decode($button['access']);
            }
        }

        return $buttons;
    }

}
