<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushMaintenanceMode extends DrushBaseTask {

  protected $name = 'Drush Set Maintenance Mode';
  protected $description = 'Set Drupal maintenance mode.';
  protected $state;

  function __construct(Container $app, $drushPlugin, $state) {
    parent::__construct($app, $drushPlugin);

    $this->state = $state;
  }

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    parent::execute();
    $label = ($this->state === '0') ? 'off' : 'on';
    $this->explainer->line('Turning ' . $label . ' Drupal\'s maintenance mode.');

    return array(
      $this->drush->run('setMaintenanceMode', $this->state),
    );
  }
}
