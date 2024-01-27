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
}
