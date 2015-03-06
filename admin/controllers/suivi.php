<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: properties.php 296 2012-04-04 22:31:14Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controlleradmin');


/**
 * Properties list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaControllerSuivi extends JControllerLegacy
{

    /**
     * Constructor.
     *
     * @param  array $config   An optional associative array of configuration settings.
     * @see    JController
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('unfeatured', 'featured');
    }

    /**
     * Method to toggle the featured setting of a list of properties.
     */
    function featured()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user   = JFactory::getUser();
        $ids    = JRequest::getVar('cid', array(), '', 'array');
        $values = array('featured' => 1, 'unfeatured' => 0);
        $task   = $this->getTask();
        $value  = JArrayHelper::getValue($values, $task, 0, 'int');

        // Access checks.
        foreach ($ids as $i => $id) {
            if (!$user->authorise('core.edit.state', 'com_jea.property.'.(int) $id)) {
                // Prune items that you can't change.
                unset($ids[$i]);
                JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
            }
        }

        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Publish the items.
            if (!$model->featured($ids, $value)) {
                JError::raiseWarning(500, $model->getError());
            }
        }

        $this->setRedirect('index.php?option=com_jea&view=contacts');
    }

    /**
     * Method to copy a list of properties.
     */
    public function copy()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user	= JFactory::getUser();
        $ids	= JRequest::getVar('cid', array(), '', 'array');

        // Access checks.
        if (!$user->authorise('core.create')) {
            JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
        } elseif (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Publish the items.
            if (!$model->copy($ids)) {
                JError::raiseWarning(500, $model->getError());
            }
        }

        $this->setRedirect('index.php?option=com_jea&view=suivi');
    }
    
    
    public function import()
    {
        $app = JFactory::getApplication();

        $model = $this->getModel('Import');
        $type = $app->input->get('type');

        $model->setState('import.type', $type);
        $model->setState('param.jea_version', $app->input->get('jea_version'));
        $model->setState('param.joomla_path', $app->input->get('joomla_path', '', 'string'));
        try {
            $model->import();
            $app->enqueueMessage(JText::sprintf('COM_JEA_PROPERTIES_FOUND_TOTAL', $model->total));
            $app->enqueueMessage(JText::sprintf('COM_JEA_PROPERTIES_UPDATED', $model->updated));
            $app->enqueueMessage(JText::sprintf('COM_JEA_PROPERTIES_CREATED', $model->created));
        } catch (Exception $e) {
            $this->setMessage($e->getMessage(), 'warning');
        }

        $this->setRedirect('index.php?option=com_jea&view=import&layout=' . $type);
    }
    
    public function add(){      
      $app = JFactory::getApplication();
      
      $task = $app->input->getCmd('task');
            
      $app->setUserState( 'com_jea.add.suivi.task', $task );
            
      $this->setRedirect('index.php?option=com_jea&view=suivi&layout=new');
    }
    
    public function edit() {
      $app = JFactory::getApplication();
      
      $task = $app->input->getCmd('task');
      $id = $app->input->getInt('id', 0 , 'int');
            
      $app->setUserState( 'com_jea.add.suivi.task', $task );
      $app->setUserState( 'com_jea.add.suivi.id', $id );
            
      $this->setRedirect('index.php?option=com_jea&view=suivi&layout=edit');
    }

    /* (non-PHPdoc)
     * @see JController::getModel()
     */
    public function getModel($name = 'Property', $prefix = 'JeaModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function cancelaction()
    { 
     $app = JFactory::getApplication(); 
     
     $app->setUserState( 'com_jea.add.suivi.task', null );
       
     $this->setRedirect('index.php?option=com_jea&view=suivi');
    }

    public function save()
    {
      $db = JFactory::getDBO();
      $app = JFactory::getApplication();
      
      $date = JDate::getInstance();
      
      $type = $app->input->get('type', null, 'int'); 
      $type_action = $app->input->get('type_action', null, 'int');
      $id_contact = $app->input->get('id_contact', 0, 'int');
      $id_property = $app->input->get('id_property', 0, 'int');
      $dateaction = $app->input->get('dateaction' , null, 'string');
      $description = $app->input->get('description' , null, 'string');
      
      $query = "INSERT INTO #__jea_suivi (type, type_action, id_contact, date, description) 
                VALUES({$db->quote($type)},{$db->quote($type_action)},{$db->quote($id_contact)},{$db->quote($date->toSql())},{$db->quote($description)})";
      $db->setQuery($query);
      $db->query();
      
      $app->enqueueMessage(JText::_('COM_JEA_LABEL_MESSAGE_SUIVI_ADDED_SUCCESSFULLY'));
      
      $this->setRedirect('index.php?option=com_jea&view=suivi');
    }
    
    public function edition()
    {
      $db = JFactory::getDBO();
      $app = JFactory::getApplication();
            
      $id = $app->input->get('id', null, 'int');
      if ( $id > 0 ) {
        $type = $app->input->get('type', null, 'int'); 
        $type_action = $app->input->get('type_action', null, 'int');
        $id_contact = $app->input->get('id_contact', 0, 'int');
        $id_property = $app->input->get('id_property', 0, 'int');
        $dateaction = $app->input->get('dateaction' , null, 'string');
        $description = $app->input->get('description' , null, 'string');
        
        $query = "UPDATE #__jea_suivi SET type={$db->quote($type)}, type_action={$db->quote($type_action)}, id_contact={$db->quote($id_contact)},id_property={$db->quote($id_property)}, date={$db->quote($dateaction)}, description={$db->quote($description)} WHERE id={$id}";
        $db->setQuery($query);
        $db->query();
        
        $app->enqueueMessage(JText::_('COM_JEA_LABEL_MESSAGE_SUIVI_UPDATED_SUCCESSFULLY'));
        
        $this->setRedirect('index.php?option=com_jea&view=suivi');      
      }   
    }
    
    public function delete(){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
            
        $id = $app->input->get('cid', array(), 'array');
        $id = array_shift($id);
                 
        $query = "DELETE FROM #__jea_suivi WHERE id={$id}";
        $db->setQuery($query);
        $db->query();
        
        $app->enqueueMessage(JText::_('COM_JEA_LABEL_MESSAGE_SUIVI_DELETED_SUCCESSFULLY'));
        
        $this->setRedirect('index.php?option=com_jea&view=suivi');
    } 
}

