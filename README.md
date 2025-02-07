# SAE-Geoquizz
SAE geoquizz

## Membres du groupe :
- [Etique Kevin](https://github.com/EtiqueKevin)
- [Netange Clément](https://github.com/clem-png)
- [Quilliec Amaury](https://github.com/Aliec-AQ)
- [Bruson Paul](https://github.com/Dr-J-Watson)

## Instalation :

Par micro services :

- api-auth
  - ini : auth.db.ini
  - env : auth.env / authdb.env
  - composer : api-auth/app
- api-geoquizz
  - ini : geoquizz.db.ini / geoquizz.rabbitmq.ini
  - env : geoquizz.env / geoquizz.db.env
  - composer : api-geoquizz/app
- api-map
  - env : directus.env /directusDB.env
- front-end
  - env : .env
- gateway
  - composer : gateway/app
- mail
  - env : mail.env
  - composer : mail/
 
## Répartition des taches :

|  |  |
|--------------|--------|
| Frontend | Amaury QUILLIEC |
| Backend | Clément NETANGE / Kévin ETIQUE |
| Directus/db/infrastructure/déployement | Paul BRESON |

## Routes :

### api-auth :

> POST /signin[/]
>
>> Authentifie un utilisateur
>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Basic |  | email et mot de passe |

--------------------------------------

> POST /register[/]
>
>> Creation de compte
>>
>> Body :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| email | string | email  |
| mdp | string | mot de passe  |

--------------------------------------

> GET /users/mail[/]

> [!WARNING]
> Pas disponible depuis la gateway, uniquement pour des requetes internes aux services

>> Récupération d'un mail utilsiateur grace à un id d'utilisateur
>>
>> Query :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| playerid | string | id de l'utilisateur playerId ou userId  |

--------------------------------------

> POST /token/refresh[/]
>
>> permet de refresh un token
>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | refresh token |

--------------------------------------

> POST /token/validate[/]
>
>> permet de valider un token
>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

--------------------------------------

> GET /token/playerID[/]

> [!WARNING]
> Pas disponible depuis la gateway, uniquement pour des requetes internes aux services

>
>> permet de récupérer l'id d'un utilisateur à partir de son token de connexion
>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

### api-geoquizz :

--------------------------------------

> GET /game[/]
>
>> permet de renvoyer la game grace au token de la game
>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | Gametoken |

--------------------------------------

> POST /game[/]

> [!IMPORTANT]
> Il faut etre connecté pour crée une game

>
>> permet de creer la game
>>
>> Query :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| idserie |  | id de la série | 

>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

--------------------------------------

> PUT /game[/]

> [!IMPORTANT]
> Il faut un token de game

>
>> permet de finir une game
>>
>> Query :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| score |  | score de la partie | 

>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | Gametoken |

--------------------------------------

> GET /sequences/public[/]
>
>> permet de récupérer toutes les sequences public
>>

--------------------------------------

> POST /sequences/replay[/]

> [!NOTE]
> Vous pouvez etre connecté ou etre anonyme

>> permet de rejouer une sequence à partir de son id
>>
>> Query :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| idSequence |  | id de la sequence à replay| 

>> **OPTIONNEL** En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

--------------------------------------

> PUT /sequences/{idSequence}/status[/]

> [!IMPORTANT]
> Vous devez etre connecté

>> permet de mettre en public une sequence
>>
>> Arguments :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| idSequence |  | id de la sequence à mettre en public| 

>> 
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

--------------------------------------

> GET /users/games[/]

> [!IMPORTANT]
> Vous devez etre connecté

>> permet de renvoyé l'historique des games d'un utilisateur
>> 
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |


--------------------------------------

> POST /players[/]

> [!WARNING]
> Pas disponible depuis la gateway, uniquement pour des requetes internes aux services

>> permet de créer un player lors de la création d'un utiliateur
>> 
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |

--------------------------------------

> PUT /series/{id}/highscore[/]

> [!IMPORTANT]
> Vous devez etre connecté

>> permet de récupérer son le highscore par rapport à une série une série
>>
>> Arguments :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| id |  | id du theme| 

>>
>> En-tête de la requête (Header) :

| nom attribut | type   | description                   |
|--------------|--------|-------------------------------|
| Authorization Bearer |  | token |
