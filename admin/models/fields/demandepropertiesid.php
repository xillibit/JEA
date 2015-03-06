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
class JFormFieldDemandepropertiesid extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'demandepropertiesid';
    
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
      
      $query = "SELECT * FROM #__jea_demandes_properties WHERE demande_id={$item->id}";      
      $db->setQuery($query);
      $properties_selected = $db->loadObjectList();
      
      $selected = array();
      if ( !empty($properties_selected) )
      {        
        foreach($properties_selected as $property)
        {
          $selected[] = $property->id;  
        }
      }
      
      $query = "SELECT * FROM #__jea_properties";
      $db->setQuery($query);
      $properties = $db->loadObjectList();
            
      $options = array();
      foreach($properties as $property)
      {
        $options[] = JHTML::_('select.option',$property->id,$property->ref);
      }
      
      $properties_list = JHTML::_('select.genericlist', $options,'id_properties[]', 'multiple="multiple" size="10"','value','text',$selected);
      
      return $properties_list; 
    }
}