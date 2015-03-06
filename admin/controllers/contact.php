<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: property.php 449 2014-01-26 12:54:38Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controllerform');

/**
 * Property controller class.
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaControllerContact extends JControllerForm
{

    /* (non-PHPdoc)
     * @see JControllerForm::allowEdit()
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user     = JFactory::getUser();
        $userId   = $user->get('id');
        $asset    = 'com_jea.contact.'.$recordId;

        // Check general edit permission first.
        if ($user->authorise('core.edit', $asset)) {
            return true;
        }

        // Fallback on edit.own.
        // First test if the permission is available.
        if ($user->authorise('core.edit.own', $asset)) {
            // Now test the owner is the user.
            $ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
            if (empty($ownerId) && $recordId) {
                // Need to do a lookup from the model.
                $record= $this->getModel()->getItem($recordId);
                if (empty($record)) {
                    return false;
                }
                $ownerId = $record->created_by;
            }

            // If the owner matches 'me' then do the test.
            if ($ownerId == $userId) {
                return true;
            }
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }
    
    public function save($key = NULL, $urlVar = NULL)
    {      
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        
        $id = JRequest::getInt('id', 0);
        $data = JRequest::getVar('jform', array(), 'post', 'array');
                                
        if ( $id ==0 )
        {
          $query = "INSERT INTO #__jea_contacts (contactname, lastname, adress, code_postal, ville, telephone, mail ) 
                    VALUES({$db->quote($data['contactname'])},{$db->quote($data['lastname'])},{$db->quote($data['adress'])},{$db->quote($data['code_postal'])},{$db->quote($data['ville'])},{$db->quote($data['phone'])},{$db->quote($data['mail'])});";
          
          $db->setQuery($query);
          $db->query();
          
          $app->enqueueMessage('Le nouveau contact a été enregistré avec succés'); 
        } else {
          $query = "UPDATE #__jea_contacts SET contactname={$db->quote($data['contactname'])}, lastname={$db->quote($data['lastname'])}, adress={$db->quote($data['adress'])}, code_postal={$db->quote($data['code_postal'])}, ville={$db->quote($data['ville'])}, telephone={$db->quote($data['phone'])}, mail={$db->quote($data['mail'])} WHERE id={$id}";
          $db->setQuery($query);
          $db->query();
          
          $app->enqueueMessage('Le contact a été mis à jour avec succés');
        }        
        
        $this->setRedirect('index.php?option=com_jea&view=contacts'); 
    }
    
    public function apply()
    {
    
    }
}
