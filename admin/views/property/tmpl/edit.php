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

<form action="<?php echo JRoute::_('index.php?option=com_jea&layout=edit&id='.(int) $this->item->id) ?>" method="post" id="adminForm" class="form-validate" enctype="multipart/form-data">

  <div class="width-60 fltlft span8 form-horizontal">
    <fieldset class="adminform">
      <legend>
      <?php echo empty($this->item->id) ? JText::_('COM_JEA_NEW_PROPERTY') : JText::sprintf('COM_JEA_EDIT_PROPERTY', $this->item->id) ?>
      </legend>

      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('ref') ?> <?php echo $this->form->getInput('ref') ?></li>
        <li><?php echo $this->form->getLabel('title') ?> <?php echo $this->form->getInput('title') ?></li>
        <li><?php echo $this->form->getLabel('alias') ?> <?php echo $this->form->getInput('alias') ?></li>
        <li><?php echo $this->form->getLabel('transaction_type') ?> <?php echo $this->form->getInput('transaction_type') ?></li>
        <li><?php echo $this->form->getLabel('type_id') ?> <?php echo $this->form->getInput('type_id') ?></li>
      </ul>

      <div class="clr"></div>
      <?php echo $this->form->getLabel('description') ?>
      <div class="clr"></div>
      <?php echo $this->form->getInput('description') ?>
      <div class="clr"></div>

      <fieldset style="margin-top:15px">
        <legend><?php echo JText::_('COM_JEA_OWNER_ANNOUNCE')?></legend>
        <?php echo $this->contactslist; ?>
      </fieldset>
      
      <fieldset style="margin-top:15px">
        <legend><?php echo JText::_('COM_JEA_DEMANDEUR_ANNOUNCE')?></legend>
        <?php echo $this->demandeurslist; ?>
      </fieldset>

      <fieldset style="margin-top:15px">
        <legend><?php echo JText::_('COM_JEA_LOCALIZATION')?></legend>
        <ul class="adminformlist">
        <?php foreach ($this->form->getFieldset('localization') as $field): ?>
          <li><?php echo $field->label . "\n" . $field->input ?></li>
        <?php endforeach ?>
        </ul>
      </fieldset>

      <fieldset>
        <legend><?php echo JText::_('COM_JEA_FINANCIAL_INFORMATIONS')?></legend>
        <ul class="adminformlist">
        <?php foreach ($this->form->getFieldset('financial_informations') as $field): ?>
          <li><?php echo $field->label . "\n" . $field->input ?></li>
        <?php endforeach ?>
        </ul>
      </fieldset>

      <fieldset>
        <legend><?php echo JText::_('COM_JEA_DETAILS')?></legend>
        <ul class="adminformlist">
        <?php foreach ($this->form->getFieldset('details') as $field): ?>
          <li><?php echo $field->label . "\n" . $field->input ?></li>
        <?php endforeach ?>
        </ul>
        <div class="clr"></div>

        <fieldset class="advantages">
          <legend><?php echo JText::_('COM_JEA_AMENITIES')?></legend>
          <div class="clr"></div>
          <?php echo $this->form->getInput('amenities') ?>
          <div class="clr"></div>
        </fieldset>

      </fieldset>

    </fieldset>
  </div>

  <div class="width-40 fltrt span4">
  <?php echo JHtml::_('sliders.start', 'property-sliders-'.$this->item->id, array('useCookie'=>1)) ?>

  <?php $dispatcher->trigger('onAfterStartPanels', array(&$this->item)) ?>

  <?php echo JHtml::_('sliders.panel', JText::_('COM_JEA_PUBLICATION_INFO'), 'params-pane') ?>
    <fieldset class="panelform">
      <ul class="adminformlist">
      <?php foreach ($this->form->getFieldset('publication') as $field): ?>
        <li><?php echo $field->label . "\n" . $field->input ?></li>
      <?php endforeach ?>
      </ul>
    </fieldset>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_JEA_PICTURES'), 'picture-pane') ?>
    <fieldset class="panelform">
    <?php echo $this->form->getInput('images') ?>
    </fieldset>
    
    <?php echo JHtml::_('sliders.panel', JText::_('COM_JEA_NOTES'), 'note-pane') ?>
    <fieldset class="panelform">
        <?php echo $this->form->getLabel('notes') ?>
        <div class="clr"></div>
        <?php echo $this->form->getInput('notes') ?>
    </fieldset>

    <?php $dispatcher->trigger('onBeforeEndPanels', array(&$this->item)) ?>

    <?php echo JHtml::_('sliders.end') ?>
  </div>

  <div class="clr"></div>

  <?php if ($this->canDo->get('core.admin')): ?>
  <div class="width-100 fltlft span8" style="margin-top: 20px">
  <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)) ?>

  <?php echo JHtml::_('sliders.panel', JText::_('COM_JEA_FIELDSET_RULES'), 'access-rules') ?>
    <fieldset class="panelform">
    <?php echo $this->form->getLabel('rules') ?>
    <?php echo $this->form->getInput('rules') ?>
    </fieldset>

    <?php echo JHtml::_('sliders.end') ?>
  </div>
  <?php endif; ?>

  <div>
    <input type="hidden" name="task" value="" /> <input type="hidden" name="return"
      value="<?php echo JRequest::getCmd('return') ?>" />
      <?php echo JHtml::_('form.token') ?>
  </div>

</form>
