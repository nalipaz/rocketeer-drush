<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushUpdatedb extends DrushBaseTask {

  protected $name = 'drushupdatedb';
  protected $description = 'Run pending Drupal database updates.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Running pending Drupal database updates.');

    return array(
      $this->drush->run('updatedb'),
    );
  }
}
