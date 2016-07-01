<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushMaintenanceModeOff extends DrushBaseTask {

  protected $name = 'Drush Turn Off Maintenance Mode';
  protected $description = 'Take Drupal out of maintenance mode.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Taking Drupal out of maintenance mode.');

    return array(
      $this->drush->run('setMaintenanceMode', '0'),
    );
  }
}
