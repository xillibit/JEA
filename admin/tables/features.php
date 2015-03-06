<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: features.php 262 2012-02-26 19:02:48Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * FeaturesFactory table class.
 * This class provides a way to instantiate a feature table on the fly
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class FeaturesFactory extends JTable
{
    /**
     * Object constructor
     * 
     * @param string $featuresTable The feature table name
     * @param string $pk The primary key of the table
     * @param JDatabase &$db JDatabase connector object.
     */
    public function __construct($featuresTable, $pk='id', &$db) {
         
        parent::__construct( $featuresTable, $pk, $db );
    }

    /* (non-PHPdoc)
     * @see JTable::getFields()
     */
    public function getFields()
    {
        $fields = $this->_db->getTableColumns($this->_tbl, false);
        if (empty($fields))
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_COLUMNS_NOT_FOUND'));
            return false;
        }

        return $fields;
    }

    /* (non-PHPdoc)
     * @see JTable::check()
     */
    public function check()
    {
        //For new insertion
        if (empty($this->id)) {
            $this->ordering = $this->getNextOrder();
        }
        
        return true;
    }
}