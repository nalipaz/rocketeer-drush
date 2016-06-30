<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;

class DrushSqlDump extends \Rocketeer\Abstracts\AbstractTask {

  protected $name = 'drushsqldump';

  /**
   * Dump the sql to a file.
   *
   * @var string
   */
  protected $description = 'Dump the database to an sql file.';

  public function getConfig($task, $key) {
    return ($task->rocketeer->getOption('drush.' . $key)) ? $task->rocketeer->getOption('drush.' . $key) : $task->config->get('rocketeer-drush::drush.' . $key);
  }

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $this->explainer->line('Dumping the Drupal database.');
    $drush = $this->binary('Rocketeer\Plugins\Drush\Binaries\Drush');
    $drush->setSiteAlias($this->getConfig($this, 'drush_alias'));

    return array(
      $drush->run('siteSet'),
      $drush->run('sqlDump', $task->releasesManager->getCurrentRelease() . '.sql'),
    );
  }
}