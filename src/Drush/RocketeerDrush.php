<?php
namespace Rocketeer\Plugins\Drush;

use Illuminate\Container\Container;
use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;
use Rocketeer\Plugins\Drush\Tasks\DrushSqlDump;
use Rocketeer\Plugins\Drush\Tasks\DrushSiteSet;
use Rocketeer\Plugins\Drush\Tasks\DrushConfigImport;
use Rocketeer\Plugins\Drush\Tasks\DrushUpdatedb;
use Rocketeer\Plugins\Drush\Tasks\DrushCacheRebuild;

class RocketeerDrush extends AbstractPlugin {
  
  public $binaryPath = 'Rocketeer\Plugins\Drush\Binaries\Drush';

  protected $lookups = array(
    'binaries' => array(
      'Rocketeer\Plugins\Drush\Binaries\%s',
    )
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

  public function getConfig($task, $key) {
    return ($task->rocketeer->getOption('drush.' . $key)) ? $task->rocketeer->getOption('drush.' . $key) : $task->config->get('rocketeer-drush::drush.' . $key);
  }

  /**
   * Register Tasks with Rocketeer
   *
   * @param \Rocketeer\Services\TasksHandler $queue
   *
   * @return void
   */
  public function onQueue(TasksHandler $queue) {
    $drushCommand = new DrushSqlDump($this->app, $this);
    $queue->addTaskListeners('deploy', 'before', [clone $drushCommand], -10, true);

    $drushCommand = new DrushSiteSet($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushConfigImport($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushUpdatedb($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);

    $drushCommand = new DrushCacheRebuild($this->app, $this);
    $queue->addTaskListeners('deploy', 'after', [clone $drushCommand], -10, true);
  }
}
