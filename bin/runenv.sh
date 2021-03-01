#!/usr/bin/env bash

sudo ln -sf environment/development.env .env
sudo sysctl -w vm.max_map_count=262144
sudo sysctl -w fs.file-max=65536
ulimit -n 65536
ulimit -u 4096

sudo docker network create wiley-gateway
sudo docker-compose up --build
