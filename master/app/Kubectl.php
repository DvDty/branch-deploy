<?php

namespace App;

use Http\Adapter\Guzzle7\Client as HttpClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maclof\Kubernetes\Client;
use Maclof\Kubernetes\Models\Deployment;
use Maclof\Kubernetes\Models\Service;

class Kubectl
{
    public function getDeployments(): Collection
    {
        $deployments = collect();
        $services = collect();

        foreach ($this->kubectl()->services()->find()->toArray() as $service) {
            $service = $service->toArray();

            $services->put(
                data_get($service, 'metadata.name'),
                data_get($service, 'spec.ports.0.nodePort'),
            );
        }

        foreach ($this->kubectl()->deployments()->find()->toArray() as $deployment) {
            $deployment = $deployment->toArray();

            if (!Str::startsWith(data_get($deployment, 'metadata.name'), 'branch-deploy-application')) {
                continue;
            }

            $deployments->push([
                'name' => data_get($deployment, 'metadata.name'),
                'branch' => data_get($deployment, 'metadata.annotations.branch'),
                'lifespan' => data_get($deployment, 'metadata.annotations.lifespan') . ' minutes',

                # Hardcoded cluster IP, need to refactor "NodePort" services to fix :(
                'url' => 'http://192.168.49.2:' . $services->get(data_get($deployment, 'metadata.name') . 'service'),
            ]);
        }

        return $deployments;
    }

    public function createInstance(string $branch, string $lifespan): array
    {
        $deployment = [
            'metadata' => [
                'name' => 'branch-deploy-application-' . $branch,
                'annotations' => [
                    'branch' => $branch,
                    'lifespan' => $lifespan,
                ],
            ],
            'spec' => [
                'replicas' => 1,
                'selector' => [
                    'matchLabels' => [
                        'app' => 'branch-deploy-application-' . $branch,
                    ],
                ],
                'template' => [
                    'metadata' => [
                        'labels' => [
                            'app' => 'branch-deploy-application-' . $branch,
                        ],
                    ],
                    'spec' => [
                        'containers' => [
                            [
                                'name' => 'branch-deploy-application-' . $branch,
                                'image' => 'dvdty/branch-deploy-application:' . $branch,
                                'imagePullPolicy' => 'Always',
                                'ports' => [
                                    ['containerPort' => 80],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $service = [
            'metadata' => ['name' => "branch-deploy-application-$branch-service"],
            'spec' => [
                'selector' => [
                    'app' => 'branch-deploy-application-' . $branch,
                ],
                'ports' => [
                    [
                        'protocol' => 'TCP',
                        'port' => 80,
                        'targetPort' => 80,
                    ],
                ],
                'type' => 'NodePort',
            ],
        ];

        $this->kubectl()->deployments()->create(new Deployment($deployment));
        $this->kubectl()->services()->create(new Service($service));

        return [];
    }

    public function kubectl(): Client
    {
        $httpClient = HttpClient::createWithConfig(['verify' => '/var/run/secrets/kubernetes.io/serviceaccount/ca.crt']);

        return new Client([
            'master' => 'https://10.96.0.1:443',
            'token' => '/var/run/secrets/kubernetes.io/serviceaccount/token',
        ], null, $httpClient);
    }
}
