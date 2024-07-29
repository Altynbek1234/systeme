# Тестовое задание
Проект для Systeme.io

## Запуск проекта
1) Установить docker и docker-compose последней версии;
2) Клонировать проект;
3) Открыть в терминале директорию проекта;
4) Запустить в терминале команду "docker-compose up -d --build" ;
5) Запустить в терминале cp .env_example .env чтобы настроить env файл;
6) Запустить в терминале команду docker exec -it backend-test-task-sio_test-1 composer install;
7) Запустить в терминале команду docker exec -it backend-test-task-sio_test-1 php bin/console doctrine:migrations:migrate чтобы создать таблицы и заполнить их;


POST: для расчёта цены
curl -X POST http://127.0.0.1:8337/calculate-price \
-H "Accept: application/json" \
-H "Content-Type: application/json" \
-d '{"product": 1, "taxNumber": "DE123456789", "couponCode": "D15"}'


POST: для осуществления покупки
curl -X POST http://127.0.0.1:8337/purchase \
-H "Accept: application/json" \
-H "Content-Type: application/json" \
-d '{"product": 4, "taxNumber": "IT12345678900", "couponCode": "D15", "paymentProcessor": "paypal"}'
git remote add origin 
git remote add systeme https://github.com/Altynbek1234/systeme.git
