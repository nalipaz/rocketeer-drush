<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Rocketeer\Binaries\Php;
use Illuminate\Container\Container;

class DrushTwigDebug extends DrushBaseTask {

  protected $name = 'Drush Set Twig Debug';
  protected $description = 'Set Twig debugging on or off.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    parent::execute();
    $setting = $this->drushPlugin->getConfig($this, 'twig_debug');
    $label = ($setting) ? 'on' : 'off';
    $this->explainer->line('Turning ' . $label . ' Twig debugging.');

    return array(
      $this->drush->run('twigDebug', $this->drushPlugin->getConfig($this, 'twig_debug')),
    );
  }
}