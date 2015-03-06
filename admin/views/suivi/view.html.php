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

require JPATH_COMPONENT.DS.'helpers'.DS.'jea.php';

/**
 * View to list properties.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaViewSuivi extends JViewLegacy
{

    protected $sidebar = '';

    function display( $tpl = null )
    {
        $params = JComponentHelper::getParams('com_jea');
        $this->assignRef('params' , $params );

        JeaHelper::addSubmenu('suivi');

        $this->user        = JFactory::getUser();
        $this->items       = $this->get('Items');
        $this->pagination  = $this->get('Pagination');
        $this->state       = $this->get('State');
        $this->item        = $this->get('Item');

        // Get contacts names
        $contact_id_list = array();
        $property_id_list = array();
        foreach ($this->items as $suivi) {
          $contact_id_list[] = $suivi->id_contact;
          $property_id_list[] = $suivi->id_property;
        }

        $contact_id_list = implode(',',$contact_id_list);

        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__jea_contacts WHERE id IN ({$contact_id_list})";
        $db->setQuery($query);
        $this->contacts_objects = $db->loadObjectList('id');                 

        // Get properties name
        $property_id_list = implode(',',$property_id_list);
        
        $query = "SELECT * FROM #__jea_properties WHERE id IN ({$property_id_list})";
        $db->setQuery($query);
        $this->properties_objects = $db->loadObjectList('id');

        if ((float) JVERSION > 3) {
            $this->sidebar = JHtmlSidebar::render();
        }

        // On charge la bonne toolbar
        $app = JFactory::getApplication();
        $task = $app->getUserState('com_jea.add.suivi.task');
        
        $this->contact_list = $this->get('Contacts'); 
        $this->types_list = $this->get('Types');
        $this->typesactions_list = $this->get('TypesAction');
        $this->properties = $this->get('Properties');
                        
        if ( $task=='add' ) {
          $this->addToolbarNew();          
        } elseif ( $task=='edit' ) {
          $this->addToolbarEdit();          
        } else {          
          $this->addToolbar();
        }         

        parent::display($tpl);
    }
        
    /**
     * Add the page title and toolbar for default view.
     *
     */
    protected function addToolbar()
    {
        $canDo  = JeaHelper::getActions();
        $user   = JFactory::getUser();

        JToolBarHelper::title( JText::_('COM_JEA_SUIVI_MANAGEMENT'), 'jea.png' );

        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('add');
            JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'COM_JEA_COPY');
        }

        if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
            JToolBarHelper::editList('edit');
        }
                
        if ($canDo->get('core.delete')) {
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_JEA_MESSAGE_CONFIRM_DELETE'), 'delete');
        }

        /*if ($canDo->get('core.admin')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_jea');
        }*/
    }
    
    /**
     * Add the page title and toolbar for edit view.
     *
     */
     protected function addToolbarEdit() 
     {
      JToolBarHelper::title( JText::_('COM_JEA_SUIVI_MANAGEMENT_EDIT'), 'jea.png' );
            
			JToolBarHelper::save('edition');
      JToolBarHelper::cancel('cancelaction', 'JTOOLBAR_CLOSE');
     }
     
     /**
     * Add the page title and toolbar for new view.
     *
     */
     protected function addToolbarNew() 
     {
      JToolBarHelper::title( JText::_('COM_JEA_SUIVI_MANAGEMENT_NEW'), 'jea.png' );
      
      JToolBarHelper::apply('apply');
			JToolBarHelper::save('save');
      JToolBarHelper::cancel('cancelaction', 'JTOOLBAR_CLOSE');
     }
}
