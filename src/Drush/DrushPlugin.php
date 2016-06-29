<?php
namespace Rocketeer\Plugins\Drush;

use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

class DrushPlugin extends AbstractPlugin {

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
      "config"
    ));
  }

  /**
   * Register Tasks with Rocketeer
   *
   * @param \Rocketeer\Services\TasksHandler $queue
   *
   * @return void
   */
  public function onQueue(TasksHandler $queue) {
    $queue->{'before-symlink'}('deploy', function ($task) {
      $drush = $this->builder->buildBinary('drush');
      $drush->getCommand('DrushConfigImport');
      $drush->getCommand('DrushUpdatedb');
      $drush->getCommand('DrushAdvAggClearAllFiles');
      $drush->getCommand('DrushCacheRebuild');
    });
  }
}
