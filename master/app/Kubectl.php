<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;

class Kubectl
{
    public function getDeployments(): Collection
    {
        $json = Process::run('kubectl get deployments --output=json')
            ->throw()
            ->output();

        $json = json_decode($json, true);

        $deployments = collect();

        foreach (data_get($json, 'items', []) as $deployment) {
            $deployments->push([
                'name' => data_get($deployment, 'metadata.name'),
                'branch' => data_get($deployment, 'metadata.annotations.branch'),
                'lifespan' => data_get($deployment, 'metadata.annotations.lifespan') . ' minutes',
                'url' => '',
            ]);
        }

        return $deployments;
    }

    public function createInstance(string $branch, int $lifespan): string
    {
        $createDeploymentCommand = "kubectl create deployment branch-deploy-application-$branch --image=dvdty/branch-deploy-application:$branch --port=80";
        $applyLifespanCommand = "kubectl annotate deployment branch-deploy-application-$branch lifespan=$lifespan branch=$branch";
        $createServiceCommand = "kubectl create service nodeport branch-deploy-application-service-$branch --tcp=80:80 -o yaml --dry-run=client | kubectl set selector --local -f - 'app=branch-deploy-application-$branch' -o yaml | kubectl create -f -";

        return Process::run($createDeploymentCommand . ' && ' . $applyLifespanCommand . ' && ' . $createServiceCommand)
            ->throw()
            ->output();
    }
}
