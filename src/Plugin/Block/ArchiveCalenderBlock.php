<?php

namespace Drupal\archive\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ArchiveCalenderBlock' block.
 *
 * @Block(
 *  id = "archive_calender_block",
 *  admin_label = @Translation("Archive calender block"),
 * )
 */
class ArchiveCalenderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['archive_calender_block']['#markup'] = calender();

    return $build;
  }
}
