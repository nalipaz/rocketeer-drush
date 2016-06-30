<?php
namespace Rocketeer\Plugins\Drush\Binaries;

use Rocketeer\Abstracts\AbstractBinary;

class Drush extends AbstractBinary {

  protected $alias = '@self';
  protected $coreVersion;

  /**
   * Get an array of default paths to look for
   *
   * @return string[]
   */
  protected function getKnownPaths() {
    return array(
      'drush',
      '/usr/bin/drush',
      '/usr/local/bin/drush',
    );
  }

  protected function ensureAliasFormat($alias) {
    if (strpos($alias, '@') !== 0) {
      $alias = '@' . $alias;
    }
    return $alias;
  }

  public function setSiteAlias($alias) {
    $this->alias = $this->ensureAliasFormat($alias);

    return $this->alias;
  }

  /**
   * Allow setting the drush alias to use for the session.
   */
  public function siteSet() {
    return $this->getCommand('site-set', $this->alias);
  }

  public function status() {
    return $this->getCommand('status');
  }

  public function statusInfo($value) {
    // Example:
    // # drush status|grep "Drupal version"|awk -F ":  " '{print $2}'|xargs
    // 8.1.2
    return $this->getCommand($this->alias, [
      'status',
      '|',
      'grep',
      sprintf('"%s"', $value),
      '|',
      'awk',
      '-F',
      '":  "',
      "'{print $2}'",
      '|',
      'xargs'
    ]);
  }

  public function sqlDump($destination = 'backup.sql') {
    return $this->getCommand('sql-dump', ['>', $destination]);
  }

  public function configImport($config = 'sync') {
    return $this->getCommand('config-import', $config, '-y');
  }

  public function updatedb() {
    return $this->getCommand('updatedb', [], '-y');
  }

  public function cacheRebuild() {
    return $this->getCommand('cache-rebuild');
  }

  public function advaggClearAllFiles() {
    return $this->getCommand('advagg-clear-all-files');
  }

  public function setMaintenanceMode($value = '1') {
    return $this->getCommand('sset', 'system.maintenance_mode', "'$value'");
  }

  public function copyDatabase() {
    // drush sql-create RELEASE && drush @alias sql-dump | mysql --user=user --password=pass --host=localhost RELEASE
    // drush sql-sync????
  }
}
