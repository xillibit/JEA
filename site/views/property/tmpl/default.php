<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: default.php 450 2014-01-26 14:07:06Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::stylesheet('media/com_jea/css/jea.css');
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('jea');

$document = JFactory::getDocument();
$document->addScriptDeclaration("
  jQuery(document).ready(function() {
    jQuery('#modal_send_mail').click(function() {
      jQuery('#modal_form_send_mail').submit(function( event ) {

      });
    });
  });
");

$uri = JFactory::getURI();
?>

<p class="prev-next-navigation">
<?php echo $this->getPrevNextNavigation() ?>
</p>

<?php if ($this->params->get('show_print_icon')): ?>
<div class="jea-tools">
  <a href="javascript:window.print()" title="<?php echo JText::_('JGLOBAL_PRINT') ?>">
  <?php echo JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true) ?></a>
</div>
<?php endif ?>

<h1><?php echo $this->page_title ?></h1>  <span class="label label-info"><?php echo JText::_('COM_JEA_REF')?> : <?php echo $this->escape($this->row->ref) ?></span>

<?php if ( $this->params->get('show_creation_date', 0) ) : ?>
<p>
  <span class="date"><?php echo JHtml::_('date',  $this->row->created, JText::_('DATE_FORMAT_LC3') ) ?></span>
</p>
<?php endif ?>

<?php if(!empty($this->row->images)): ?>
<div id="jea-gallery">
<?php echo $this->loadTemplate($this->params->get('images_layout', 'squeezebox')) ?>
</div>
<?php endif ?>


<div class="jea-col-right">
  <h3>Contacter le propriétaire :</h3>
  <div class="btn-group">
    <a href="#modal_mail_owner" role="button" class="btn btn-primary" data-toggle="modal">Envoyer un mail</a>    
    <a href="#modal_tel_owner" role="button" class="btn btn-primary" data-toggle="modal">Voir le numéro de téléphone</a>
  </div>
  
  <!-- Boite modale pour envoyer un mail au propriétaire -->
  <div id="modal_mail_owner" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Envoyer un mail au propriétaire</h3>
    </div>
    <form action="<?php echo JRoute::_('index.php?option=com_jea&task=sendMailOwner') ?>" method="post" id="modal_form_send_mail" class="form-horizontal">
      <div class="modal-body">
        <b>Annonce concernée :</b> <?php echo $this->page_title ?> 
        <br /><br />        
          <div class="control-group">
            <label class="control-label" for="inputNom">Nom</label>
            <div class="controls">
              <input name="nom" type="text" id="inputNom" placeholder="Votre nom" required="required">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputPrenom">Prénom</label>
            <div class="controls">
              <input name="prenom" type="text" id="inputPrenom" placeholder="Votre prénom" required="required">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputTelephone">Numéro de téléphone</label>
            <div class="controls">
              <input name="telephone" type="text" id="inputTelephone" placeholder="Votre numéro de téléphone" required="required">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputMail">Adresse email</label>
            <div class="controls">
              <input name="email" type="text" id="inputMail" placeholder="Votre adresse email" required="required">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputMessage">Votre message</label>
            <div class="controls">
              <textarea name="message" placeholder="Entrez votre message" id="inputMessage" required="required"></textarea>
            </div>
          </div>         
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
        <button class="btn btn-primary" id="modal_send_mail">Envoyer</button>
      </div>
      <input type="hidden" name="propertyURL" value="<?php echo base64_encode($uri->toString())?>" />
      <input type="hidden" name="id_annonce" value="<?php echo $this->row->id ?>" />
      <input type="hidden" name="view" value="property" />
      <?php echo JHTML::_( 'form.token' ) ?>
    </form>
  </div>
  
  <!-- Boite modale pour voir le numéro de téléphone du proprétaire -->  
  <div id="modal_tel_owner" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Numéro de téléphone du propriétaire</h3>
    </div>
    <div class="modal-body">
      <p><b>Annonce concernée :</b><?php echo $this->page_title ?></p>
      
      <p><b>Numéro de téléphone :</b></p>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>      
    </div>
  </div>
  
  <?php if ($this->row->address || $this->row->zip_code || $this->row->town ): ?>
  <h3><?php echo JText::_('COM_JEA_FIELD_ADDRESS_LABEL') ?> :</h3>
  <address>
  <?php if ($this->row->address):?> 
  <?php echo $this->escape($this->row->address ) ?><br />
  <?php endif ?>
  <?php if ($this->row->zip_code) echo $this->escape($this->row->zip_code ) ?> 
  <?php if ($this->row->town) echo $this->escape($this->row->town ) ?>
  </address>
  <?php endif ?>

  <?php if ($this->row->area) :?>
  <p><?php echo JText::_('COM_JEA_FIELD_AREA_LABEL') ?> : 
  <strong> <?php echo$this->escape( $this->row->area ) ?></strong></p>
  <?php endif  ?>

  <?php if (!empty($this->row->amenities)): ?>
  <h3><?php echo JText::_('COM_JEA_AMENITIES')?> :</h3>
  <?php echo JHtml::_('amenities.bindList', $this->row->amenities, 'ul') ?>
  <?php endif ?>
