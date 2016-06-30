<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushSqlDump extends DrushBaseTask {

  protected $name = 'drushsqldump';
  protected $description = 'Dump the Drupal database to an sql file.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Dumping the Drupal database.');

    return array(
      $this->drush->runForCurrentRelease('sqlDump', $this->releasesManager->getCurrentRelease() . '.sql'),
    );
  }
}
