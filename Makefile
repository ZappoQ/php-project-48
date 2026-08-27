COVERAGE_MIN = 80

install:
	composer install

lint:
	composer run-script lint

test:
	php vendor/bin/phpunit

test-coverage:
	php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-clover=build/logs/clover.xml --coverage-text=build/logs/coverage.txt
	@echo "Checking coverage..."
	@COVERAGE=$$(grep -o '[0-9]\+\.[0-9]\+%' build/logs/coverage.txt | head -1 | tr -d '%'); \
	if [ -z "$$COVERAGE" ]; then \
		echo "Error: Could not determine code coverage"; \
		exit 1; \
	fi; \
	if [ $$(echo "$$COVERAGE < $(COVERAGE_MIN)" | bc) -eq 1 ]; then \
		echo "Error: Code coverage is $$COVERAGE%, which is below the minimum of $(COVERAGE_MIN)%"; \
		exit 1; \
	else \
		echo "Code coverage is $$COVERAGE%, which meets the minimum of $(COVERAGE_MIN)%"; \
	fi