</div>

<?php if (intval($this->row->availability)): ?>
<p class="availability">
<?php echo JText::_('COM_JEA_FIELD_PROPERTY_AVAILABILITY_LABEL') ?> : 
<?php echo JHTML::_('date',  $this->row->availability, JText::_('DATE_FORMAT_LC3') ) ?>
</p>
<?php endif  ?>

<h3><?php echo JText::_('COM_JEA_FINANCIAL_INFORMATIONS') ?> : </h3>

<table class="jea-data" >
  <tr>
    <th><?php echo $this->row->transaction_type == 'RENTING' ? JText::_('COM_JEA_FIELD_PRICE_RENT_LABEL') :  JText::_('COM_JEA_FIELD_PRICE_LABEL') ?></th>
    <td class="right">
      <?php echo JHtml::_('utility.formatPrice', (float) $this->row->price, JText::_('COM_JEA_CONSULT_US')) ?>
      <?php if ($this->row->transaction_type == 'RENTING' && (float)$this->row->price != 0.0): ?>
      <span class="rate_frequency"><?php echo JText::_('COM_JEA_PRICE_PER_FREQUENCY_'. $this->row->rate_frequency) ?></span>
      <?php endif ?>
    </td>
  </tr>

  <?php if ((float)$this->row->charges > 0): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_CHARGES_LABEL') ?></th>
    <td class="right"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->charges) ?></td>
  </tr>
  <?php endif  ?>

  <?php if ($this->row->transaction_type == 'RENTING' &&  (float) $this->row->deposit > 0 ): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_DEPOSIT_LABEL') ?></th>
    <td class="right"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->deposit) ?></td>
  </tr>
  <?php endif  ?>

  <?php if ((float)$this->row->fees > 0): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_FEES_LABEL') ?></th>
    <td class="right"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->fees) ?></td>
  </tr>
  <?php endif  ?>
</table>

