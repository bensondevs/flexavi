## Owner

-------------------------------------------------------
### 1. Populate Company Owners
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/owners`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`owners` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "owners": {
        "current_page": 1,
        "data": [
            {
                "id": "59324cb0-c819-11eb-bb0a-23892e37ab27",
                "user": {
                    "id": "5509f450-c819-11eb-b99e-1d7a7bdf5522",
                    "fullname": "Flexavi Owner 1",
                    "salutation": "Mr.",
                    "birth_date": "1999-06-08",
                    "id_card_type": "id_card",
                    "id_card_number": "281492675",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "address": "11, A Road Name",
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "owner1@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "is_prime_owner": true,
                "bank_name": "FLEXAVIBANK",
                "bic_code": "9213",
                "bank_account": "83271221",
                "bank_holder_name": "Flexavi Owner 1"
            },
            {
                "id": "77e48ce0-c82e-11eb-ba37-c990286d2c1c",
                "user": null,
                "is_prime_owner": false,
                "bank_name": "Added Bank",
                "bic_code": "911",
                "bank_account": "9988776655",
                "bank_holder_name": "Added Holder"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "/?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "/",
        "per_page": 10,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}
```

-------------------------------------------------------
### 2. Store Owner
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/owners/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`bank_name` | Required | string | Bank name used by the owner
`bic_code` | Required | string | Bank international code used by the owner
`bank_account` | Required | string | Bank account of the user
`bank_holder_name` | Required | string | Bank holder name

**Request Body Example:**

```json
{
    "bank_name": "FLEXAVIBANK",
    "bic_code": "011",
    "bank_account": "019191919",
    "bank_holder_name": "Bank Holder Name",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`owner` | Object | Object of owner data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "owner": {
        "bank_name": "Added Bank",
        "bic_code": "911",
        "bank_account": "9988776655",
        "bank_holder_name": "Added Holder",
        "company_id": "86e64050-c860-11eb-91e2-3927f7252fca",
        "id": "e8f80c40-c864-11eb-9d13-d1ff39c7c8eb",
        "updated_at": "2021-06-08T14:22:09.000000Z",
        "created_at": "2021-06-08T14:22:09.000000Z"
    },
    "status": "success",
    "message": "Successfully save owner."
}
```

-------------------------------------------------------
### 3. Update Owner
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/owners/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of updated owner
`bank_name` | Required | string | Bank name used by the owner
`bic_code` | Required | string | Bank international code used by the owner
`bank_account` | Required | string | Bank account of the user
`bank_holder_name` | Required | string | Bank holder name

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "bank_name": "FLEXAVIBANK",
    "bic_code": "011",
    "bank_account": "019191919",
    "bank_holder_name": "Bank Holder Name",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`owner` | Object | Object of owner data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "owner": {
        "id": "e8f80c40-c864-11eb-9d13-d1ff39c7c8eb",
        "is_prime_owner": false,
        "user_id": null,
        "company_id": "86e64050-c860-11eb-91e2-3927f7252fca",
        "bank_name": "Added Banks",
        "bic_code": "911",
        "bank_account": "9988776655",
        "bank_holder_name": "Added Holder",
        "created_at": "2021-06-08T14:22:09.000000Z",
        "updated_at": "2021-06-08T14:22:46.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully save owner."
}
```

-------------------------------------------------------
### 4. Delete Owner
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/owners/delete`

**Method:** `DELETE`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of deleted owner

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete owner from company"
}
```