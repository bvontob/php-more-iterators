.PHONY: all
all:
	@echo "Noting to be made here. Just skip ahead to 'make tests'."

.PHONY: check
check: tests

.PHONY: test
test: tests

.PHONY: tests
tests:
	@which pear >/dev/null || ( echo "Need 'pear' to run tests." && false )
	pear run-tests tests/
