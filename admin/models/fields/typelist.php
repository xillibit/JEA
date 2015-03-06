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
class JFormFieldTypelist extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'typelist';
    
    /**
     * Method to get the list of features.
     *
     * @return  string  The field input markup.
     * @see JHtmlFeatures
     */
    protected function getInput()
    {
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_CONTACT_TYPE_SELECT'));
      $options[] = JHTML::_('select.option',1,JText::_('COM_JEA_SELECT_CONTACT_TYPE_DEPOSANT'));
      $options[] = JHTML::_('select.option',2,JText::_('COM_JEA_SELECT_CONTACT_TYPE_DEMANDEUR'));      
      
      $types_list = JHTML::_('select.genericlist', $options,'jform[contacttype]', '','value','text');
      
      return $types_list; 
    }
}