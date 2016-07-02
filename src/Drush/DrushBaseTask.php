<?php
namespace Rocketeer\Plugins\Drush;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Illuminate\Container\Container;

class DrushBaseTask extends \Rocketeer\Abstracts\AbstractTask {

  protected $drushPlugin;

  protected $drush;

  function __construct(Container $app, $drushPlugin) {
    parent::__construct($app);

    $this->drush = $this->binary($drushPlugin->binaryPath);
    $this->drushPlugin = $drushPlugin;
    $this->drush->setSiteAlias($this->drushPlugin->getConfig($this, 'drush_alias'));
  }

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {}
}
