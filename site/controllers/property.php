<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: property.php 348 2012-04-27 16:18:25Z ilhooq $
 * @package     Joomla.Site
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controllerform');

/**
 * Property controller class.
 * @package     Joomla.Site
 * @subpackage  com_jea
 */
class JeaControllerProperty extends JControllerForm
{
    /**
     * The URL view item variable.
     *
     * @var    string
     */
    protected $view_item = 'form';

    /**
     * The URL view list variable.
     *
     * @var    string
     */
    protected $view_list = 'properties';

    /* (non-PHPdoc)
     * @see JControllerForm::allowAdd()
     */
    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();
        if (!$user->authorise('core.create', 'com_jea')) {
            $app = JFactory::getApplication();
            $uri = JFactory::getURI();
            $return = base64_encode($uri);
            if ($user->get('id')) {
                $this->setMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
            } else {
                $this->setMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'));
            }
            // Save the data in the session.
            $app->setUserState('com_jea.edit.property.data', $data);
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return='. $return, false));
            return $this->redirect();
        }
        return true;
    }


    /* (non-PHPdoc)
     * @see JControllerForm::allowEdit()
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user     = JFactory::getUser();
        $userId   = $user->get('id');
        $asset    = 'com_jea.property.'.$recordId;

        // Check general edit permission first.
        if ($user->authorise('core.edit', $asset)) {
            return true;
        }

        // Fallback on edit.own.
        // First test if the permission is available.
        if ($user->authorise('core.edit.own', $asset)) {
            // Now test the owner is the user.
            $ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
            if (empty($ownerId) && $recordId) {
                // Need to do a lookup from the model.
                $record= $this->getModel()->getItem($recordId);
                if (empty($record)) {
                    return false;
                }
                $ownerId = $record->created_by;
            }

            // If the owner matches 'me' then do the test.
            if ($ownerId == $userId) {
                return true;
            }
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

    public function unpublish()
    {
        $this->publish(0);
    }

    public function publish($action=1)
    {
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        $this->getModel()->publish($id, $action);
        $this->setRedirect(
        JRoute::_('index.php?option=com_jea&view=properties'
        . $this->getRedirectToListAppend(), false)
        );
    }

    public function delete()
    {
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        if ($this->getModel()->delete($id)) {
            $this->setMessage(JText::_('COM_JEA_SUCCESSFULLY_REMOVED_PROPERTY'));
        }
        $this->setRedirect(
        JRoute::_('index.php?option=com_jea&view=properties'
        . $this->getRedirectToListAppend(), false)
        );
    }

    public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    /**
     * Envoi d'un mail au propriétaire du bien
     */         
     public function sendMailOwner()
     {
      $app = JFactory::getApplication();
      $returnurl = base64_decode($app->input->get('propertyURL', null, 'string'));
      
      if (!JSession::checkToken()) {
        return $this->setRedirect($returnurl);
      }
      
      $nom = $app->input->get('nom', null, 'string');
      $prenom = $app->input->get('prenom', null, 'string');
      $telephone = $app->input->get('telephone', null, 'string');
      $email = $app->input->get('email', null, 'string');
      $message = $app->input->get('message', null, 'string');
      $id_annonce = $app->input->get('id_annonce', null, 'int');
                  
      if ( empty($nom) )
      {
        $app->enqueueMessage('Vous devez entrer votre nom', 'error');
        return $this->setRedirect($returnurl);
      }
      else if ( empty($prenom) )
      {
        $app->enqueueMessage('Vous devez entrer votre prénom', 'error');
        return $this->setRedirect($returnurl);
      }
      else if ( empty($telephone) )
      {
        $app->enqueueMessage('Vous devez entrer votre numéro de téléphone', 'error');
        return $this->setRedirect($returnurl);
      }
      else if ( empty($email))
      {
        $app->enqueueMessage('Vous devez entrer votre email', 'error');
        return $this->setRedirect($returnurl);
      }
      else if ( empty($message) )
      {
        $app->enqueueMessage('Vous devez entrer un message', 'error');
        return $this->setRedirect($returnurl);
      }
      
      // On charge les détails de l'annonce
      $db = JFactory::getDBO();
      $query = "SELECT p.* FROM #__jea_properties AS p WHERE p.id={$id_annonce}";
      $db->setQuery($query);
      $result = $db->loadObject();
      
      // On récupére les contacts associés à l'annonce
      $query = "SELECT c.* FROM #__jea_contacts_rel AS crel INNER JOIN #__jea_contacts AS c ON c.id=crel.contact_id WHERE crel.property_id={$id_annonce} AND c.type=0";
      $db->setQuery($query);
      $contacts = $db->loadObject();
      
      if(!empty($contacts))
      {
        // Préparation pour l'envoi du mail
        $mailer = JFactory::getMailer();
        
        $config = JFactory::getConfig();
        $sender = array( 
          $config->getValue( 'config.mailfrom' ),
          $config->getValue( 'config.fromname' )
        );
     
        $date = JFactory::getDate();
        
        $slug = $result->alias ? ($result->id . ':' . $result->alias) : $result->id;
        
        $mailer->setSender($sender);
        
        $mailer->addRecipient('florian.dalfitto@gmail.com');
        $mailer->setSubject('Bourse immobilière demande d\'informations sur une annonce');
        $mailer->isHTML(true);
        $mailer->setBody(
          'Vous avez reçu une demande d\information depuis la Bourse immobilière du site de la Communauté de Communes du Pays de Faverges (http://pays-de-faverges.com/).
          La demande d\'informations concerne l\'annonce nommée <b>'.$result->title.'</b> portant la référence <b>'.$result->ref.'</b><br />
          <a href="'.JRoute::_('index.php?option=com_jea&view=property&id='. $slug, false).'" title="Lien vers l\'annonce du bien">Lien vers l\'annonce du bien en question</a><br />
          Détails des informations données par la personne demandeuse : <br />
          <ul>
          <li>Nom : '.$nom.'</li>
          <li>Prénom : '.$prenom.'</li>
          <li>Numéro de téléphone : '.$telephone.'</li>
          <li>Adresse mail : '.$email.'</li>
          <li>Message : '.$message.' </li></ul>');
        
                    
        try
        {
          $mailer->Send();
          $app->enqueueMessage(JText::_('COM_JEA_MORE_INFO_ANNOUNCE_SEND_SUCCESSFULLY'), 'message');
        }
        catch (Exception $e)
        {      
          $app->enqueueMessage($e->getMessage(), 'error'); 
        }
              
        $this->setRedirect($returnurl);
      }
      else
      {
        $app->enqueueMessage('Impossible d\'envoyer le mail au propriétaire du bien', 'error');
        $this->setRedirect($returnurl);
      }       
     }     

    /**
     * Envoi du mail pour plus d'information sur une annonce
     */         
    public function sendMailContact()
    {
      $input = JFactory::getApplication()->input;
      $nom = $input->get('name', null, 'string');
      $email = $input->get('email', null, 'string');
      $telephone = $input->get('telephone', null, 'string');
      $sujet = $input->get('subject', null, 'string');
      $message = $input->get('message', null, 'string');
      
      $returnurl = base64_decode($input->get('propertyURL', null, 'string'));
                  
      if (!JSession::checkToken()) {
        return $this->setRedirect($returnurl);
      }
      
      // L'utilisateur doit compléter son nom, email, sujet et message sinon on le renvoie à la page précédente
      if ( empty($nom) && empty($email) && empty($telephone) && empty($sujet) && empty($message) ) {
        JFactory::getApplication()->enqueueMessage('Vous devez compléter tous les champs pour que le message soit envoyé', 'error');
        
        return $this->setRedirect($returnurl);
      }
      
      // Préparation pour l'envoi du mail
      $mailer = JFactory::getMailer();
      
      $config = JFactory::getConfig();
      $sender = array( 
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' )
      );
   
      $date = JFactory::getDate();
   
      $mailer->setSender($sender);
      
      $mailer->addRecipient('florian.dalfitto@gmail.com');
      $mailer->setSubject('[Gestion immobilière demande informations] '.$sujet);
      $mailer->isHTML(true);
      $mailer->setBody(
        'Detail du message: '.$message.' <br /><br /><b>Annonce 
        déposée le: </b>'.$date->format('d-m-Y').'<br /><br /><b>Coordonnées
         du déposant:</b> <br />'.$nom.' <br />'.$telephone.' <br />'.$email);
          
      try
      {
        $mailer->Send();
        JFactory::getApplication()->enqueueMessage(JText::_('COM_JEA_MORE_INFO_ANNOUNCE_SEND_SUCCESSFULLY'), 'message');
      }
      catch (Exception $e)
      {      
        JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error'); 
      }
            
      $this->setRedirect($returnurl);
    }

    /* (non-PHPdoc)
     * @see JControllerForm::getRedirectToItemAppend()
     */
    protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
    {
        $tmpl = JRequest::getCmd('tmpl');
        $append = '&layout=edit';

        // Setup redirect info.
        if ($tmpl)
        {
            $append .= '&tmpl=' . $tmpl;
        }

        if ($recordId)
        {
            $append .= '&' . $urlVar . '=' . $recordId;
        }

        return $append;
    }

    /* (non-PHPdoc)
     * @see JControllerForm::getRedirectToListAppend()
     */
    protected function getRedirectToListAppend()
    {
        $tmpl = JRequest::getCmd('tmpl');
        $append = '&layout=manage';

        // Try to redirect to the manage menu item if found
        $app  = JFactory::getApplication();
        $menu = $app->getMenu();
        $activeItem = $menu->getActive();

        if (isset($activeItem->query['layout']) && $activeItem->query['layout'] !='manage' ) {
            $items = $menu->getItems('component', 'com_jea');
            foreach ($items as $item) {
                $layout = isset($item->query['layout']) ? $item->query['layout'] : '';
                if ($layout == 'manage') {
                    $append .= '&Itemid=' . $item->id;
                }
            }
        }


        // Setup redirect info.
        if ($tmpl)
        {
            $append .= '&tmpl=' . $tmpl;
        }

        return $append;
    }

}

