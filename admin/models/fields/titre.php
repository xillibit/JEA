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
class JFormFieldTitre extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'titre';
    
    /**
     * Method to get the list of features.
     *
     * @return  string  The field input markup.
     * @see JHtmlFeatures
     */
    protected function getInput()
    {
      $options = array();
      $options[] = JHTML::_('select.option',-1,JText::_('COM_JEA_SELECT_CONTACT_TITRE'));
      $options[] = JHTML::_('select.option',1,JText::_('COM_JEA_SELECT_CONTACT_MONSIEUR'));
      $options[] = JHTML::_('select.option',2,JText::_('COM_JEA_SELECT_CONTACT_MADAME'));
      $options[] = JHTML::_('select.option',3,JText::_('COM_JEA_SELECT_CONTACT_MADEMOISELLE'));
      
      $titres_list = JHTML::_('select.genericlist', $options,'contacttitre', '','value','text');
      
      return $titres_list; 
    }
}