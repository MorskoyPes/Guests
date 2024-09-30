# Микросервис для работы с гостями

Этот микросервис предоставляет API для управления гостями (создание, получение, обновление и удаление записей). 

## Технологии

- PHP 8.2
- Symfony 7.1
- Docker
- PostgreSQL (в качестве базы данных)

## Установка и запуск

Склонировать репозиторий:
   ```bash
   git clone https://github.com/MorskoyPes/Guests.git
```
Зайти в директорию с проектом и запустить сборку контейнеров, запустить миграции:
    
    
    docker-compose up --build -d
    docker-compose exec php bin/console doctrine:migrations:migrate

## Тесты запускаются внутри контейнера:
   ```bash
   php bin/phpunit
```
## Документация API Swagger:

http://localhost:8080/api/doc


### 1. Создание гостя

**URL:** `/guest/new`  
**Метод:** `POST`  
**Описание:** Создает нового гостя в системе.

#### Пример запроса:
```json
{
  "firstName": "Ivan",
  "lastName": "Ivanov",
  "phone": "+79210000000",
  "email": "ivan@example.com",
  "country": "Russia"
}
```

#### Ответ при успешном создании:
- **Код ответа:** `201 Created`
- **Тело ответа:**
```json
{
  "id": 1,
  "message": "Guest created successfully"
}
```

### 2. Получение списка гостей

**URL:** `/guest`  
**Метод:** `GET`  
**Описание:** Возвращает список всех гостей.

#### Пример ответа:
- **Код ответа:** `200 OK`
- **Тело ответа:**
```json
[
  {
    "id": 1,
    "firstName": "Ivan",
    "lastName": "Ivanov",
    "phone": "+79210000000",
    "email": "ivan@example.com",
    "country": "Russia"
  },
  {
    "id": 2,
    "firstName": "Petr",
    "lastName": "Petrov",
    "phone": "+79210000001",
    "email": "petr@example.com",
    "country": "Russia"
  }
]
```

### 3. Получение гостя по ID

**URL:** `/guest/{id}`  
**Метод:** `GET`  
**Описание:** Возвращает данные гостя по его идентификатору.

#### Пример запроса:
```
GET /guest/1
```

#### Ответ при успешном запросе:
- **Код ответа:** `200 OK`
- **Тело ответа:**
```json
{
  "id": 1,
  "firstName": "Ivan",
  "lastName": "Ivanov",
  "phone": "+79210000000",
  "email": "ivan@example.com",
  "country": "Russia"
}
```

### 4. Обновление данных гостя

**URL:** `/guest/{id}/edit`  
**Метод:** `PUT`  
**Описание:** Обновляет информацию о госте.

#### Пример запроса:
```json
{
  "firstName": "Alexey",
  "lastName": "Smirnov",
  "phone": "+79210000002",
  "email": "alexey@example.com",
  "country": "Russia"
}
```

#### Ответ при успешном обновлении:
- **Код ответа:** `200 OK`
- **Тело ответа:**
```json
{
  "id": 1,
  "message": "Guest updated successfully"
}
```

### 5. Удаление гостя

**URL:** `/guest/{id}`  
**Метод:** `DELETE`  
**Описание:** Удаляет гостя из системы.

#### Пример запроса:
```
DELETE /guest/1
```

#### Ответ при успешном удалении:
- **Код ответа:** `204 No Content`
- **Тело ответа:** пустое


## В ответах сервера присутствуют два заголовка:

X-Debug-Time: указывает, сколько миллисекунд занял запрос.

X-Debug-Memory: показывает количество памяти (в Кб), использованной для выполнения запроса.

