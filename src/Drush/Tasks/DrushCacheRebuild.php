<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushCacheRebuild extends DrushBaseTask {

  protected $name = 'drushupdatedb';
  protected $description = 'Rebuild the Drupal cache.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Rebuilding the Drupal cache.');

    return array(
      // @TODO: Make this dependent on module existence.
      $this->drush->run('advaggClearAllFiles'),
      $this->drush->run('cacheRebuild'),
    );
  }
}
