#!/bin/bash

kubectl get deployments -o=jsonpath='{range .items[*]}{.metadata.name} {.metadata.annotations.lifespan} {"\n"}{end}' | while read deployment lifespan; do
    if [ "$lifespan" != "" ]; then
        current_minus_lifespan=$(date -d "$lifespan minute ago" +%s)
        creation_timestamp=$(kubectl get deployment "$deployment" -o=jsonpath='{.metadata.creationTimestamp}')
        creation_seconds=$(date -d "$creation_timestamp" +%s)

        if [ "$current_minus_lifespan" -ge "$creation_seconds" ]; then
            kubectl delete deployment "$deployment"
            kubectl delete service "$deployment"-service
        fi
    fi
done