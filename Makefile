RUNNER = docker compose
COMPOSE_FILE = .docker/docker-compose.dev.yml


.PHONY: sh up down

sh:
	$(RUNNER) --env-file=.env -f $(COMPOSE_FILE)  exec app bash

up:
	$(RUNNER) --env-file=.env -f $(COMPOSE_FILE) -p test-luso up -d --build

down:
	$(RUNNER) --env-file=.env -f $(COMPOSE_FILE) down

logs:
	$(RUNNER) --env-file=.env -f $(COMPOSE_FILE) logs -f