<h3><?php echo JText::_('COM_JEA_DETAILS') ?> : </h3>
<table class="jea-data" >
  <?php if ($this->row->condition): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_CONDITION_LABEL') ?></th>
    <td><?php echo $this->escape($this->row->condition) ?></td>
  </tr>
  <?php endif  ?>

  <?php if ($this->row->living_space): ?>
  <tr>
    <th><?php echo  JText::_( 'COM_JEA_FIELD_LIVING_SPACE_LABEL' ) ?></th>
    <td><?php echo JHtml::_('utility.formatSurface', (float) $this->row->living_space ) ?></td>
  </tr>
  <?php endif ?>

  <?php if ($this->row->land_space): ?>
  <tr>
    <th><?php echo  JText::_( 'COM_JEA_FIELD_LAND_SPACE_LABEL' ) ?></th>
    <td><?php echo JHtml::_('utility.formatSurface', (float) $this->row->land_space ) ?></td>
  </tr>
  <?php endif ?>

  <?php if ($this->row->rooms): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_ROOMS_LABEL') ?></th>
    <td><?php echo $this->row->rooms ?></td>
  </tr>
  <?php endif  ?>

  <?php if ($this->row->bedrooms): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_BEDROOMS_LABEL') ?></th>
    <td><?php echo $this->row->bedrooms ?></td>
  </tr>
  <?php endif  ?>

  <?php if ($this->row->orientation != '0'): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_ORIENTATION_LABEL') ?></th>
    <td>
    <?php
     switch ($this->row->orientation) {
         case 'N':
             echo JText::_('COM_JEA_OPTION_NORTH');
             break;
         case 'NW':
             echo JText::_('COM_JEA_OPTION_NORTH_WEST');
             break;
         case 'NE':
             echo JText::_('COM_JEA_OPTION_NORTH_EAST');
             break;
         case 'NS':
             echo JText::_('COM_JEA_OPTION_NORTH_SOUTH');
             break;
         case 'E':
             echo JText::_('COM_JEA_OPTION_EAST');
             break;
         case 'EW':
             echo JText::_('COM_JEA_OPTION_EAST_WEST');
             break;
         case 'W':
             echo JText::_('COM_JEA_OPTION_WEST');
             break;
         case 'S':
             echo JText::_('COM_JEA_OPTION_SOUTH');
             break;
         case 'SW':
             echo JText::_('COM_JEA_OPTION_SOUTH_WEST');
             break;
         case 'SE':
             echo JText::_('COM_JEA_OPTION_SOUTH_EAST');
             break;
     }
    ?>
     </td>
  </tr>
  <?php endif ?>

  <?php if ($this->row->floor): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_FLOOR_LABEL') ?></th>
    <td><?php echo $this->row->floor ?></td>
  </tr>
  <?php endif  ?>

  <?php if ( $this->row->floors_number ): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_FLOORS_NUMBER_LABEL') ?></th>
    <td><?php echo $this->row->floors_number ?></td>
  </tr>
  <?php endif  ?>

  <?php if ( $this->row->bathrooms ): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_BATHROOMS_LABEL') ?></th>
    <td><?php echo $this->row->bathrooms ?></td>
  </tr>
  <?php endif  ?>

  <?php if ($this->row->toilets): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_TOILETS_LABEL') ?></th>
    <td><?php echo $this->row->toilets ?></td>
  </tr>
  <?php endif  ?>

  <?php if ( $this->row->heating_type_name ): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_HEATINGTYPE_LABEL') ?></th>
    <td><?php echo $this->escape( $this->row->heating_type_name) ?></td>
  </tr>
  <?php endif  ?>

  <?php if ( $this->row->hot_water_type_name ): ?>
  <tr>
    <th><?php echo JText::_('COM_JEA_FIELD_HOTWATERTYPE_LABEL') ?></th>
    <td><?php echo $this->escape( $this->row->hot_water_type_name) ?></td>
  </tr>
  <?php endif  ?>
</table>

<?php $dispatcher->trigger('onBeforeShowDescription', array(&$this->row)) ?>

<div class="property-description clr">
<?php echo $this->row->description ?>
</div>

<?php $dispatcher->trigger('onAfterShowDescription', array(&$this->row)) ?>

<?php if ( $this->params->get('show_googlemap') ): ?>
<h3><?php echo JText::_('COM_JEA_GEOLOCALIZATION') ?> :</h3>
<?php echo $this->loadTemplate('googlemap') ?>
<?php endif ?>

<?php //if ( $this->params->get('show_contactform') ): ?>
<?php //echo $this->loadTemplate('contactform') ?>
<?php //endif  ?>

<p>
  <a href="<?php echo JRoute::_('index.php?option=com_jea&view=properties')?>" class="btn"><?php echo JText::_('COM_JEA_RETURN_TO_THE_LIST')?> </a>
</p>
