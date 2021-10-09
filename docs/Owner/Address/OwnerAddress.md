## Owner Address

-------------------------------------------------------
### 0. About
-------------------------------------------------------

Flexavi allows some instances to have more than one address.
The instances that could possibly posses the addresses are `Customer`, `Owner`, `Owner` and `Company`.

The address has a type, which described in [Meta](/docs/Meta/Address.md). But there is a special treatment for a type which named `Other`, if this type selected, the insertion of data should also posses `other_address_type_description`. This parameter allows the possesor of the address to name the address according to their needs.

-------------------------------------------------------
### 1. Populate Owner Addresses
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/addresses/owner`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`owner_id` | Required | string | The Owner ID who posses the address
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
### 2. Store Owner Address
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/addresses/owner/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`owner_id` | Required | string | The Owner ID who will posses the address
`address_type` | Required | numeric, numeric string | This is the enum type value for the type of address existed. To see the details please see [Address Meta](/docs/Meta/Address.md)
`address` | Required | string, text | The address
`house_number` | Required | numeric, numeric string | The house number of the address
`house_number_suffix` | Optional | string | Some houses or addresses have suffix in the back of their number for an example `A`, `B` or etc, this will allow insertion of the suffix if there is any.
`zipcode` | Required | numeric, numeric string | The zipcode of address
`city` | Required | string | The city of address
`province` | Required | string | The string of address

**Request Body Example:**

```json
{
    "address_type": 1,
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
### 3. Update Company Address
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/addresses/owner/update`

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
### 4. Delete Company Address
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/addresses/delete`

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
### 5. Restore Company Address
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/addresses/restore`

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