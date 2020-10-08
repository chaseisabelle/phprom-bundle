# phprom client symfony 4+ bundle
a symfony client for [phprom](https://github.com/chaseisabelle/phprom), a prometheus metrics datastore for php apps

---
## prerequisites
- [phprom](https://github.com/chaseisabelle/phprom) server
- [grpc extension](https://grpc.io/docs/languages/php/quickstart/)
    - `pecl install grpc`
    - or use the [docker image](https://hub.docker.com/r/grpc/php)

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
