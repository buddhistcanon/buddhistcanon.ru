# https://makefile.site
ARGS = $(filter-out $@,$(MAKECMDGOALS))
%:
 @:

up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

vite-dev:
	./vendor/bin/sail npm run dev

vite-build:
	./vendor/bin/sail npm run build
