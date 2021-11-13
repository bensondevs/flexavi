## Address

-------------------------------------------------------
### 0. About
-------------------------------------------------------

This section is going to describe you about the flow endpoints of the addresses.

- Each `addresses` will have [morph relationship](https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-polymorphic-relations) which the address is the child of the relationship.

- 

-------------------------------------------------------
### 1. Populate Company Addresses
-------------------------------------------------------

**Description:** 

This endpoint will give a list of current authenticated user"s addresses.

**Endpoint:** `/api/dashboard/companies/addresses`

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
`address` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "addresses": {
        "current_page": 1,
        "data": [
            {
                "id": "24840560-23a5-11ec-9d9b-35de8c179fe4",
                "address_type": 1,
                "address_type_description": "Visiting Address",
                "address": "Example address 123",
                "house_number": "12",
                "house_number_suffix": "X",
                "zipcode": "123510",
                "city": "City Example",
                "province": "Province Example"
            },
            {
                "id": "db14a210-239a-11ec-8342-25d516888e89",
                "address_type": 2,
                "address_type_description": "Invoicing Address",
                "address": "Company 1 Address ",
                "house_number": "274",
                "house_number_suffix": "X",
                "zipcode": "496154",
                "city": "Randon City",
                "province": "Random Province"
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
### 2. Store Address
-------------------------------------------------------

**Endpoint:** `/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------


**Request Body Example:**

```json
{
    "address_type": 1,
    "addressable_type": 3,
    "other_address_type_description": "Home Address",

    "address": "Example address 123",
    "house_number": 12,
    "house_number_suffix": "X",
    "zipcode": 123510,
    "city": "City Example",
    "province": "Province Example",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update address status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save address."
}
```

-------------------------------------------------------
### 3. Update Address
-------------------------------------------------------

**Endpoint:** `/update`

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
`id` | Required | string | ID of updated address

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    
    "address_type": 1,
    "addressable_type": 3,
    "other_address_type_description": "Home Address",

    "address": "Example address 123",
    "house_number": 12,
    "house_number_suffix": "X",
    "zipcode": 123510,
    "city": "City Example",
    "province": "Province Example",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Address status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save address."
}
```

-------------------------------------------------------
### 4. Delete Address
-------------------------------------------------------

**Endpoint:** `/delete`

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
`id` | Required | string | ID of deleted address

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete address status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete address"
}
```

-------------------------------------------------------
### 5. Restore Address
-------------------------------------------------------

**Endpoint:** `/restore`

**Method:** `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of updated address

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore address status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore address."
}
```