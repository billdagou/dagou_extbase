<?php
use Dagou\DagouExtbase\Mvc\Controller\EidActionController;
use Dagou\DagouExtbase\Mvc\Controller\EidControllerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $container): void {
    $container->registerForAutoconfiguration(EidControllerInterface::class)->addTag('de.eid_controller');
    $container->registerForAutoconfiguration(EidActionController::class)->addTag('de.eid_action_controller');

    $container->addCompilerPass(new class () implements CompilerPassInterface {
        /**
         * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
         *
         * @return void
         */
        public function process(ContainerBuilder $container): void {
            foreach ($container->findTaggedServiceIds('de.eid_controller') as $id => $tags) {
                $container->findDefinition($id)->setPublic(TRUE);
            }

            foreach ($container->findTaggedServiceIds('de.eid_action_controller') as $id => $tags) {
                $container->findDefinition($id)->setShared(FALSE);
            }
        }
    });
};