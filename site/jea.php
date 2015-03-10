<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: jea.php 428 2013-08-25 15:26:30Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include dependancies
jimport('joomla.application.component.controller');

$input = JFactory::getApplication()->input;

$task = $input->getCmd('task');

if ($task == '') {
    // In order to execute controllers/default.php as default controller
    // and display as default method
    $input->set('task', 'default.display');
}

if ($task == 'properties.sendcontactform' || $task == 'sendMailOwner' ) {
  $view = strtolower ( JRequest::getWord ( 'view' ) );
  $controller = 'JeaController' . ucfirst ( $view );

  $path = JPATH_COMPONENT . "/controllers/{$view}.php";
  require_once $path;

  $instance = new $controller ();
  $instance->execute(JRequest::getCmd('task'));
  $instance->redirect();
}

$controller = JControllerLegacy::getInstance('jea');
if ((float) JVERSION > 3) {
    $controller->authorise($input->getCmd('task'));
}

$controller->execute($input->getCmd('task'));
$controller->redirect();


