<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\Metrics;

class MetricsGeneratorRegistry
{
    /**
     * @var MetricsGeneratorInterface[]
     */
    private $generators = [];

    public function registerMetricsGenerator(MetricsGeneratorInterface $generator): void
    {
        $this->generators[] = $generator;
    }

    /**
     * @return MetricsGeneratorInterface[]
     */
    public function getMetricsGenerators(): array
    {
        return $this->generators;
    }
}
