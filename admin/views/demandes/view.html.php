<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: view.html.php 428 2013-08-25 15:26:30Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

require JPATH_COMPONENT . '/helpers/jea.php';

/**
 * View to list properties.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaViewDemandes extends JViewLegacy
{
  protected $sidebar = '';

  function display( $tpl = null )
  {
    $params = JComponentHelper::getParams('com_jea');
    $this->assignRef('params' , $params );

    JeaHelper::addSubmenu('demandes');

    $this->user        = JFactory::getUser();
    $this->items       = $this->get('Items');
    $this->pagination  = $this->get('Pagination');
    $this->state       = $this->get('State');

    $this->nbbiensenrelations = $this->get('NbBiensEnRelation');

    if ((float) JVERSION > 3) {
      $this->sidebar = JHtmlSidebar::render();
    }

    $this->addToolbar();

    parent::display($tpl);
  }

  /**
    * Add the page title and toolbar.
    *
  */
  protected function addToolbar()
  {
    $canDo  = JeaHelper::getActions();
    $user   = JFactory::getUser();

    JToolBarHelper::title( JText::_('COM_JEA_DEMANDES_MANAGEMENT'), 'jea.png' );

    if ($canDo->get('core.create')) {
      JToolBarHelper::addNew('demande.add');
      JToolBarHelper::custom('demande.copy', 'copy.png', 'copy_f2.png', 'COM_JEA_COPY');
    }

    if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
      JToolBarHelper::editList('demande.edit');
    }

    if ($canDo->get('core.delete')) {
      JToolBarHelper::divider();
      JToolBarHelper::deleteList(JText::_('COM_JEA_MESSAGE_CONFIRM_DELETE'), 'demandes.delete');
    }
  }
}