<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: properties.php 428 2013-08-25 15:26:30Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Properties controller class.
 *
 * @package     Joomla.Site
 * @subpackage  com_jea
 */
class JeaControllerProperties extends JControllerLegacy
{
    protected $default_view = 'properties';

    public function search()
    {
        $app = JFactory::getApplication();
        $app->input->set('layout', 'default');
        $this->display();
    }
     
    public function sendcontactform()
    {
        
                
        // Get the document object.
        /*$document = JFactory::getDocument();
        
        $document->setMimeEncoding('application/json');
        JResponse::setHeader('Content-Disposition','attachment;filename="properties.contact.form.json"');
        
        $result = array('success', 1);
        
        echo json_encode($result);
        
        JFactory::getApplication()->close(); */
        
        // Check for request forgeries
        /*if (!JSession::checkToken()) {
            return $this->setRedirect(JRoute::_('index.php?option=com_jea'), JText::_('JINVALID_TOKEN'), 'warning');
        }

        $model = $this->getModel('Property', 'JeaModel');
        $returnURL = $model->getState('contact.propertyURL');

        if (!$model->sendContactForm()) {
            $errors = $model->getErrors();
            $msg = '';
            foreach ($errors as $error) {
                $msg .= $error . "\n";
            }
            return $this->setRedirect(JRoute::_('index.php?option=com_jea'), $msg, 'warning');
        }
        $msg = JText::_('COM_JEA_CONTACT_FORM_SUCCESSFULLY_SENT');

        $this->setRedirect(JRoute::_('index.php?option=com_jea'), $msg);*/
    }
}
