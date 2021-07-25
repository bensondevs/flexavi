## User Meta

-------------------------------------------------------
### 1. ID Card Types
-------------------------------------------------------

**Endpoint:** `/api/meta/user/all_id_card_types`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "National ID Card",
    "2": "Passport",
    "3": "Driving License"
}
```

-------------------------------------------------------
### 2. Check Email Used
-------------------------------------------------------

**Endpoint:** `/api/meta/user/all_id_card_types`

**Method:** `GET`

Header Name | Value 
------------|--------------
Accept | `application/json`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`email` | Required | string, email | The email thats going to be checked in users table, when it does not exist, it will not be shown up. This check also includes DELETED user, because deleted user is still within the users table but set as DELETED.