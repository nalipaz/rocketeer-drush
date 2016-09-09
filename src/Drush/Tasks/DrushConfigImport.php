<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushConfigImport extends DrushBaseTask {

  protected $name = 'Drush Import Configuration';
  protected $description = 'Import the drupal configuration files.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    parent::execute();
    $this->explainer->line('Importing the drupal configuration files.');

    return array(
      $this->drush->run('configImport', $this->drushPlugin->getConfig($this, 'drupal_config')),
    );
  }
}
