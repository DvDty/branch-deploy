<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;

class Kubectl
{
    public function getPods(): Collection
    {
        $json = '{
    "apiVersion": "v1",
    "items": [
        {
            "apiVersion": "v1",
            "kind": "Pod",
            "metadata": {
                "creationTimestamp": "2024-01-21T23:36:20Z",
                "generateName": "branch-deploy-master-5d4cdc47f4-",
                "labels": {
                    "app": "branch-deploy-master",
                    "pod-template-hash": "5d4cdc47f4"
                },
                "name": "branch-deploy-master-5d4cdc47f4-dbkm9",
                "namespace": "default",
                "ownerReferences": [
                    {
                        "apiVersion": "apps/v1",
                        "blockOwnerDeletion": true,
                        "controller": true,
                        "kind": "ReplicaSet",
                        "name": "branch-deploy-master-5d4cdc47f4",
                        "uid": "33f6393e-2fa1-4b1e-8539-145510756c5f"
                    }
                ],
                "resourceVersion": "21582",
                "uid": "4f51f5b6-7b4f-4d3c-9020-6dee52f3cd23"
            },
            "spec": {
                "containers": [
                    {
                        "image": "dvdty/branch-deploy-master:latest",
                        "imagePullPolicy": "Always",
                        "name": "branch-deploy-master",
                        "ports": [
                            {
                                "containerPort": 80,
                                "protocol": "TCP"
                            }
                        ],
                        "resources": {},
                        "terminationMessagePath": "/dev/termination-log",
                        "terminationMessagePolicy": "File",
                        "volumeMounts": [
                            {
                                "mountPath": "/var/run/secrets/kubernetes.io/serviceaccount",
                                "name": "kube-api-access-2jw77",
                                "readOnly": true
                            }
                        ]
                    }
                ],
                "dnsPolicy": "ClusterFirst",
                "enableServiceLinks": true,
                "nodeName": "minikube",
                "preemptionPolicy": "PreemptLowerPriority",
                "priority": 0,
                "restartPolicy": "Always",
                "schedulerName": "default-scheduler",
                "securityContext": {},
                "serviceAccount": "default",
                "serviceAccountName": "default",
                "terminationGracePeriodSeconds": 30,
                "tolerations": [
                    {
                        "effect": "NoExecute",
                        "key": "node.kubernetes.io/not-ready",
                        "operator": "Exists",
                        "tolerationSeconds": 300
                    },
                    {
                        "effect": "NoExecute",
                        "key": "node.kubernetes.io/unreachable",
                        "operator": "Exists",
                        "tolerationSeconds": 300
                    }
                ],
                "volumes": [
                    {
                        "name": "kube-api-access-2jw77",
                        "projected": {
                            "defaultMode": 420,
                            "sources": [
                                {
                                    "serviceAccountToken": {
                                        "expirationSeconds": 3607,
                                        "path": "token"
                                    }
                                },
                                {
                                    "configMap": {
                                        "items": [
                                            {
                                                "key": "ca.crt",
                                                "path": "ca.crt"
                                            }
                                        ],
                                        "name": "kube-root-ca.crt"
                                    }
                                },
                                {
                                    "downwardAPI": {
                                        "items": [
                                            {
                                                "fieldRef": {
                                                    "apiVersion": "v1",
                                                    "fieldPath": "metadata.namespace"
                                                },
                                                "path": "namespace"
                                            }
                                        ]
                                    }
                                }
                            ]
                        }
                    }
                ]
            },
            "status": {
                "conditions": [
                    {
                        "lastProbeTime": null,
                        "lastTransitionTime": "2024-01-21T23:36:20Z",
                        "status": "True",
                        "type": "Initialized"
                    },
                    {
                        "lastProbeTime": null,
                        "lastTransitionTime": "2024-01-21T23:36:21Z",
                        "status": "True",
                        "type": "Ready"
                    },
                    {
                        "lastProbeTime": null,
                        "lastTransitionTime": "2024-01-21T23:36:21Z",
                        "status": "True",
                        "type": "ContainersReady"
                    },
                    {
                        "lastProbeTime": null,
                        "lastTransitionTime": "2024-01-21T23:36:20Z",
                        "status": "True",
                        "type": "PodScheduled"
                    }
                ],
                "containerStatuses": [
                    {
                        "containerID": "docker://f4c9faa3579597196e6b4305641d98155d04d1234723638da08c140d1f77b294",
                        "image": "dvdty/branch-deploy-master:latest",
                        "imageID": "docker-pullable://dvdty/branch-deploy-master@sha256:fbab8db12455c957ff32eaae2632add44e9cd5d4ece7a046a3d32c0d5a6447a6",
                        "lastState": {},
                        "name": "branch-deploy-master",
                        "ready": true,
                        "restartCount": 0,
                        "started": true,
                        "state": {
                            "running": {
                                "startedAt": "2024-01-21T23:36:21Z"
                            }
                        }
                    }
                ],
                "hostIP": "192.168.49.2",
                "phase": "Running",
                "podIP": "10.244.0.26",
                "podIPs": [
                    {
                        "ip": "10.244.0.26"
                    }
                ],
                "qosClass": "BestEffort",
                "startTime": "2024-01-21T23:36:20Z"
            }
        }
    ],
    "kind": "List",
    "metadata": {
        "resourceVersion": ""
    }
}';

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

        dd(Process::run('kubectl get pods')->output());
    }
}
