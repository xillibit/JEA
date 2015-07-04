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

jimport( 'joomla.html.html.tabs' );
JHtml::stylesheet('media/com_jea/css/jea.admin.css');

?>

<?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
      <?php echo $this->sidebar; ?>
    </div>
<?php endif ?>

<div id="j-main-container" class="span10">
<?php echo JHtml::_('tabs.start', 'jea_info') ?>

<?php echo JHtml::_('tabs.panel', JText::_( 'COM_JEA_ABOUT' ), 'pan1') ?>

<div id="pan1">
  <div style="height: 300px; float: left; margin-right: 50px;">
    <img src="../media/com_jea/images/logo.png" alt="logo.png" />
  </div>

  <div style="height: 300px">
    <p><strong>Joomla Estate Agency <?php echo $this->getVersion() ?> </strong></p>

    <p>
      <a href="https://github.com/xillibit/JEA" target="_blank"><?php echo JText::_('COM_JEA_PROJECT_HOME') ?></a>
    </p>

    <p>
      <a href="http://joomlacode.org/gf/project/jea/forum/" target="_blank"><?php echo JText::_('COM_JEA_FORUM') ?></a>
    </p>

    <p>
      <a href="http://joomlacode.org/gf/project/jea/wiki/" target="_blank"><?php echo JText::_('COM_JEA_DOCUMENTATION') ?></a>
    </p>

    <p>
      <?php echo JText::_('COM_JEA_MAIN_DEVELOPER') ?> : <a href="http://www.sphilip.com" target="_blank">Sylvain Philip</a><br />
      <?php echo JText::_('COM_JEA_CREDITS') ?> : <a href="https://twitter.com/#!/phproberto" target="_blank">Roberto Segura</a>
    </p>

    <p>
      <?php echo JText::_('COM_JEA_LOGO') ?> : <a href="http://www.lievregraphiste.com/" target="_blank">Elisa Roche</a>
    </p>
  </div>
</div>

<?php echo JHtml::_('tabs.panel', JText::_('COM_JEA_LICENCE'), 'pan2') ?>
<div id="pan2">
  <div class="file_reader">
    <pre>
    <?php //require JPATH_COMPONENT . '/LICENCE.txt' ?>
    </pre>
  </div>
</div>

<?php echo JHtml::_('tabs.panel', JText::_('COM_JEA_VERSIONS'), 'pan3') ?>
<div id="pan3">
  <div class="file_reader">
    <pre>
    <?php //require JPATH_COMPONENT . '/NEWS.txt' ?>
    </pre>
  </div>
</div>

<?php echo JHtml::_('tabs.end') ?>
</div>

