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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
if ((float) JVERSION > 3) {
    JHtml::_('formbehavior.chosen', 'select');
}

$rowsCount = count($this->items) ;
$altrow = 1;

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirection = $this->escape($this->state->get('list.direction'));
$saveOrder     = $listOrder == 'p.ordering';

$transactionType = $this->state->get('filter.transaction_type');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jea&view=contacts') ?>" method="post"
      name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
  <?php echo $this->sidebar ?>
  <hr />
  <div class="filter-select hidden-phone">
    <h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL') ?></h4>
    <select name="filter_transaction_type" class="inputbox span12 small" onchange="this.form.submit()">
      <option value=""> - <?php echo JText::_('COM_JEA_FIELD_TRANSACTION_TYPE_LABEL')?> - </option>
      <option value="RENTING"<?php if ($transactionType == 'RENTING') echo ' selected="selected"'?>>
        <?php echo JText::_('COM_JEA_OPTION_RENTING')?>
      </option>
      <option value="SELLING"<?php if ($transactionType == 'SELLING') echo ' selected="selected"'?>>
        <?php echo JText::_('COM_JEA_OPTION_SELLING')?>
      </option>
      <?php // TODO: call plugin entry to add more transaction types  ?>
    </select>
    <hr class="hr-condensed" />
    <?php echo JHtml::_('features.types', $this->state->get('filter.type_id', 0), 'filter_type_id', 'onchange="document.adminForm.submit();"' ) ?>
    <hr class="hr-condensed" />
    <?php echo JHtml::_('features.departments', $this->state->get('filter.department_id', 0), 'filter_department_id', 'onchange="document.adminForm.submit();"' ) ?>
    <hr class="hr-condensed" />
    <?php if ($this->params->get('relationship_dpts_towns_area', 0)): ?>
    <?php echo JHtml::_('features.towns', $this->state->get('filter.town_id'), 'filter_town_id', 'onchange="document.adminForm.submit();"', $this->state->get('filter.department_id', 0) ) ?>
    <?php else: ?>
    <?php echo JHtml::_('features.towns', $this->state->get('filter.town_id'), 'filter_town_id', 'onchange="document.adminForm.submit();"' ) ?>
    <?php endif ?>
    <hr class="hr-condensed" />
    <select name="filter_language" class="inputbox span12 small" onchange="this.form.submit()">
      <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
      <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
    </select>
  </div>
</div>
<?php endif ?>

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
          <?php echo JHtml::_('grid.sort', 'COM_JEA_CONTACTS_FIELD_NAME', 'c.name', $listDirection , $listOrder ) ?>
        </th>
        <th class="nowrap">
          <?php echo JText::_('COM_JEA_FIELD_CONTACTS_FIELD_LASTNAME') ?>
        </th>
        <th class="nowrap">
          <?php echo JText::_('COM_JEA_FIELD_CONTACTS_FIELD_ADRESS') ?>
        </th>
        <th width="27%" class="nowrap">
          <?php echo JText::_('COM_JEA_FIELD_CONTACTS_CODE_POSTAL') ?>
        </th>
        <th width="10%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JEA_FIELD_CONTACTS_CITY', 'c.ville', $listDirection , $listOrder) ?>
        </th>
        <th width="10%" class="nowrap">
          <?php echo JText::_('COM_JEA_FIELD_CONTACTS_PHONE') ?>
        </th>
        <th width="10%" class="nowrap">
          <?php echo JText::_('COM_JEA_FIELD_MAIL') ?>
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

    <?php
    $altrow = ( $altrow == 1 )? 0 : 1;    
    ?>

      <tr class="row<?php echo $altrow ?>">
        <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
        <td><a href="<?php echo JRoute::_('index.php?option=com_jea&task=contact.edit&id='.(int) $item->id); ?>">
          <?php echo $item->contactname; ?> </a> </td>
        <td><?php echo $item->lastname; ?></td>
        <td><?php echo $item->adress; ?></td>
        <td><?php echo $item->code_postal; ?></td>
        <td><?php echo $item->ville; ?></td>
        <td><?php echo $item->telephone; ?></td>
        <td><?php echo $item->mail; ?></td>        
      </tr>
      <?php endforeach ?>
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
