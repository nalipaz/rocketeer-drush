<?php
namespace Rocketeer\Plugins\Drush;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Illuminate\Container\Container;
use Rocketeer\Plugins\Drush\Binaries\Drush;

class DrushBaseTask extends \Rocketeer\Abstracts\AbstractTask {

  protected $drushPlugin;

  protected $drush;

  function __construct(Container $app, $drushPlugin) {
    parent::__construct($app);

    $this->drush = $this->binary($drushPlugin->binaryPath);
    $this->drushPlugin = $drushPlugin;
  }

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $drush_alias = $this->localStorage->get('drush_alias');
    if (!$drush_alias) {
      $drush_alias = $this->command->ask('What Drush Alias should be used for this deployment?');
      $this->localStorage->set('drush_alias', Drush::ensureAliasFormat($drush_alias));
    }
    $this->drush->setSiteAlias($this->localStorage->get('drush_alias'));
  }
}
