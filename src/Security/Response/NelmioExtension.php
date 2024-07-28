<?php

namespace App\Security\Response;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

readonly class NelmioExtension
{
    public function __construct(
        private ParameterBagInterface $parameterBag
    ) {

    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        $configurationFinder = Finder::create()
            ->in($this->parameterBag->get('kernel.project_dir') . '/config/app')
            ->in($this->parameterBag->get('kernel.project_dir') . '/config/packages')
            ->depth(0)
            ->name(['*.yaml']);

        $components = [];

        foreach ($configurationFinder as $configurationFile)
        {
            $parsedConfigurationFile = Yaml::parseFile($configurationFile->getPathname(), Yaml::PARSE_CUSTOM_TAGS);

            if ($this->checkComponentsExist($parsedConfigurationFile)){
                $components = array_merge_recursive($components, ['components' => $parsedConfigurationFile['nelmio_api_doc']['documentation']['components']]);
            }
        }

        return $components;
    }

    private function checkComponentsExist(?array $parsedConfigurationFile): bool
    {
        return $parsedConfigurationFile
            && array_key_exists('nelmio_api_doc', $parsedConfigurationFile)
            && array_key_exists('documentation', $parsedConfigurationFile['nelmio_api_doc'])
            && array_key_exists('components', $parsedConfigurationFile['nelmio_api_doc']['documentation']);
    }
}