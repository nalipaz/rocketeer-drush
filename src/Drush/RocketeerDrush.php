<?php
namespace Rocketeer\Plugins\Drush;

use Illuminate\Container\Container;
use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

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

  /**
   * Register Tasks with Rocketeer
   *
   * @param \Rocketeer\Services\TasksHandler $queue
   *
   * @return void
   */
  public function onQueue(TasksHandler $queue) {
    $queue->after('deploy', function ($task) {
      $drush = $task->binary('drush');
      $drush->run('siteSet', [$task->config->get('rocketeer-drush::drush_alias')]);
      $drush->run('configImport', [$task->config->get('rocketeer-drush::drupal_config')]);
      $drush->run('updatedb');
      $drush->run('advaggClearAllFiles');
      $drush->run('cacheRebuild');
    });
  }
}
