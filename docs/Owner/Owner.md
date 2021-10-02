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
                "id": "c8846360-c922-11eb-ada5-1fae3baae23b",
                "user": {
                    "id": "c43d7c80-c922-11eb-9f02-9b031a502c59",
                    "fullname": "Flexavi Owner 1",
                    "salutation": "Mr.",
                    "birth_date": "1997-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "379218581",
                    "phone": "999999999999",
                    "phone_verified_at": null,
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
                "bank_holder_name": "Flexavi Owner 1",
            },
            {
                "id": "cfdbe8d0-c922-11eb-b50d-6b48abca3839",
                "user": null,
                "is_prime_owner": false,
                "bank_name": "Invited Bank",
                "bic_code": "001",
                "bank_account": "1010101010",
                "bank_holder_name": "Invited User",
            },
            {
                "id": "d5d62150-c925-11eb-8487-d73d292448de",
                "user": null,
                "is_prime_owner": false,
                "bank_name": "Added Banks",
                "bic_code": "911",
                "bank_account": "9988776655",
                "bank_holder_name": "Added Holder",
                "address": "Another street",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "11178",
                "city": "Another City",
                "province": "Another Province"
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
        "to": 3,
        "total": 3
    }
}
```

-------------------------------------------------------
### 2. Populate Company Inviteable Owners
-------------------------------------------------------


**Endpoint:** `/api/dashboard/companies/owners/inviteables`

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
                "id": "cfdbe8d0-c922-11eb-b50d-6b48abca3839",
                "user": null,
                "is_prime_owner": false,
                "bank_name": "Invited Bank",
                "bic_code": "001",
                "bank_account": "1010101010",
                "bank_holder_name": "Invited User",
            },
            {
                "id": "d5d62150-c925-11eb-8487-d73d292448de",
                "user": null,
                "is_prime_owner": false,
                "bank_name": "Added Banks",
                "bic_code": "911",
                "bank_account": "9988776655",
                "bank_holder_name": "Added Holder",
                "address": "Another street",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "11178",
                "city": "Another City",
                "province": "Another Province"
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
        "to": 3,
        "total": 3
    }
}
```

-------------------------------------------------------
### 3. Store Owner
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
### 4. View Owner
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/owners/view`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`with_user` | Optional | boolean, boolean string | Set this attribute to `true` to load owner with its relation of user. By default, this attribute will be set into `false`
`with_company` | Optional | boolean, boolean string | Set this attribute to true to load owner with its relation of user. By default, this attribute will be set into `true`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`owner` | Object | Object of owner data

**Success Response Example:**

```json
{
    "owner": {
        "id": "14cf92f0-1e90-11ec-acde-0f8ec6f25009",
        "user_id": "0ff1f6e0-1e90-11ec-99b5-e1aad2849cc2",
        "is_prime_owner": true,
        "bank_name": "FLEXAVIBANK",
        "bic_code": "9213",
        "bank_account": "83271221",
        "bank_holder_name": "Flexavi Owner 1",
        "addresses": [
            {
                "address": "Flexavi Owner 1 Address 1",
                "house_number": "724",
                "house_number_suffix": "X",
                "zipcode": "639513",
                "city": "Randon City",
                "province": "Random Province"
            }
        ],
        "company": {
            "id": "14cf8a90-1e90-11ec-9306-1b0a7e4fbbf3",
            "company_name": "Company 1",
            "email": "company1@flexavi.com",
            "phone_number": "9894778",
            "vat_number": "46698864",
            "commerce_chamber_number": "37",
            "company_logo_url": "http://localhost:8000/storage/uploads/companies/logos/20210730125714.jpeg",
            "company_website_url": "www.randomwebsite.com",
            "visiting_address": {
                "city": "Random City",
                "street": "Custom Road",
                "zip_code": "67312",
                "house_number": 207,
                "house_number_suffix": "X"
            },
            "invoicing_address": {
                "city": "Random City",
                "street": "Custom Street",
                "zip_code": "65123",
                "house_number": 37,
                "house_number_suffix": "X"
            }
        }
    }
}
```

**Success Response Notes:**

1. Owners can possibly have more than one address.
2. To add the address of owner, we can 

-------------------------------------------------------
### 5. Update Owner
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
### 6. Delete Owner
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