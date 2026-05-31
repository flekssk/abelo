CONTAINER_NAME=php

.PHONY: restart-rr reload rr-status

restart-rr:
	@echo "🔄 Перезапуск воркеров RoadRunner внутри контейнера..."
	@docker compose exec $(CONTAINER_NAME) /usr/local/bin/rr reset
	@echo "✅ Код успешно обновлен!"

reload: restart-rr

rr-status:
	@docker compose exec $(CONTAINER_NAME) /usr/local/bin/rr workers -c .rr.yaml

console:
	docker compose -f docker-compose.yml exec php bin/console $(filter-out $@,$(MAKECMDGOALS))

%:
	@: