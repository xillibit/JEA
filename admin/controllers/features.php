<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: features.php 428 2013-08-25 15:26:30Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.archive');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/upload.php';


/**
 * Features controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class JeaControllerFeatures extends JControllerLegacy
{


    /**
     * Method to export features tables as CSV
     */
    public function export()
    {
        $features = JRequest::getVar( 'cid', array(), 'post', 'array' );

        if (!empty($features)) {
            $config   = JFactory::getConfig();
            $exportPath = $config->get('tmp_path') . '/jea_export';

            if (JFolder::create($exportPath) === false) {
                $msg= JText::_('JLIB_FILESYSTEM_ERROR_FOLDER_CREATE').' : '.$exportPath;
                $this->setRedirect('index.php?option=com_jea&view=features', $msg, 'warning');
            } else {

                $xmlPath = JPATH_COMPONENT.'/models/forms/features/';
                $xmlFiles = JFolder::files($xmlPath);
                $model = $this->getModel();
                $files = array();

                foreach ($xmlFiles as $filename) {
                    if (preg_match('/^[0-9]{2}-([a-z]*).xml/', $filename, $matches)) {
                        $feature = $matches[1];
                        if (in_array($feature, $features)) {
                            $form = simplexml_load_file($xmlPath. '/' .$filename);
                            $table = (string) $form['table'];
                            $files[] = array(
                                'data' => $model->getCSVData($table),
                                'name' => $table.'.csv'
                            );
                        }
                    }
                }

                $zipFile = $exportPath . '/jea_export_'.uniqid().'.zip';
                $zip = JArchive::getAdapter('zip');
                $zip->create($zipFile, $files);

                JResponse::setHeader('Content-Type', 'application/zip');
                JResponse::setHeader('Content-Disposition', 'attachment; filename="jea_features.zip"');
                JResponse::setHeader('Content-Transfer-Encoding', 'binary');
                JResponse::setBody(readfile($zipFile));
                echo JResponse::toString();

                // clean tmp files
                JFile::delete($zipFile);
                JFolder::delete($exportPath);

                Jexit();
            }

        } else {
            $msg= JText::_('JERROR_NO_ITEMS_SELECTED');
            $this->setRedirect('index.php?option=com_jea&view=features', $msg);
        }
    }


    /**
     * Method to import data in features tables
     */
    public function import()
    {
        $application = JFactory::getApplication();
        $upload = JeaUpload::getUpload('csv');
        $validExtensions = array('csv','CSV','txt','TXT') ;

        $xmlPath = JPATH_COMPONENT.'/models/forms/features/';
        $xmlFiles = JFolder::files($xmlPath);
        $model = $this->getModel();
        $tables = array();

        // Retrieve the table names
        foreach ($xmlFiles as $filename) {
            if (preg_match('/^[0-9]{2}-([a-z]*).xml/', $filename, $matches)) {
                $feature = $matches[1];
                if (!isset($tables[$feature])) {
                    $form = simplexml_load_file($xmlPath . '/' . $filename);
                    $tables[$feature]= (string) $form['table'];
                }
            }
        }

        foreach ($upload as $file) {
            if ($file->isPosted() && isset($tables[$file->key])) {
                $file->setValidExtensions($validExtensions);
                $fileErrors = $file->getErrors();
                if (!$fileErrors) {
                    $rows = $model->importFromCSV($file->temp_name, $tables[$file->key]);
                    $msg = JText::sprintf('COM_JEA_NUM_LINES_IMPORTED_ON_TABLE', $rows, $tables[$file->key]);
                    $application->enqueueMessage($msg);
                    $errors = $model->getErrors();
                    if ($errors) {
                        foreach ($errors as $error) {
                            $application->enqueueMessage($error, 'warning');
                        }
                    }
                } else {
                    foreach ($fileErrors as $error) {
                        $application->enqueueMessage($error, 'warning');
                    }
                }
            }
        }

        $this->setRedirect('index.php?option=com_jea&view=features');
    }


    /* (non-PHPdoc)
     * @see JController::getModel()
     */
    public function getModel($name = 'Features', $prefix = 'JeaModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

}
