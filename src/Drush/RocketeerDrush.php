<?php
namespace Rocketeer\Plugins\Drush;

use Illuminate\Container\Container;
use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;
use Rocketeer\Plugins\Drush\Tasks\DrushSqlDump;

class RocketeerDrush extends AbstractPlugin {

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
//    $drushSqlDump = new DrushSqlDump($this->app);
//    $drushSqlDump->setDrush($this);
//    $queue->addTaskListeners('deploy', 'before', [clone $drushSqlDump], -10, true);
//
//    // Would've preferred to use $queue->addTaskListeners('deploy', 'before-symlink', function($task), but it runs three times...
//    $queue->before('deploy', function ($task) {
//      $drush = $task->binary('Rocketeer\Plugins\Drush\Binaries\Drush');
//      $drush->setSiteAlias($this->getConfig($task, 'drush_alias'));
//      $drush->run('siteSet');
//      $drush->run('sqlDump', $task->releasesManager->getCurrentRelease() . '.sql');
//    });
    $queue->after('deploy', function ($task) {
      $drush = $task->binary('Rocketeer\Plugins\Drush\Binaries\Drush');
      $drush->setSiteAlias($this->getConfig($task, 'drush_alias'));
      $drush->run('siteSet');
      $drush->run('sqlDump', $task->releasesManager->getCurrentRelease() . '.sql');
      $drush->run('configImport', $this->getConfig($task, 'drupal_config'));
      $drush->run('updatedb');
      $drush->run('advaggClearAllFiles');
      $drush->run('cacheRebuild');
    });
  }
}
