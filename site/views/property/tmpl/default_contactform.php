<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: default_contactform.php 299 2012-04-05 01:55:25Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');
$uri = JFactory::getURI();
?>

<form action="<?php echo JRoute::_('index.php?option=com_jea&task=sendmailcontact') ?>" method="post" id="jea-contact-form" enctype="application/x-www-form-urlencoded">
  <fieldset>
    <legend><?php echo JText::_('COM_JEA_CONTACT_FORM_LEGEND') ?></legend>
           
    <label><?php echo JText::_('COM_JEA_NAME') ?> :</label>
    <input name="name" type="text" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_NAME_PLACEHOLDER') ?>" value="<?php echo $this->escape($this->state->get('contact.name')) ?>" required />
            
    <label><?php echo JText::_('COM_JEA_EMAIL') ?> :</label>
    <input name="email" type="text" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_EMAIL_PLACEHOLDER') ?>" value="<?php echo $this->escape($this->state->get('contact.email')) ?>" required />
      
    <label><?php echo JText::_('COM_JEA_TELEPHONE') ?> :</label>
    <input name="telephone" type="text" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_PHONE_PLACEHOLDER') ?>" value="<?php echo $this->escape($this->state->get('contact.telephone')) ?>" required />
     
    <label><?php echo JText::_('COM_JEA_SUBJECT') ?> :</label>
    <input name="subject" type="text" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_SUBJECT_PLACEHOLDER') ?>" value="<?php echo JText::_('COM_JEA_REF') ?> : <?php echo $this->escape($this->row->ref) ?>" required />
      
    <label><?php echo JText::_('COM_JEA_MESSAGE') ?> :</label>
    <textarea name="message" id="e_message" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_MESSAGE_PLACEHOLDER') ?>" rows="10" cols="40" required><?php echo $this->escape($this->state->get('contact.message')) ?></textarea>
      
    <?php if ($this->params->get('use_captcha')):?> 
      <div id="dynamic_recaptcha_1"></div>
    <?php endif ?>            
      
    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_JEA_SEND') ?></button>
      
    <input type="hidden" name="propertyURL" value="<?php echo base64_encode($uri->toString())?>" />
    <input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
    <input type="hidden" name="view" value="property" />
    <?php echo JHTML::_( 'form.token' ) ?>
  </fieldset>
</form>  
