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
class JeaControllerDemandes extends JControllerAdmin
{
  public function delete()
  {
    $db = JFactory::getDBO();
    $app = JFactory::getApplication();
      
    $ids = $app->input->get('cid', array(), 'array');
    
    $ids = implode(',', $ids);
        
    $query = "DELETE FROM #__jea_demandes WHERE id IN ({$ids})";
    $db->setQuery($query);
    $db->query();
    
    $affected = $db->getAffectedRows();
    
    $query = "DELETE FROM #__jea_demandes_properties WHERE property_id IN ({$ids})";
    $db->setQuery($query);
    $db->query();
    
    $app->enqueueMessage('Les '.$affected.' demandes sélectionnées ont été supprimées');
    
    $this->setRedirect('index.php?option=com_jea&view=demandes');
  }
}