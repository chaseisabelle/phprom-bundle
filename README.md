# phprom client symfony 4+ bundle
a symfony client for [phprom](https://github.com/chaseisabelle/phprom), a prometheus metrics datastore for php apps

---
## example

see a fully functional example [here](https://github.com/chaseisabelle/phprom-example)

---
## prerequisites
- [phprom](https://github.com/chaseisabelle/phprom) server
- [grpc extension](https://grpc.io/docs/languages/php/quickstart/)
    - `pecl install grpc`
    - or use the [docker image](https://hub.docker.com/r/grpc/php)

#### related
this is essentially a symfony bundle wrapper for the [phprom client](https://github.com/chaseisabelle/phprom-client)

---
## install

#### with symfony flex
```
composer require chaseisabelle/phprom-bundle
```

#### without symfony flex
1. install with composer
    ```
    composer require chaseisabelle/phprom-bundle
    ```
2. enable in `app/AppKernel.php`
    ```php
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                ...
                new ChaseIsabelle\PHPromBundle\ChaseIsabellePHPromBundle(),
            );
            ...
    ```

---
## configure

1. create `config/packages/phprom.yaml`
    ```yaml
    phprom:
      address: 127.0.0.1:3333 # optional, defaults to 127.0.0.1:3333
      api: grpc # optional, defaults to grpc (use "rest" for rest api)
      namespace: my_cool_app # required, the prefix for all your metrics
      routes: # optional, if empty or omitted then all routes will be recorded
        - my_cool_route # route can be plain string - only routes matching these strings will be recorded
        - /^.+$/ # route can be a regex - only routes matching this regex will be recorded
    ```

2. open `config/routes.yaml` and add
    ```yaml
    metrics:
        resource: '@ChaseIsabellePHPromBundle/Resources/config/routes.xml'
    ```
   or you can customize
   ```yaml
   metrics:
       path: /custom/url/path
       controller: ChaseIsabelle\PHPromBundle\Controller\MetricsController::metrics
   ```

---
## custom metrics

#### example 

`src/Controller/DefaultController.php`
```php
<?php

namespace App\Controller;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @param PHPromService $phpromService
     * @return Response
     * @throws Exception
     */
    public function index(PHPromService $phpromService)
    {
        $counter = $phpromService->counter()
            ->setName('custom_counter')
            ->setDescription('my custom counter')
            ->setLabels(['foo']); //<< optional

        $counter->record(
            rand(1, 10),
            ['foo' => 'bar'] //<< optional
        );

        $histogram = $phpromService->histogram()
            ->setName('custom_histogram')
            ->setDescription('my custom histogram')
            ->setLabels(['foo']) //<< optional
            ->setBuckets(range(1, 10)); //<< optional

        $histogram->record(
            rand(1, 100) / 10,
            ['foo' => 'bar'] //<< optional
        );

        $summary = $phpromService->summary()
            ->setName('custom_summary')
            ->setDescription('my custom summary')
            ->setLabels(['foo']) //<< optional
            ->setObjectives(range(1, 5)) //<< optional
            ->setAgeBuckets(5) //<< optional
            ->setMaxAge(10)    //<< optional
            ->setBufCap(5); //<< optional

        $summary->record(
            rand(1, 100) / 10,
            ['foo' => 'bar'] //<< optional
        );

        $gauge = $phpromService->gauge()
            ->setName('custom_gauge')
            ->setDescription('my custom gauge')
            ->setLabels(['foo']); //<< optional

        $gauge->record(
            rand(1, 10),
            ['foo' => 'bar'] //<< optional
        );

        return new Response($phpromService->instance()->get(), 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
```
