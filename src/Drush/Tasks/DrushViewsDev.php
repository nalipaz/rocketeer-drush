<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushViewsDev extends DrushBaseTask {

  protected $name = 'Drush Set Developer Views settings';
  protected $description = 'Set the Views settings to more developer-oriented.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    parent::execute();
    $this->explainer->line('Setting the Views settings to more developer-oriented.');

    return array(
      $this->drush->run('views-dev'),
    );
  }
}
