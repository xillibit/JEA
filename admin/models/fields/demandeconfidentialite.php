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
class JFormFieldDemandeconfidentialite extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'demandeconfidentialite';
    
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
      
      $options = array();
      $options[] = JHTML::_('select.option',0,JText::_('COM_JEA_SELECT_CONFIDENTIALITE_FALSE'));
      $options[] = JHTML::_('select.option',1,JText::_('COM_JEA_SELECT_CONFIDENTIALITE_TRUE'));      
      
      $titres_list = JHTML::_('select.genericlist', $options,'demandeconfidentialite', '','value','text', $item->confidentielle);
      
      return $titres_list; 
    }
}