<?php
namespace Rocketeer\Plugins\Drush\Tasks;

use Rocketeer\Rocketeer;
use Rocketeer\Plugins\Drush\RocketeerDrush;
use Rocketeer\Plugins\Drush\DrushBaseTask;
use Rocketeer\Binaries\Php;
use Illuminate\Container\Container;

class DrushTwigConfigDebug extends DrushBaseTask {

  protected $name = 'Drush Set Twig Config Debug';
  protected $description = 'Set Twig config debugging on or off.';

  /**
   * Executes the Task
   *
   * @return void
   */
  public function execute() {
    $setting = $this->drushPlugin->getConfig($this, 'twig_debug');
    $label = ($setting) ? 'on' : 'off';
    $this->explainer->line('Turning ' . $label . ' Twig config debugging.');

    return array(
      $this->drush->run('twigConfigDebug', $this->drushPlugin->getConfig($this, 'twig_debug')),
    );
  }
}