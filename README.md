```Imabiru``` - imageboard на Laravel. Сделан просто так.

Используемые зависимости:

1. Laravel sail;
2. Laravel pint;
3. Laravel sanctum;
4. Larastan;
5. Pest;
6. Intervention image

# Примеры запросов и ответы

Каждый запрос должен принимать `Header`, для получения данных в формате json:

```
Accept — application/json
```

## Auth

### Register
```
POST api/v1/auth/register
```

Регистрирует пользователя.

После регистрации необходимо подтвердить почту.

Принимает:
* name — имя, мин. 6, макс, 48, уникальное, обязательно
* email — почта, уникальное, обязательно
* password — пароль, базовые правила для пароля, обязательно
* password_confirmation — подтверждение пароля, обязательно

Возвращает:
```json
{
    "token": "---token",
    "refreshToken": "---refresh-token"
}
```

### Login

```
POST api/v1/auth/login
```

Авторизация.

Принимает:
* email — почта, обязательно
* password — пароль, обязательно

Возвращает:
```json
{
    "token": "---token",
    "refreshToken": "---refresh-token"
}
```

### Refresh token

```
POST api/v1/auth/refresh-token
```

Возвращает новый токен.

Принимает:
* Refresh Token

Возвращает:
```json
{
    "token": "---token",
    "refreshToken": "---refresh-token"
}
```

## Users

### Update Email

```
PUT api/v1/users/update-email
```

Требует токен.

Обновляет email пользователя.

Принимает:
* Email — уникальное, обязательно.

Возвращает:
```json
{
    "data": {
        "id": 41,
        "name": "Крутой сигма",
        "email": "vip.sigma@shock.com",
        "created_at": "2024-07-23T21:30:58.000000Z"
    }
}
```

### Update Password

```
PUT api/v1/users/update-password
```

Требует токен.

Обновляет пароль пользователя.

Принимает:
* oldPassword — обязательно, строка, текущий пароль.
* newPassword — пароль, базовые правила для пароля, обязательно
* newPassword_confirmation — подтверждение пароля, обязательно


Возвращает:

Пустое.

Статус 200 (OK)


## Users

### Get users

```
GET api/v1/users
```

Возвращает список пользователей.

Принимает:
* page - номер страницы, необязательно.
* search - поиск по имени пользователя, необязательно, строка, мин 4. макс. 48

Возвращает:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Крутой сигма",
            "image": {
                "id": 1,
                "imageName": "http://localhost/storage/images/users/user-1-1722177061.jpg",
                "imageNameScaled": "http://localhost/storage/images/users/user-1-1722177061-scaled.jpg"
            }
        }
    ]
}
```

### Get user by id

```
GET api/v1/users/{id}
```

Где id - id пользователя

Получение конкретного пользователя

Возвращает:

```json
{
    "data": {
        "id": 1,
        "name": "Крутой сигма",
        "email": "vipshockshock@gmail.com",
        "image": {
            "id": 1,
            "imageName": "http://localhost/storage/images/users/user-1-1722177061.jpg",
            "imageNameScaled": "http://localhost/storage/images/users/user-1-1722177061-scaled.jpg"
        },
        "created_at": "2024-07-28T14:29:04.000000Z"
    }
}
```

### Upload user image

```
POST api/v1/users/upload-image
```
Обновляет фотографию пользователя

Возвращает:
```json
{
    "data": {
        "id": 9,
        "imageName": "http://localhost/storage/images/users/user-1-1722178176.jpg",
        "imageNameScaled": "http://localhost/storage/images/users/user-1-1722178176-scaled.jpg"
    }
}
```

### Delete user image

```
DELETE api/v1/users/delete-image
```

Удаляет фотографию пользователя


## Posts

### Как-нибудь потом...
