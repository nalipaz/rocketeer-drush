# Rocketeer Drush Plugin

[![Join the chat at https://gitter.im/nalipaz/rocketeer-drush](https://badges.gitter.im/nalipaz/rocketeer-drush.svg)](https://gitter.im/nalipaz/rocketeer-drush?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Rocketeer plugin for Drush. Performs various tasks on before-symlink.deploy.

## Install

Via Composer

``` bash
$ rocketeer plugin:install nalipaz/rocketeer-drush
```

## Usage

Setup the configuration for your project by running:

``` bash
rocketeer plugin:config nalipaz/rocketeer-drush
```
Then edit the produced configuration file to set your project settings.

You also need to add the plugin to the plugins array in `.rocketeer/config.php`:

``` php
  'plugins' => [
    'Rocketeer\Plugins\Drush\RocketeerDrush',
  ],
```

And lastly (until [this issue for Rocketeer 3.0](https://github.com/rocketeers/rocketeer/issues/680) gets resolved), you need to edit `.rocketeer/remote.php` to have `'drush'` in the shelled array: `'shelled' => ['drush'],`.

### Overriding configuration

You can set global configuration for your project as described under usage above. However, in some projects you may wish to override settings per connection, stages, or strategies (most commonly connections). As an example, you could do the following for multiple connections with different configs.

```
.rocketeer/connections/
├── local
│   └── remote.php
├── production
│   ├── drush.php
│   ├── remote.php
│   └── scm.php
└── staging
    ├── drush.php
    ├── remote.php
    └── scm.php
```
In the above example we are overriding some configuration in both the production and staging connections. `drush.php` is simply a copy of `.rocketeer/plugins/rocketeers/rocketeer-drush/drush.php` which has been modified with different settings like a different `drush_alias`.
