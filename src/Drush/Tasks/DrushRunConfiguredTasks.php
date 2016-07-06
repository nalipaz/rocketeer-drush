<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Illuminate\Container\Container;

class DrushRunConfiguredTasks extends DrushBaseTask {

  protected $name = 'Drush Run Extra Tasks';
  protected $description = 'Run all extra Drush tasks in the configuration.';
  protected $drushTasks = array();
  protected $event;

  function __construct(Container $app, $drushPlugin, $event) {
    parent::__construct($app, $drushPlugin);

    $this->event = $event;
    $extraTasks = $this->drushPlugin->getConfig($this, 'extra_tasks', array());

    $this->drushTasks = (array_key_exists($event, $extraTasks)) ? $extraTasks[$event] : $extraTasks;
  }

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    if (!empty($this->drushTasks)) {
      $this->explainer->line('Running all extra Drush tasks configured for the ' . $this->event . ' hook.');
      foreach ($this->drushTasks as $task) {
        $this->drush->run($task);
      }
    }
    else {
      $this->explainer->line('No extra Drush tasks configured for the ' . $this->event . ' hook, nothing to do.');
    }
  }
}
