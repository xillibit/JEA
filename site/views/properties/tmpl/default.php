<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: default.php 444 2013-10-11 11:57:34Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::stylesheet('media/com_jea/css/jea.css');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$rowsCount = count( $this->items );

$script=<<<EOB
function changeOrdering( order, direction )
{
	var form = document.getElementById('jForm');
	form.filter_order.value = order;
	form.filter_order_Dir.value = direction;
	form.submit();
}
EOB;
JHtml::_('behavior.framework');
$this->document->addScriptDeclaration($script);

$listOrder      = $this->escape($this->state->get('list.ordering'));
$listDirection  = $this->escape($this->state->get('list.direction'));

?>
<h1>
Bourse immobilière
</h1>
<div id="modal_alert_result" class="alert" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="modal_alert_result_span"></span>
</div>
<div class="jea-properties<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
<a href="#ModalForm" id="modalOpenButton" role="button" class="btn btn-success" data-toggle="modal">Proposer votre annonce de vente ou location</a>
<!-- Formulaire pour proposer une annonce de vente ou location -->
<div id="ModalForm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalFormLabel" aria-hidden="true">
  <form id="jea_modal_form_new_announce" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_jea&task=properties.sendcontactform&view=properties') ?>" method="post">
  <div class="modal-header">    
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h2 id="myModalFormLabel"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_TITLE_LABEL') ?></h2>
    <h5><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_DESCRIPTION') ?></h5>
  </div>
  <div class="modal-body">    
      <div class="control-group">
        <label class="control-label" for="inputTitle"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_TITLE') ?> :</label>
        <div class="controls">
          <input type="text" name="titre" id="inputTitle" placeholder="<?php echo JText::_('COM_JEA_FORM_TITLE_PLACEHOLDER') ?>"  value="" pattern="[A-F][0-9]" required />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputAnnounceType"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_ANNOUNCE_TYPE') ?> :</label>
        <div class="controls">
          <input type="radio" name="optionsRadios" value="1">
          <?php echo JText::_('COM_JEA_FORM_ANNOUNCE_ANNOUNCE_TYPE_LOCATION') ?>
          <input type="radio" name="optionsRadios" value="2">
          <?php echo JText::_('COM_JEA_FORM_ANNOUNCE_ANNOUNCE_TYPE_VENTE') ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_NAME') ?> :</label>
        <div class="controls">
          <input type="text" name="name" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_NAME_PLACEHOLDER') ?>" id="inputName" value="" pattern="[A-F]" required />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputDescription"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_DESCRIPTION') ?> :</label>
        <div class="controls">
          <textarea name="annonce_desc" id="inputDescription" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_DESCRIPTION_PLACEHOLDER') ?>" value="" pattern="[A-F][0-9]" required></textarea>
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputPhone"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_PHONE') ?> :</label>
        <div class="controls">
          <input type="text" name="phone" id="inputPhone" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_PHONE_PLACEHOLDER') ?>" value="" required />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_EMAIL') ?> :</label>
        <div class="controls">
          <input type="text" name="email" id="inputEmail" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_EMAIL_PLACEHOLDER') ?>" value="" required />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputLoyer"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_PRICE') ?> :</label>
        <div class="controls">
          <input type="text" name="loyer" id="inputLoyer" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_PRICE_PLACEHOLDER') ?>" value="" />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputPrixDeVente"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_SELLING_PRICE') ?> :</label>
        <div class="controls">
          <input type="text" name="prix_vente" id="inputPrixDeVente" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_YOUR_SELLING_PLACEHOLDER') ?>" value="" />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputAdresse"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_ADRESS') ?> :</label>
        <div class="controls">          
          <input type="text" name="adresse" id="inputAdresse" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_ADRESS_PLACEHOLDER') ?>" value="" />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputCodePostal"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_POSTAL_CODE') ?> :</label>
        <div class="controls">          
          <input type="text" name="code_postal" id="inputCodePostal" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_POSTAL_CODE_PLACEHOLDER') ?>" value="" />
        </div>    
      </div>
      <div class="control-group">
        <label class="control-label" for="inputVille"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_YOUR_CITY') ?> :</label>
        <div class="controls">          
          <input type="text" name="ville" id="inputVille" placeholder="<?php echo JText::_('COM_JEA_FORM_YOUR_CITY_PLACEHOLDER') ?>" value="" />
        </div>    
      </div>
      <div class="control-group">        
        <div class="controls">          
          <input type="checkbox" name="copie" id="recevoir_copie" value="1"> <?php echo JText::_('COM_JEA_FORM_ANNOUNCE_RECEIVE_COPY') ?>
        </div>    
      </div>       
      <?php echo JHtml::_( 'form.token' ); ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button id="save_form_announce" type="submit" class="btn btn-primary"><?php echo JText::_('COM_JEA_FORM_ANNOUNCE_SUBMIT_FORM') ?></button>
  </div>  
  </form>
