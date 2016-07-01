<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushMaintenanceModeOn extends DrushBaseTask {

  protected $name = 'Drush Turn On Maintenance Mode';
  protected $description = 'Put Drupal into maintenance mode.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Putting Drupal into maintenance mode.');

    return array(
      $this->drush->run('setMaintenanceMode', '1'),
    );
  }
}
