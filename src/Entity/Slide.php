<?php

namespace Drupal\slider\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\link\LinkItemInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Slide entity.
 *
 * @ingroup slider
 *
 * @ContentEntityType(
 *   id = "slide",
 *   label = @Translation("Slide"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\slider\Entity\ListBuilder\SlideListBuilder",
 *     "views_data" = "Drupal\slider\Entity\ViewsData\SlideViewsData",
 *     "translation" = "Drupal\slider\Entity\TranslationHandler\SlideTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\slider\Entity\Form\SlideForm",
 *       "add" = "Drupal\slider\Entity\Form\SlideForm",
 *       "edit" = "Drupal\slider\Entity\Form\SlideForm",
 *       "delete" = "Drupal\slider\Entity\Form\SlideDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\slider\Entity\HtmlRouteProvider\SlideHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\slider\Entity\AccessControlHandler\SlideAccessControlHandler",
 *   },
 *   base_table = "slide",
 *   data_table = "slide_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer slide entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "admin_title",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "add-form" = "/admin/content/slides/add",
 *     "edit-form" = "/admin/content/slides/{slide}/edit",
 *     "delete-form" = "/admin/content/slides/{slide}/delete",
 *   },
 *   field_ui_base_route = "slide.settings"
 * )
 */
class Slide extends ContentEntityBase implements SlideInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('admin_title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('admin_title', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Slide entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['admin_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Titre d'administration"))
      ->setDescription(t('Ne sera pas affichée aux visiteurs.'))
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Titre'))
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 1)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['baseline'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Phrase d'accroche"))
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 1)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image de fond'))
      ->setSetting('file_directory', 'slider')
      ->setSetting('file_extensions', 'png jpg jpeg')
      ->setSetting('title_field', FALSE)
      ->setSetting('title_field_required', FALSE)
      ->setSetting('alt_field', FALSE)
      ->setSetting('alt_field_required', FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['cta'] = BaseFieldDefinition::create('link')
      ->setLabel(t('Lien'))
      ->setDescription(t('Le titre sera le label du bouton'))
      ->setSetting('link_type', LinkItemInterface::LINK_GENERIC)
      ->setSetting('title', DRUPAL_REQUIRED)
      ->setRequired(FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['status']->setDescription(t('La diapo est-elle visible ?'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
