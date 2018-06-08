<?php

namespace Drupal\archive\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config;
// use \Drupal\Core\Entity\Query\QueryInterface
/**
 * Class ArchiveController.
 */
class ArchiveController extends ControllerBase {

  /**
   * Calender.
   *
   * @return string
   *   Return Hello string.
   */
  public function calender() {
    return [
      '#theme' => 'calender',
      '#calender' =>  $this->calender_tables(),
    ];
  }

 /**
  * Calender tables.
  */
 public function calender_tables() {
    $dateComponents = getdate();
    $month = $dateComponents['mon'];            
    $year = $dateComponents['year'];

     // Create array containing abbreviations of days of week.
     $days_of_week = array('S','M','T','W','T','F','S');
     // What is the first day of the month in question?
     $first_day_of_month =  mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?
     $number_days = date('t',$first_day_of_month);
     // Retrieve some information about the first day of the
     // month in question.
     $date_components = getdate($first_day_of_month);
     // What is the name of the month in question?
     $month_name = $date_components['month'];
     // What is the index value (0-6) of the first day of the
     // month in question.
     $day_of_week = $date_components['wday'];

    $calender = [
      'days_of_week' =>$days_of_week,
      'first_day_of_month' =>date('D',$first_day_of_month),
      'number_days' =>$number_days,
      'date_components' =>$date_components,
      'month_name' =>$month_name,
      'day_of_week' =>$day_of_week, 
      'year' =>$year, 
      'current_date' => (int) date('d'), 
      'url'=>'/archive/'.(int) strtotime(date('Y-m-d'))
    ];
    // ksm($date_components);
    return $calender;
  }

 /**
  * archive data.
  */
  public function archive($date) {

    return [
      '#theme' => 'archive_page',
      '#archive_page' => $this->archive_date($date),
    ];
  }

  public function archive_date($date) {
    

    $archive_content = $this->get_archive_content_type();
    $current_date = date("Y-m-d");

    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', $archive_content, "IN")
        // ->condition('created', $current_date, ">")
        ->range(0, 100);
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $node_storage->loadMultiple($nids);
    $date_format = 'Y-m-d'; 

    // kint($current_date);

    
    $archive_data = array();
    foreach ($nodes as $key => $value) {
      // kint(strtotime(date($date_format, $value->getCreatedTime())), strtotime($current_date));

      if(strtotime(date($date_format, $value->getCreatedTime()))==strtotime($current_date)) { 
          $archive_data[$key]['nid'] = $key;
          $archive_data[$key]['dtime'] = date($date_format, $value->getCreatedTime());
          $archive_data[$key]['title'] = $value->getTitle();
          $archive_data[$key]['type'] = $value->getType();
          $archive_data[$key]['body'] = $value->get('body')->getValue()[0]['value'];
        }
    }
    $output['archive_data'] = $archive_data;
    return $output;
  }

/*
 *
 *
 */
public function archiveType($type) {

}

  /*
   * Implementation : Get archive content type.
   *
   */
  private function get_archive_content_type() {
    $archive_content = \Drupal::config('archive.userinterface')->get();
    $archive_content_type = $archive_content['archive_type'];

    $content_type = array();
    foreach ($archive_content_type as $key => $value) {
      if($key === $value) {
        $content_type[] = (string) $value;
      }
    }
    return $content_type;
  }
}
