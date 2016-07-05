<?php
namespace Rocketeer\Plugins\Drush;

use Illuminate\Container\Container;
use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

spl_autoload_register(function ($class) {
  include 'Tasks/' . $class . '.php';
  class_alias('Rocketeer\\Plugins\\Drush\\Tasks\\' . $class, $class);
});

foreach (glob("Tasks/*.php") as $class) {
  spl_autoload_register($class);
}

class RocketeerDrush extends AbstractPlugin {
  
  public $binaryPath = 'Rocketeer\Plugins\Drush\Binaries\Drush';

  protected $lookups = array(
    'binaries' => array(
      'Rocketeer\Plugins\Drush\Binaries\%s',
    ),
    'tasks' => array(
      'Rocketeer\Plugins\Drush\Tasks\%s',
    ),
  );

  /**
   * Setup the plugin.
   *
   * @param Container $app
   */
  public function __construct(Container $app) {
    parent::__construct($app);
    $this->configurationFolder = implode(DIRECTORY_SEPARATOR, array(
      __DIR__,
      "..",
      "..",
      "config"
    ));
  }

  public function getConfig($task, $key, $default = '') {
    if ($task->rocketeer->getOption('drush.' . $key)) {
      return $task->rocketeer->getOption('drush.' . $key);
    }
    else if ($task->config->get('rocketeer-drush::drush.' . $key)) {
      return $task->config->get('rocketeer-drush::drush.' . $key);
    }
    else {
      return $default;
    }
  }

  /**
   * Register Tasks with Rocketeer
   *
   * @param \Rocketeer\Services\TasksHandler $queue
   *
   * @return void
   */
  public function onQueue(TasksHandler $queue) {
    // Before deploy.
    $drushCommand = new DrushSqlDump($this->app, $this);
    $queue->addTaskListeners('deploy', 'before', [clone $drushCommand], -10, true);

    $drushCommand = new DrushRunConfiguredTasks($this->app, $this, 'before');
    $queue->addTaskListeners('deploy', 'before', [clone $drushCommand], -10, true);

    // After deploy.
    $drushCommand = new DrushSiteSet($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushConfigImport($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushMaintenanceModeOn($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushUpdatedb($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushMaintenanceModeOff($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushCacheRebuild($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushRunConfiguredTasks($this->app, $this, 'after');
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);
  }
}