</div>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
  <?php if ($this->params->get('page_heading')) : ?>
  <h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
  <?php else: ?>
  <h1><?php echo $this->escape($this->params->get('page_title')) ?></h1>
  <?php endif ?>
<?php endif ?>

<?php if ($this->state->get('searchcontext') === true): ?>
  <div class="search_parameters">
    <h2><?php echo JText::_('COM_JEA_SEARCH_PARAMETERS_TITLE') ?> :</h2>
    <?php echo $this->loadTemplate('remind') ?>
  </div>
<?php endif ?>

<?php if (!empty($this->items)): ?>

  <form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()) ?>" id="jForm" method="post">

    <p class="sort-options">
    <?php echo implode(' | ', $this->sort_links)  ?>
    </p>

    <p class="limitbox">
      <em>Trier par commune : </em>
      <select name="filter_town_id" onchange="this.form.submit()">
        <option>Choix d'une commune</option>
        <option value="0">Afficher toutes les communes</option>
        <option value="10">Cons-Sainte-Colombe</option>
        <option value="6">Chevaline</option>
        <option value="2">Doussard</option>
        <option value="1">Faverges</option>
        <option value="9">Lathuile</option>
        <option value="3">Giez</option>
        <option value="4">Montmin</option>
        <option value="5">Marlens</option>
        <option value="8">Saint-ferreol</option>
        <option value="7">Seythenex</option>
      </select>
    </p>

    <p class="limitbox">
      <em><?php echo JText::_('COM_JEA_RESULTS_PER_PAGE') ?> : </em>
      <?php echo $this->pagination->getLimitBox() ?>
    </p>
    
    <div class="jea-items">
    <?php foreach ($this->items as $k => $row): ?>
    <?php $row->slug = $row->alias ? ($row->id . ':' . $row->alias) : $row->id; $imgUrl = $this->getFirstImageUrl($row); ?>

      <dl class="jea_item">
        <dt class="title">
          <a href="<?php echo JRoute::_('index.php?option=com_jea&view=property&id='. $row->slug) ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>"> <strong> 
          <?php if(empty($row->title)): ?>
          <?php echo ucfirst( JText::sprintf('COM_JEA_PROPERTY_TYPE_IN_TOWN', $this->escape($row->type), $this->escape($row->town) ) ) ?>
          <?php else : echo $this->escape($row->title) ?> 
          <?php endif ?></strong> 
          <span class="label label-info"><?php echo JText::_('COM_JEA_REF' ) . ' : ' . $row->ref ?></span>
          </a>

          <?php if ( $this->params->get('show_creation_date', 0)): ?>
          <span class="date"><?php echo JHtml::_('date',  $row->created, JText::_('DATE_FORMAT_LC3')) ?></span>
          <?php endif ?>
        </dt>

        <?php if ($imgUrl): ?>
        <dt class="image">
          <a href="<?php echo JRoute::_('index.php?option=com_jea&view=property&id='. $row->slug) ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>"> 
          <img src="<?php echo $imgUrl ?>" alt="<?php echo JText::_('COM_JEA_DETAIL') ?>" /></a>
        </dt>
        <?php else : ?>
        <dt class="image">
          <a href="<?php echo JRoute::_('index.php?option=com_jea&view=property&id='. $row->slug) ?>" title="<?php echo JText::_('COM_JEA_DETAIL') ?>"> 
          <img src="<?php echo JURI::root(). 'images/com_jea/images/Image_manquante.png' ?>" alt="<?php echo JText::_('COM_JEA_DETAIL') ?>" /></a>
        </dt>
        <?php endif ?>

        <dd>
        <?php if ($row->slogan): ?>
          <span class="slogan"><?php echo $this->escape($row->slogan) ?> </span>
        <?php endif ?>

        <?php echo $row->transaction_type == 'RENTING' ? JText::_('COM_JEA_FIELD_PRICE_RENT_LABEL') :  JText::_('COM_JEA_FIELD_PRICE_LABEL') ?> : 
        <strong>Nous consulter <?php //echo JHtml::_('utility.formatPrice', (float) $row->price , JText::_('COM_JEA_CONSULT_US') ) ?> </strong>
        <?php //if ($row->transaction_type == 'RENTING' && (float)$row->price != 0.0) echo JText::_('COM_JEA_PRICE_PER_FREQUENCY_'. $row->rate_frequency) ?>

        <br /><br />

        <div>
          <i class="icon-envelope"></i>
          <?php echo $row->address. ' - ' . $row->zip_code . ' - '.$row->town; ?>          
        </div>

        <?php if (!empty($row->living_space)): ?>
          <br /><?php echo  JText::_('COM_JEA_FIELD_LIVING_SPACE_LABEL') ?> : <strong>
          <?php echo JHtml::_('utility.formatSurface', (float) $row->living_space , '-' ) ?>
          </strong>
        <?php endif ?>

        <?php if (!empty($row->land_space)): ?>
          <br /><?php echo  JText::_('COM_JEA_FIELD_LAND_SPACE_LABEL') ?> : <strong>
          <?php echo JHtml::_('utility.formatSurface', (float) $row->land_space , '-' ) ?>
          </strong>
        <?php endif ?>

          <?php if (!empty($row->amenities)) : ?>
            <br /> <strong><?php echo JText::_('COM_JEA_AMENITIES') ?> : </strong>
            <?php echo JHtml::_('amenities.bindList', $row->amenities) ?>
          <?php endif ?>

          <br />
          <button class="btn btn-info btn-button-modal-localize" type="button">
          Localiser
            <input class="map-open-modal-sitraid" type="hidden" value="<?php echo $row->id ?>">
          </button>
          <a href="<?php echo JRoute::_('index.php?option=com_jea&view=property&id='. $row->slug) ?>" class="btn" title="<?php echo JText::_('COM_JEA_DETAIL') ?>">
            <?php echo JText::_('COM_JEA_DETAIL') ?>
          </a>
        </dd>
      </dl>
      <!--
      On place ici l'html de la fenête modale pour qu'elle soit chargée quand on clique sur le bouton localiser
      -->
      <div id="modal_localize-<?php echo $row->id ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $row->id ?>" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="myModalLabel-<?php echo $row->id ?>">Carte de localisation: <?php echo $this->escape($row->title); ?></h3>
        </div>
        <div class="modal-body">
          <div id="map-<?php echo $row->id ?>" style="width: 100%; height: 400px; position: relative; background-color: rgb(229, 227, 223); overflow: hidden;"></div>
        </div>
        <input type="hidden" class="map-modal-latitude" value="<?php echo $row->latitude ?>" />
        <input type="hidden" class="map-modal-longitude" value="<?php echo $row->longitude ?>" />
        <input type="hidden" class="map-modal-titre" value="<?php echo $row->title ?>" />
        <?php if (!empty($imgUrl)): ?>
        <input type="hidden" class="map-modal-imgurl" value="<?php echo $this->getFirstImageUrl($row) ?>" />
        <?php else : ?>
          <input type="hidden" class="map-modal-imgurl" value="<?php echo JURI::root(). 'images/com_jea/images/Image_manquante.png' ?>" />
        <?php endif ?>
        <input type="hidden" class="map-modal-adresse" value="<?php echo $row->address. ' - ' . $row->zip_code . ' - '.$row->town ?>" />
        <input type="hidden" class="map-modal-tel" value="<?php //echo $article->tel ?>" />
        <input type="hidden" class="map-modal-mail" value="<?php //echo $article->mail ?>" />              
      </div>
      <!-- Fin de la fenêtre modale -->
      <?php endforeach ?>
    </div>

    <div>
      <input type="hidden" id="filter_order" name="filter_order" value="<?php echo $listOrder ?>" />
      <input type="hidden" id="filter_order_Dir" name="filter_order_Dir" value="<?php echo $listDirection ?>" /> 
    </div>

    <div class="pagination">      
      <?php echo $this->pagination->getPagesLinks() ?>
    </div>
  </form>
  
<?php else : ?>

  <?php if ($this->state->get('searchcontext') === true): ?>
  <hr />
  <h2><?php echo JText::_('COM_JEA_SEARCH_NO_MATCH_FOUND') ?></h2>

  <p>
    <a href="<?php echo JRoute::_('index.php?option=com_jea&view=properties&layout=search') ?>">
    <?php echo JText::_('COM_JEA_MODIFY_SEARCH')?>
    </a>
  </p>

<?php endif ?>
  
<?php endif ?>

</div>
