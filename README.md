# UserProcessorBundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/skowron-line/UserProcessorBundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4bf7ca88-b00c-4b46-a020-807c59a84e13/mini.png)](https://insight.sensiolabs.com/projects/4bf7ca88-b00c-4b46-a020-807c59a84e13)

# Install

`composer require skowronline/user-processor-bundle`

```yaml
# config.yml
imports:
    ...
    - { resource: "@SkowronlineUserProcessorBundle/Resources/config/services.yml" }
```

# Usage

```yaml
custom.stream.handler:
    class: Monolog\Handler\StreamHandler
    arguments: ["%kernel.logs_dir%/custom.log"]
    public: false
custom.logger:
    class: Symfony\Bridge\Monolog\Logger
    arguments: [symfony, ['@custom.stream.handler'], ['@skowronline.monolog.user.processor']]
```

```php
$this->get('custom.logger')->info('Custom message');
```

```
[2016-05-21 10:26:47] symfony.INFO: Custom message [] {"user":"anon."}
```


# Global usage
You have to override service definition and add `monolog.processor` tag

```yml
skowronline.monolog.user.processor.global:
	parent: skowronline.monolog.user.processor
	tags:
	  - { name: monolog.processor }
```
