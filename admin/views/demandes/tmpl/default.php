<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: default.php 444 2013-10-11 11:57:34Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::stylesheet('media/com_jea/css/jea.admin.css');

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirection = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_jea&view=demandes') ?>" method="post"
      name="adminForm" id="adminForm">
  <div id="j-main-container" class="span10">
    <?php if ((float) JVERSION > 3): ?>
      <div id="filter-bar" class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
          <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL') ?></label>
          <input type="text" name="filter_search"
            placeholder="<?php echo JText::_('COM_JEA_PROPERTIES_SEARCH_FILTER_DESC'); ?>"
            id="filter_search"
            value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
            title="<?php echo JText::_('COM_JEA_PROPERTIES_SEARCH_FILTER_DESC'); ?>" />
        </div>
        <div class="btn-group pull-left hidden-phone">
          <button class="btn tip hasTooltip" type="submit"
            title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
            <i class="icon-search"></i>
          </button>
          <button class="btn tip hasTooltip" type="button"
            onclick="document.id('filter_search').value='';this.form.submit();"
            title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
            <i class="icon-remove"></i>
          </button>
        </div>
        <div class="btn-group pull-right hidden-phone">
          <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ?></label>
          <?php echo $this->pagination->getLimitBox() ?>
        </div>
      </div>
  
      <?php endif ?>
    <table class="adminlist table table-striped">
      <thead>
        <tr>
          <th width="1%" class="nowrap">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
          </th>
          <th width="10%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_NOM', 'p.ref', $listDirection , $listOrder ) ?>
          </th>
          <th class="nowrap">
            <?php echo JText::_('COM_JEA_DEMANDES_PRENOM') ?>
          </th>
          <th width="27%" class="nowrap">
            <?php echo JText::_('COM_JEA_DEMANDES_ADRESSE') ?>
          </th>
          <th width="10%" class="nowrap">
            <?php echo JText::_('COM_JEA_DEMANDES_MAIL') ?>
          </th>
          <th width="10%" class="nowrap">
            <?php echo JText::_('COM_JEA_DEMANDES_ACTIVITE') ?>
          </th>
          <th width="10%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_DESCRIPTION', 'p.price', $listDirection , $listOrder ) ?>
          </th>
          <th width="1%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_LIEU_RECHERCHE', 'p.featured', $listDirection , $listOrder ) ?>
          </th>
          <th width="1%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_BUDGET', 'p.published', $listDirection , $listOrder ) ?>
          </th>
          <th width="1%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_NB_BIENS_EN_RELATION', 'p.published', $listDirection , $listOrder ) ?>
          </th>
          <th width="5%" class="nowrap">
            <?php echo JHtml::_('grid.sort', 'COM_JEA_DEMANDES_ETAT', 'access_level', $listDirection, $listOrder); ?>
          </th>
          <th width="5%" class="nowrap">
            <?php echo JHTML::_('grid.sort', 'COM_JEA_DEMANDES_DATE_REALISE', 'p.ordering', $listDirection , $listOrder ) ?>          
          </th> 
          <th width="5%" class="nowrap">
            <?php echo JHTML::_('grid.sort', 'COM_JEA_DEMANDES_CONFIDENTIALITE', 'p.ordering', $listDirection , $listOrder ) ?>          
          </th>        
        </tr>
      </thead>
  
      <tfoot>
        <tr>
          <td colspan="16"><?php echo $this->pagination->getListFooter() ?></td>
        </tr>
      </tfoot>
  
      <tbody>
      <?php foreach ($this->items as $i => $item) : ?>
  
        <tr class="row<?php echo $altrow ?>">
          <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
          <td class="center">
            <a href="<?php echo JRoute::_('index.php?option=com_jea&task=demande.edit&id='.(int) $item->id); ?>">
            <?php echo $item->contactname; ?> </a>
          </td>
  
          <td class="center"><?php echo $this->escape( $item->lastname ) ?></td>
          <td class="center"><?php echo $this->escape( $item->adress ) ?></td>
          <td class="center"><?php echo $this->escape( $item->mail) ?></td>
          <td class="left nowrap"><?php echo $this->escape( $item->activite ) ?></td>
          <td class="right">
            <?php echo $this->escape( $item->description ) ?>
          </td>
          <td class="center">
            <?php echo $this->escape( $item->lieu_recherche ) ?>
          </td>
          <td class="center">
            <?php echo $item->budget ?>
          </td>
          <td class="center">
            <?php echo $this->nbbiensenrelations[$item->id]; ?>  
          </td>
          <td class="center">
            <?php 
            if($item->etat==0)
            {
              echo JText::_('COM_JEA_SELECT_DEMANDE_ETAT_INDEFINI');
            }
            elseif($item->etat==1)
            {
              echo '<img src="'. JUri::root() .'/administrator/components/com_jea/assets/images/icon-16-update.png" title="' .JText::_('COM_JEA_SELECT_DEMANDE_ETAT_ATTENTE').'" />';
            }
            elseif($item->etat==2)
            {
              echo '<img src="'. JUri::root() .'/administrator/components/com_jea/assets/images/icon-16-order.png" title="' .JText::_('COM_JEA_SELECT_DEMANDE_ETAT_REALISE').'" />'; 
            }
            ?>
          </td>
          <td class="order">
            <?php echo $this->escape($item->date_realise); ?>          
          </td>
          <td class="center">
            <?php if($item->confidentielle)
            { 
              echo '<img src="'. JUri::root() .'/administrator/templates/bluestork/images/admin/checked_out.png" title="' .JText::_('COM_JEA_SELECT_DEMANDE_CONFIDENTIEL').'" />';
            }
            else
            { 
              echo '<img src="'. JUri::root() .'/administrator/templates/bluestork/images/admin/icon-16-allow.png" title="' .JText::_('COM_JEA_SELECT_DEMANDE_NON_CONFIDENTIEL').'" />';
            } ?>
          </td>          
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  
    <div>
      <input type="hidden" name="task" value="" /> 
      <input type="hidden" name="boxchecked" value="0" />
      <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" /> 
      <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirection ?>" />
      <?php echo JHtml::_('form.token') ?>
    </div>
  </div>
</form>