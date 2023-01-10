# PHP REST API

## Endpoints

| URL             | HTTP method | Auth | JSON Response                |
|-----------------|-------------|------|------------------------------|
| ?users          |    GET      |  Y   |    get users                 |
| ?users          |    POST     |      |    user login                |
| ?users          |    PUT      |      |    user register             |
| ?user=id        |    GET      |  Y   |    list user by id           |
| ?user           |    DELETE   |  Y   |    delete user by id         |
| ?user           |    PATCH    |  Y   |    edit user by id           |
| ?posts          |    GET      |      |    list all posts            |
| ?posts          |    PUT      |  Y   |    add new post              |
| ?stats          |    GET      |  Y   |    list general stats        |
| ?chat           |    GET      |  Y   |    list messages from a chat |
| ?chat           |    POST     |  Y   |    list user's chat partners |
| ?chat           |    PUT      |  Y   |    add new chat message      |