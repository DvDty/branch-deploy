<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;

class Kubectl
{
    public function getPods(): Collection
    {
        $json = Process::run('kubectl get pods --output=json')->output();

        $json = json_decode($json, true);

        $pods = collect();

        foreach (data_get($json, 'items') as $pod) {
            $pods->push([
                'podLabel' => data_get($pod, 'metadata.labels.app'),
                'image' => data_get($pod, 'spec.containers.0.image'),
                'status' => data_get($pod, 'status.phase'),
                'hostIp' => data_get($pod, 'status.hostIP'),
                'podIp' => data_get($pod, 'status.podIP'),
            ]);
        }

        return $pods;
    }

    public function createInstance(string $branch, int $lifespan): void
    {
        $createDeploymentCommand = "kubectl create deployment branch-deploy-application-{$branch} --image=dvdty/branch-deploy-application:{$branch}-latest --port=80";
        $createServiceCommand = "kubectl create service nodeport branch-deploy-application-service-{$branch} --tcp=80:80 -o yaml --dry-run=client | kubectl set selector --local -f - 'app=branch-deploy-application-{$branch}' -o yaml | kubectl create -f -";

        Process::run($createDeploymentCommand);
        Process::run($createServiceCommand);
    }
}
