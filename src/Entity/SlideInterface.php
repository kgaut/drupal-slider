<?php

namespace Drupal\slider\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Slide entities.
 *
 * @ingroup slider
 */
interface SlideInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Slide name.
   *
   * @return string
   *   Name of the Slide.
   */
  public function getName();

  /**
   * Sets the Slide name.
   *
   * @param string $name
   *   The Slide name.
   *
   * @return \Drupal\slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setName($name);

  /**
   * Gets the Slide creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Slide.
   */
  public function getCreatedTime();

  /**
   * Sets the Slide creation timestamp.
   *
   * @param int $timestamp
   *   The Slide creation timestamp.
   *
   * @return \Drupal\slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setCreatedTime($timestamp);

}
