<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushSiteSet extends DrushBaseTask {

  protected $name = 'Drush Set Site Alias';
  protected $description = 'Set the active site.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Setting the active site.');

    return array(
      $this->drush->run('siteSet'),
    );
  }
}
