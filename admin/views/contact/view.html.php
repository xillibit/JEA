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
 * View to edit property.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaViewContact extends JViewLegacy
{

    protected $form;
    protected $item;
    protected $state;
    protected $canDo;

    function display( $tpl = null )
    {

        $this->form   = $this->get('Form');
        $this->item   = $this->get('Item');
        $this->state  = $this->get('State');
        $this->canDo  = JeaHelper::getActions($this->item->id);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();

        parent::display($tpl);
    }


    /**
     * Add the page title and toolbar.
     *
     * Inspired from ContentViewArticle in com_content
     *
     */
    function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);
        $user       = JFactory::getUser();
        $userId     = $user->get('id');
        $isNew      = ($this->item->id == 0);

        $title = JText::_('COM_JEA_CONTACT_MANAGEMENT') . ' : ';
        $title .= $isNew ? JText::_( 'JACTION_CREATE' ) : JText::_( 'JACTION_EDIT' );

        JToolBarHelper::title( $title , 'jea.png' ) ;

        // Built the actions for new and existing records.
        // For new records, check the create permission.
        JToolBarHelper::apply('contact.apply');
        JToolBarHelper::save('contact.save');
        JToolBarHelper::save2new('contact.save2new');
        JToolBarHelper::cancel('contact.cancel');
    }

}
