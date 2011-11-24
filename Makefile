test: test-reports

test-reports:
	php index.php ninja_unit_test/reports modules/unit_test/reports/*.tst

test-ci-prepare: prepare-config
	@mkdir -p test/configs/all-host_service-states/var/rw
	@mkdir -p test/configs/all-host_service-states/var/spool/checkresults
	@/opt/monitor/bin/monitor -d test/configs/all-host_service-states/etc/nagios.cfg &> /dev/null
	@/bin/sleep 5
	@/bin/echo "[$$(date +%s)] SHUTDOWN_PROGRAM" >> test/configs/all-host_service-states/var/rw/nagios.cmd
	@/opt/monitor/op5/merlin/ocimp --force --cache=test/configs/all-host_service-states/var/objects.cache --status-log=test/configs/all-host_service-states/var/status.log &> /dev/null
	@php index.php 'cli/insert_user_data'

test-coverage: test-ci-prepare
	@php test/all_coverage.php $$(pwd)

docs: Documentation

Documentation: ninja.doxy application/models/*.php application/helpers/*.php
	doxygen ninja.doxy

help:
	@echo
	@echo Available make targets:
	@echo -----------------------
	@$(MAKE) --print-data-base --question | sed -n -e '/^Makefile/d' -e 's/^\([A-Za-z0-9_-]*\):.*/\1/p'
	@echo

wipe:
	php index.php ninja_unit_test/wipe_tables

prepare-config:
	@sed -e "s|@@TESTDIR@@|$$(pwd)/test/configs/all-host_service-states|" test/configs/all-host_service-states/etc/nagios.cfg.in > test/configs/all-host_service-states/etc/nagios.cfg

.PHONY: test help test-reports
