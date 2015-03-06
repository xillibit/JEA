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
class JeaControllerDemande extends JControllerForm
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
        $asset    = 'com_jea.property.'.$recordId;

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
        $jinput = $app->input;
        
        $id = JRequest::getInt('id', 0);
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        
        $demandecontactid = $jinput->get('demandecontactid',0,'int');
        $demandeetat = $jinput->get('demandeetat',0,'int');
        $confidentialite = $jinput->get('demandeconfidentialite',0,'int');
        $id_properties = $app->input->get('id_properties', array(), 'array');
        $date_realise = JFactory::getDate($data['date_realise'])->toSql();
                                                    
        if ( $id ==0 )
        {
          $query = "INSERT INTO #__jea_demandes (id_contact, activite, description, lieu_recherche, budget, etat, date_realise, confidentielle ) 
                    VALUES({$demandecontactid},{$db->quote($data['activite'])},{$db->quote($data['description'])},{$db->quote($data['lieu_recherche'])},{$db->quote($data['budget'])},{$demandeetat},{$db->quote($date_realise)},{$confidentialite});";
          $db->setQuery($query);
          $db->query();
          
          $demande_id = $db->insertid();          
          
          foreach($id_properties as $property)
          {             
            $query = "INSERT INTO #__jea_demandes_properties (demande_id, property_id) VALUES ($demande_id,$property)";
            $db->setQuery($query);
            $db->query();
          }          
                    
          $app->enqueueMessage('La nouvelle demande a été enregistré avec succés'); 
        } 
        else
        { 
          // On charge les données dans la table #__demandes_properties correspondantes pour mettre à jour
          $query = "SELECT property_id FROM #__jea_demandes_properties WHERE demande_id={$id}";           
          $db->setQuery($query);
          $objects = $db->loadObjectList();
                    
          $objects_remove = array();
          $objects_array = array();
          foreach($objects as $object)
          {
            if ( !in_array($object->property_id, $id_properties) )
            {
              $objects_remove[] = $object->property_id;  
            }
            
            $objects_array[] = $object->property_id;             
          }    
                    
          $objects_added = array();
          foreach($id_properties as $id_property)
          {
            if ( !in_array($id_property, $objects_array))
            {
              $objects_added[] = $id_property;
            }
          }
          
          if ( !empty($objects_remove) )
          {
            $objects_remove = implode(',', $objects_remove);
          
            $query = "DELETE FROM #__jea_demandes_properties WHERE property_id IN ({$objects_remove})";
            $db->setQuery($query);
            $db->query();                               
          }
          
          if ( !empty($objects_added) )
          {
            foreach($objects_added as $object)
            {             
              $query = "INSERT INTO #__jea_demandes_properties (demande_id, property_id) VALUES ($id,$object)";
              $db->setQuery($query);
              $db->query();
            }  
          }
          
          $query = "UPDATE #__jea_demandes SET etat={$demandeetat}, 
                                               date_realise={$db->quote($date_realise)}, 
                                               confidentielle={$confidentialite}, 
                                               id_contact={$demandecontactid}, 
                                               activite={$db->quote($data['activite'])}, 
                                               description={$db->quote($data['description'])}, 
                                               lieu_recherche={$db->quote($data['lieu_recherche'])}, 
                                               budget={$db->quote($data['budget'])} WHERE id={$id}";

          $db->setQuery($query);
          $db->query();
          
          $app->enqueueMessage('La demande a été mise à jour avec succés');  
        } 
                   
        $this->setRedirect('index.php?option=com_jea&view=demandes');
    }
}