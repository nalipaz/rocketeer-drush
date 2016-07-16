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
  public function siteSet($quiet = FALSE) {
    $flags = ($quiet) ? '-q' : array();

    return $this->getCommand('site-set', $this->alias, $flags);
  }

  public function status() {
    return $this->getCommand('status');
  }

  public function coreStatus($value) {
    // Example:
    // # drush core-status 'drupal version' --format=list --no-field-labels
    // 8.1.2
    return $this->getCommand($this->alias, [
      'core-status',
      sprintf("'%s'", $value),
      '--format=list',
      '--no-field-labels'
    ]);
  }

  public function sqlDump($destination = 'backup.sql') {
    // Somehow sql-dump doesn't ever use the alias when set in a prior command.
    return $this->siteSet(TRUE) .
    ' && ' .
    $this->getCommand('sql-dump', ['>', $destination]);
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

  public function viewsDev() {
    return $this->getCommand('views-dev');
  }

  public function drupalDirectory() {
    return $this->siteSet(TRUE) .
      ' && ' .
      $this->getCommand('drupal-directory');
  }

  public function twigDebug($value = FALSE) {
    $faux_bool = $value ? 'TRUE' : 'FALSE';
    $code = sprintf('\'use Symfony\Component\Yaml\Yaml;
 use Drupal\Component\Serialization\Yaml as SerializationYaml;
 $config = Yaml::parse(file_get_contents(DRUPAL_ROOT . "/sites/default/services.yml"));
 $config["parameters"]["twig.config"]["debug"] = %s;
 file_put_contents(DRUPAL_ROOT . "/sites/default/services.yml", SerializationYaml::encode($config));\'', $faux_bool);

    return $this->getCommand('php-eval', $code);
  }

  // Catch-all to handle aliases.
  public function __call($method, $args) {
    $aliases = array(
      'advagg-clear-all-files' => 'advaggClearAllFiles',
      'advagg-caf' => 'advaggClearAllFiles',
      'cache-rebuild' => 'cacheRebuild',
      'cr' => 'cacheRebuild',
      'rebuild' => 'cacheRebuild',
      'config-import' => 'configImport',
      'cim' => 'configImport',
      'drupal-directory' => 'drupalDirectory',
      'dd' => 'drupalDirectory',
      'site-set' => 'siteSet',
      'use' => 'siteSet',
      'sql-dump' => 'sqlDump',
      'updb' => 'updatedb',
      'views-dev' => 'viewsDev',
      'vd' => 'viewsDev',
    );
    
    if (array_key_exists($method, $aliases)) {
      return call_user_func_array([$this, $aliases[$method]], $args);
    }
    else {
      $parent = get_parent_class();
      if ($parent && (method_exists($parent, $method) || method_exists($parent, '__call'))) {
        return parent::__call($method, $args);
      }
    }
  }
}
