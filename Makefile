# https://makefile.site
ARGS = $(filter-out $@,$(MAKECMDGOALS))
%:
 @:

up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

