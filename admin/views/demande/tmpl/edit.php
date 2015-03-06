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

<form action="<?php echo JRoute::_('index.php?option=com_jea&layout=demande&task=save&id='.(int) $this->item->id) ?>" method="post" id="adminForm" class="form-validate" enctype="multipart/form-data">
  <div class="width-60 fltlft span8 form-horizontal" style="width:100%;">
    <fieldset class="adminform">
      <legend>
      <?php echo empty($this->item->id) ? JText::_('COM_JEA_NEW_DEMANDE') : JText::sprintf('COM_JEA_EDIT_DEMANDE', $this->item->id) ?>
      </legend>

      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('id_contact') ?> <?php echo $this->form->getInput('id_contact') ?></li>
        <li><?php echo $this->form->getLabel('id_properties') ?> <?php echo $this->form->getInput('id_properties') ?></li>
        <li><?php echo $this->form->getLabel('activite') ?> <?php echo $this->form->getInput('activite') ?></li>
        <li><?php echo $this->form->getLabel('description') ?> <?php echo $this->form->getInput('description') ?></li>
        <li><?php echo $this->form->getLabel('lieu_recherche') ?> <?php echo $this->form->getInput('lieu_recherche') ?></li>
        <li><?php echo $this->form->getLabel('budget') ?> <?php echo $this->form->getInput('budget') ?></li>
        <li><?php echo $this->form->getLabel('etat') ?> <?php echo $this->form->getInput('etat') ?></li>
        <li><?php echo $this->form->getLabel('date_realise') ?> <?php echo $this->form->getInput('date_realise') ?></li>
        <li><?php echo $this->form->getLabel('confidentielle') ?> <?php echo $this->form->getInput('confidentielle') ?></li>        
      </ul>
    </fieldset>
  </div>
  
  <div>
    <input type="hidden" name="task" value="" /> 
    <input type="hidden" name="return"
      value="<?php echo JRequest::getCmd('return') ?>" />
      <?php echo JHtml::_('form.token') ?>
  </div>

</form>
