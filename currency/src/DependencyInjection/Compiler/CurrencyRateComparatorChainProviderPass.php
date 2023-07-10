<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CurrencyRateComparatorChainProviderPass implements CompilerPassInterface
{
    private const CURRENCY_RATE_COMPARATOR_HANDLER_TAG = 'app.currency_rate_handler';

    public function process(ContainerBuilder $container): void
    {
        if ('test' === $container->getParameterBag()->get('kernel.environment')) {
            return;
        }

        $handlers = $container->findTaggedServiceIds(self::CURRENCY_RATE_COMPARATOR_HANDLER_TAG);

        if (empty($handlers)) {
            return;
        }

        $handlers = array_keys($handlers);

        /** @var string $firstHandler */
        $firstHandler = array_shift($handlers);
        $handlerDefinition = $container->getDefinition($firstHandler);
        $container->setDefinition(CurrencyRateComparatorChainInterface::class, $handlerDefinition);

        foreach ($handlers as $handler) {
            $handler = $container->getDefinition($handler);
            $handlerDefinition->addMethodCall('setNext', [$handler]);
            $handlerDefinition = $handler;
        }
    }
}
