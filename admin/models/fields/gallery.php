<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     $Id: gallery.php 388 2013-01-15 17:25:38Z ilhooq $
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @copyright   Copyright (C) 2008 - 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.image');


/**
 * Form Field class for JEA.
 * Provides a complete widget to manage a gallery
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 * @see         JFormField
 */
class JFormFieldGallery extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     *
     * @since  11.1
     */
    protected $type = 'Gallery';


    /**
     * Method to get the list of input[type="file"]
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $output = '';

        $params = JComponentHelper::getParams('com_jea');
        $imgUploadNumber = $params->get('img_upload_number', 3);

        for ($i=0; $i < $imgUploadNumber; $i++) {
            $output .= '<input type="file" name="newimages[]" value=""  size="30" class="fltnone" /> <br />';
        }

        $output .= "\n";

        //alert & return if GD library for PHP is not enabled
        if (!extension_loaded('gd')) {
            $output .= '<strong>WARNING: </strong>The <a href="http://php.net/manual/en/book.image.php" target="_blank">GD library for PHP</a> was not found. Ensure to install it';
            return $output;
        }

        if (is_string($this->value)) {
            $images = (array) json_decode($this->value);
        } else {
            $images = (array) $this->value;
            foreach ($images as $k => $image) {
                $images[$k] = (object) $image;
            }
        }

        $propertyId  = $this->form->getValue('id');

        $baseURL = JURI::root(true);
        $imgBaseURL  = $baseURL.'/images/com_jea/images/' . $propertyId;
        $imgBasePath = JPATH_ROOT . '/images/com_jea/images/' . $propertyId;

        if (!empty($images)) {
            $output .= "<ul class=\"gallery\">\n";
            foreach ($images as $k => $image) {
                $imgPath = $imgBasePath . '/' . $image->name;
                try {
                    $infos = JImage::getImageFileProperties($imgPath);
                } catch (Exception $e) {
                    $output .= "<li>Recorded Image ".$image->name." cannot be accessed</li>\n";
                    continue;
                }

                $thumbName = 'thumb-admin-'. $image->name;
                // Create the thumbnail
                if (!file_exists($imgBasePath . '/' . $thumbName)) {
                  try {
                    // This is where the JImage will be used, so only create it here
                    $JImage = new JImage($imgPath);
                    $thumb = $JImage->resize(150, 90);
                    $thumb->crop(150, 90, 0, 0);
                    $thumb->toFile($imgBasePath . '/' . $thumbName);
                    // To avoid memory overconsumption, destroy the JImage now that we don't need it anymore
                    if (method_exists( $JImage , 'destroy')) {
                       $JImage->destroy();
                       $thumb->destroy();
                    } else {
                       // There is no destroy method on Jplatform < 12.3 (Joomla 2.5) and the handle property is protected.
                       // We have to hack the JImage class to destroy the image resource
                       $prop = new ReflectionProperty('JImage', 'handle');
                       $prop->setAccessible(true);
                       $JImageHandle = $prop->getValue($JImage);
                       $thumbHandle = $prop->getValue($thumb);
                       if (is_resource($JImageHandle) ) {
                        imagedestroy($JImageHandle);
                       }
                       if (is_resource($thumbHandle) ) {
                         imagedestroy($thumbHandle);
                       }
                    }
                  } catch (Exception $e) {
                     $output .= "<li>Thumbnail for ".$image->name." cannot be generated</li>\n";
                     continue;
                  }
                }
                $thumbUrl = $imgBaseURL .'/'. $thumbName;
                $url    = $imgBaseURL .'/'. $image->name;
                $weight = round($infos->bits/1024,1); // Ko

                $output .= "<li class=\"item-$k\">\n"
                . "<a href=\"{$url}\" title=\"Zoom\" class=\"imgLink modal\" rel=\"{handler: 'image'}\"><img src=\"{$thumbUrl}\" alt=\"{$image->name}\" /></a>\n"
                . "<div class=\"imgInfos\">\n"
                . $image->name . "<br />\n"
                . JText::_('COM_JEA_WIDTH') . ' : ' . $infos->width . ' px' . "<br />\n"
                . JText::_('COM_JEA_HEIGHT') . ' : ' . $infos->height . ' px' . "<br />\n"
                . "</div>\n"
                . "<div class=\"imgTools\">\n"
                . '  <a class="img-move-up" title="'.JText::_('JLIB_HTML_MOVE_UP').'"><img src="'. $baseURL . '/media/com_jea/images/sort_asc.png' .'" alt="Move up" /></a>'
                . '  <a class="img-move-down" title="'.JText::_('JLIB_HTML_MOVE_DOWN').'"><img src="'. $baseURL . '/media/com_jea/images/sort_desc.png' .'" alt="Move down" /></a>'
                . '  <a class="delete-img" title="'.JText::_('JACTION_DELETE').'"><img src="'. $baseURL . '/media/com_jea/images/media_trash.png' .'" alt="Delete" /></a>'
                . "</div>\n"
                . "<div class=\"clr\"></div>\n"
                . '<label for="'. $this->id.$k .'title">'.JText::_('JGLOBAL_TITLE').'</label><input id="'. $this->id.$k .'title" type="text" name="'.$this->name.'['.$k .'][title]" value="'.$image->title.'" size="20"/><br />'
                . '<label for="'. $this->id.$k .'desc">'.JText::_('JGLOBAL_DESCRIPTION').'</label><input id="'. $this->id.$k .'desc" type="text" name="'. $this->name.'['.$k .'][description]" value="'.$image->description.'" size="40"/>'
                . '<input type="hidden" name="'. $this->name.'['.$k .'][name]" value="'.$image->name.'" />'
                . "<div class=\"clr\"></div>\n"
                . "</li>\n";
            }
            $output .= "</ul>\n";

            // Add javascript behavior
            JHtml::_('behavior.modal');

            JFactory::getDocument()->addScriptDeclaration("
                window.addEvent('domready', function() {
                    var sortOptions = {
                        transition: Fx.Transitions.Back.easeInOut,
                        duration: 700,
                        mode: 'vertical',
                        onComplete: function() {
                           mySort.rearrangeDOM()
                        }
                    };

                    var mySort = new Fx.Sort($$('ul.gallery li'), sortOptions);

                    $$('a.delete-img').each(function(item) {
                        item.addEvent('click', function() {
                            this.getParent('li').destroy();
                            mySort = new Fx.Sort($$('ul.gallery li'), sortOptions);
                        });
                    });

                    $$('a.img-move-up').each(function(item) {
                        item.addEvent('click', function() {
                            var activeLi = this.getParent('li');
                            if (activeLi.getPrevious()) {
                                mySort.swap(activeLi, activeLi.getPrevious());
                            } else if (this.getParent('ul').getChildren().length > 1 ) {
                                // Swap with the last element
                            	mySort.swap(activeLi, this.getParent('ul').getLast('li'));
                            }
                        });
                    });

                     $$('a.img-move-down').each(function(item) {
                        item.addEvent('click', function() {
                            var activeLi = this.getParent('li');
                            if (activeLi.getNext()) {
                                mySort.swap(activeLi, activeLi.getNext());
                            } else if (this.getParent('ul').getChildren().length > 1 ) {
                                // Swap with the first element
                            	mySort.swap(activeLi, this.getParent('ul').getFirst('li'));
                            }
                        });
                    });

                })"
            );
        }

        return $output;
    }


}
