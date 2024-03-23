# https://makefile.site

.DEFAULT_GOAL : help
# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

ARGS = $(filter-out $@,$(MAKECMDGOALS))
%:
 @:

up: ## Run sail docker-compose with detach
	./vendor/bin/sail up -d

prepare:
	./vendor/bin/sail composer install
	./vendor/bin/sail npm install
	./vendor/bin/sail artisan migrate --seed
	./vendor/bin/sail artisan scout:import "App\Models\ContentChunk"
	./vendor/bin/sail artisan ide-helper:models -W -R

down: ## Terminate sail docker-compose
	./vendor/bin/sail down

restart: down up ## Restart sail docker-compose

front: vite-dev ## Run vite dev server

logs:
	./vendor/bin/sail logs -f

vite-dev: ## npm run dev
	./vendor/bin/sail npm run dev

vite-build: ## npm run build
	./vendor/bin/sail npm run build

server-ssr: ## Launch SSR server
	npm run build && node bootstrap/ssr/ssr.mjs npm run ssr-server

server-meilisearch: ## Launch Meilisearch server
	docker run -it --rm -p 7700:7700 -e MEILI_ENV='development' -e MEILI_MASTER_KEY='8yy_tNVZJ8_MdNa5RQiThfnC-MNZDD0F79xUS49tTq0' -v $(pwd)/meili_data:/meili_data getmeili/meilisearch:v1.7

migrate: ## Run migrate with ide-helper
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail artisan ide-helper:models -W -R

migrate-rollback: ## Run migrate:rollback
	./vendor/bin/sail artisan migrate:rollback

migrate-seed: ## Run migrate with ide-helper and seed
	./vendor/bin/sail artisan migrate --seed
	./vendor/bin/sail artisan ide-helper:models -W -R

migrate-refresh: migrate-rollback migrate-seed reindex ## DELETE ALL DATABASE, then run migrate with ide-helper and seed

migrate-refresh-seed: ## DELETE ALL DATABASE, then run migrate with ide-helper and seed
	# ./vendor/bin/sail artisan scout:flush "App\Models\ContentChunk"
	./vendor/bin/sail artisan migrate:refresh --seed
	./vendor/bin/sail artisan ide-helper:models -W -R

import-file-suttas: ## Import suttas from json files from suttacentral
	./vendor/bin/sail artisan lb:import_file_suttas --rebuild

import-theravadaru-suttas: ## Import suttas from theravada.ru
	./vendor/bin/sail artisan lb:import_theravadaru_suttas --rebuild

ide: ## Generate ide-helper and models docblocks
	./vendor/bin/sail artisan ide-helper:generate
	./vendor/bin/sail artisan ide-helper:models -W -R

clear: ## Clear config and route cache
	./vendor/bin/sail artisan cache:clear
	./vendor/bin/sail artisan config:clear
	./vendor/bin/sail artisan route:clear

pint: ## Run php linter
	./vendor/bin/pint --config ./pint.json

reindex: ## Reindex scout search
	./vendor/bin/sail artisan scout:flush "App\Models\ContentChunk"
	./vendor/bin/sail artisan scout:import "App\Models\ContentChunk"
