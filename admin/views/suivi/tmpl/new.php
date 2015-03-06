<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: edit.php 444 2013-10-11 11:57:34Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if ((float) JVERSION > 3) {
    // require dirname(__FILE__) . '/edit-J3.x.php' ;
    // return;
}

$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin( 'jea' );

JHtml::stylesheet('media/com_jea/css/jea.admin.css');
JHtml::script('media/com_jea/js/property.form.js', true);
?>
<div id="ajaxupdating">
  <h3><?php echo JText::_('COM_JEA_FEATURES_UPDATED_WARNING')?></h3>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_jea&view=suivi') ?>" method="post" id="adminForm" class="form-validate" enctype="multipart/form-data">

  <div class="width-60 fltlft span8 form-horizontal" style="width:100%;">
    <fieldset class="adminform">
      <legend>
      <?php echo JText::_('COM_JEA_NEW_ACTION_SUIVI') ?>
      </legend>

      <ul class="adminformlist">
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE') ?></label> <?php echo $this->types_list ?></li>
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE_ACTION') ?></label> <?php echo $this->typesactions_list ?></li>
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE_CONTACTNAME') ?></label> <?php echo $this->contact_list; ?></li>
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE_PROPERTYNAME') ?></label> <?php echo $this->properties; ?></li>
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE_DATEACTION') ?></label> <?php echo JHtml::calendar(date("Y-m-d"), 'dateaction', 'dateaction','%Y-%m-%d'); ?></li>
        <li><label><?php echo JText::_('COM_JEA_NEW_ACTION_TYPE_DESCRIPTION') ?></label> <textarea name="description"></textarea></li>        
      </ul>
    </fieldset>
  </div>
  
  <div>
    <input type="hidden" name="task" value="save" /> 
    <input type="hidden" name="return"
      value="<?php echo JRequest::getCmd('return') ?>" />
      <?php echo JHtml::_('form.token') ?>
  </div>

</form>
