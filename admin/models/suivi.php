<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: properties.php 427 2013-07-17 15:48:22Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.modellist');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaModelSuivi extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see      JModelList
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'c.id',
                'name', 'c.name',
                'lastname', 'c.lastname',
                'adress', 'c.adress',
                'code_postal', 'c.code_postal',
                'ville', 'c.ville',
                'telephone', 'c.telephone',
                'mail', 'c.mail'
                );
        }

        // Set the internal state marker to true
        $config['ignore_request'] = true;

        parent::__construct($config);

        // Initialize state information and use id as default column ordering
        $this->populateState('s.id', 'desc');
    }



    /* (non-PHPdoc)
     * @see JModelList::populateState()
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $this->context .= '.properties';

        $transaction_type = $this->getUserStateFromRequest($this->context.'.filter.transaction_type', 'filter_transaction_type');
        $this->setState('filter.transaction_type', $transaction_type);

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $type_id = $this->getUserStateFromRequest($this->context.'.filter.type_id', 'filter_type_id');
        $this->setState('filter.type_id', $type_id);

        $department_id = $this->getUserStateFromRequest($this->context.'.filter.department_id', 'filter_department_id');
        $this->setState('filter.department_id', $department_id);

        $town_id = $this->getUserStateFromRequest($this->context.'.filter.town_id', 'filter_town_id');
        $this->setState('filter.town_id', $town_id);
        
        $language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        parent::populateState($ordering, $direction);
    }


    /* (non-PHPdoc)
     * @see JModelList::getListQuery()
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db        = $this->getDbo();
        $query    = $db->getQuery(true);
        $user    = JFactory::getUser();

        $query->select('s.* ');

        $query->from('#__jea_suivi AS s');

        // Join viewlevels
        /*$query->select('al.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS al ON al.id = p.access');

        // Join departments
        $query->select('d.value AS `department`');
        $query->join('LEFT', '#__jea_departments AS d ON d.id = p.department_id');

        // Join properties types
        $query->select('t.value AS `type`');
        $query->join('LEFT', '#__jea_types AS t ON t.id = p.type_id');

        // Join towns
        $query->select('town.value AS `town`');
        $query->join('LEFT', '#__jea_towns AS town ON town.id = p.town_id');

        // Join users
        $query->select('u.username AS `author`');
        $query->join('LEFT', '#__users AS u ON u.id = p.created_by');
        
        // Join over the language
        $query->select('l.title AS language_title');
        $query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = p.language');

        // Filter by transaction type
        if ($transactionType = $this->getState('filter.transaction_type')) {
            $query->where('p.transaction_type ='. $db->Quote($db->escape($transactionType)));
        }

        // Filter by property type
        if ($typeId = $this->getState('filter.type_id')) {
            $query->where('p.type_id ='.(int) $typeId);
        }

        // Filter by departments
        if ($departmentId = $this->getState('filter.department_id')) {
            $query->where('p.department_id ='.(int) $departmentId);
        }

        // Filter by town
        if ($townId = $this->getState('filter.town_id')) {
            $query->where('p.town_id ='.(int) $townId);
        }

        // Filter by search
        if ($search = $this->getState('filter.search')) {
            $search = $db->Quote('%'.$db->escape($search, true).'%');
            $search = '(p.ref LIKE ' . $search
                    . ' OR p.title LIKE ' . $search
                    . ' OR p.id LIKE ' . $search
                    . ' OR u.username LIKE ' .$search .')';
            $query->where($search);
        }
        
        // Filter on the language.
        if ($language = $this->getState('filter.language')) {
        	$query->where('p.language = '.$db->quote($language));
        }   */

        // Add the list ordering clause.
        $orderCol    = $this->state->get('list.ordering');
        $orderDirn    = $this->state->get('list.direction');
        
        // If language order selected order by languagetable title 
        if($orderCol == 'language') $orderCol = 'l.title';

        $query->order($db->escape($orderCol.' '.$orderDirn));

        // echo $query;
        return $query;
    }
    
    public function getItem() {
      $db = JFactory::getDBO();
      $app = JFactory::getApplication();
      
      $task = $app->getUserState( 'com_jea.add.suivi.task');       
      $id = $app->getUserState( 'com_jea.add.suivi.id');
      
      if ( $task == 'edit' ) {
        $query = "SELECT * FROM #__jea_suivi WHERE id={$id}";
        $db->setQuery($query);
        $item = $db->loadObject();
      
        return $item;
      } else {
        return null;
      }  
    }
    
    public function getContacts() 
    {
      $db = JFactory::getDBO();
      
      $query = "SELECT * FROM #__jea_contacts";
      $db->setQuery($query);
      $contacts = $db->loadObjectList();
      
      $item = $this->getItem();
      $contact_id = '-1';
      if ( !empty($item) ) {
        $contact_id = $item->id_contact;
      }
      
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_CONTACT'));
      foreach ($contacts as $key => $value) {
        $options[] = JHTML::_('select.option', $value->id, $value->contactname.' '.$value->lastname);    
      }

      $contact_list = JHTML::_('select.genericlist', $options,'id_contact', '','value','text', $contact_id);
      
      return $contact_list;
    }
    
    public function getProperties() 
    {
      $db = JFactory::getDBO();
      
      $query = "SELECT * FROM #__jea_properties";
      $db->setQuery($query);
      $properties = $db->loadObjectList();
      
      $item = $this->getItem();
      $property_id = '-1';
      if ( !empty($item) ) {
        $property_id = $item->id_property;
      }
      
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_PROPERTY'));
      foreach ($properties as $key => $value) {
        $options[] = JHTML::_('select.option', $value->id, $value->title);    
      }

      $properties_list = JHTML::_('select.genericlist', $options,'id_property', '','value','text', $property_id);
      
      return $properties_list; 
    }
    
    public function getTypes ()
    {
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_TYPE_EN_RELATION'));
      $options[] = JHTML::_('select.option',1,JText::_('COM_JEA_SELECT_TYPE_EN_RELATION_PROPERTY'));
      $options[] = JHTML::_('select.option',2,JText::_('COM_JEA_SELECT_TYPE_EN_RELATION_CONTACT'));      
      
      $item = $this->getItem();
      $type = '-1';
      if ( !empty($item) ) {
        $type = $item->type;
      }
      
      $types_list = JHTML::_('select.genericlist', $options,'type', '','value','text', $type);
      
      return $types_list;
    }
    
    public function getTypesAction ()
    {
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_TYPE_ACTION'));
      $options[] = JHTML::_('select.option',1,JText::_('COM_JEA_SELECT_TYPE_ACTION_MAIL'));
      $options[] = JHTML::_('select.option',2,JText::_('COM_JEA_SELECT_TYPE_ACTION_PHONE'));
      $options[] = JHTML::_('select.option',3,JText::_('COM_JEA_SELECT_TYPE_ACTION_FACETOFACE'));
      $options[] = JHTML::_('select.option',4,JText::_('COM_JEA_SELECT_TYPE_EN_RELATION_RELANCE'));
      
      $item = $this->getItem();
      $type_action = '-1';
      if ( !empty($item) ) {
        $type_action = $item->type_action;
      }
      
      $typesaction_list = JHTML::_('select.genericlist', $options,'type_action', '','value','text', $type_action);
      
      return $typesaction_list; 
    } 
}

