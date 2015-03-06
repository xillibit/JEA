<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: featurelist.php 387 2012-11-28 12:55:10Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

JHtml::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jea/helpers/html');

/**
 * Form Field class for JEA.
 * Provides a list of features
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @see         JFormField
 */
class JFormFieldDemandecontactid extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'demandecontactid';
    
    /**
     * Method to get the list of features.
     *
     * @return  string  The field input markup.
     * @see JHtmlFeatures
     */
    protected function getInput()
    {
      $app = JFactory::getApplication();
      $item = $app->getUserState("com_jea.demande_item");
       
      $db = JFactory::getDBO();
      $query = "SELECT * FROM #__jea_contacts";
      $db->setQuery($query);
      $contacts = $db->loadObjectList();
            
      $options = array();
      foreach($contacts as $contact)
      {
        $options[] = JHTML::_('select.option',$contact->id,$contact->contactname.' '.$contact->lastname);
      }
      
      $contacts_list = JHTML::_('select.genericlist', $options,'demandecontactid', '','value','text', $item->id_contact);
      
      return $contacts_list; 
    }
}