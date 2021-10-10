## Cost

-------------------------------------------------------
### 0. About
-------------------------------------------------------

According to main document, the company can do recording about the cost happen in each event such as `appointment`, `worklist` or event `workday`. Because of that `cost` instance can have relationships with more than one `costables` (eg: `Appointment`, `Worklist`, `Workday`).

Say that we have a cost that recorded to an `appointment` which attached into a `worklist`, when we record a cost to `appointment`, defaultly, it will also be attached to the parent in which in this case is `worklist`. This goes up to `workday` because a `worklist` is recorded under `workday`. But there is a configuration in API where the attachment to parent can be cancelled.

Each relationship with `costable` will be used to calculate the `costable` costs and revenues. Only the attached costs will be included in calculation. The unattached cost will be excluded from calculation even though the `cost` instance is still exists in database.

Defaultly, the costs that no longer have relationship with `costable` will be deleted forever.

-------------------------------------------------------
### 1. Populate Costs
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/costs`

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
`cost` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "costs": {
        "current_page": 1,
        "data": [
            {
                "id": "993a3fc0-12d9-11ec-a838-a1ce0eaabb3b",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a44d0-12d9-11ec-ac14-0d5de517b3ac",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a47a0-12d9-11ec-884a-c95703da4c84",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a4a40-12d9-11ec-9f94-e7827c21bbbe",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a4d90-12d9-11ec-88b4-eb562f4253ff",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a50c0-12d9-11ec-91dc-b35fc3456385",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a5350-12d9-11ec-9a5e-b353ca7c1db9",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a55e0-12d9-11ec-9c3e-0bea47b85ec3",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a5880-12d9-11ec-a8f8-d5010b2f29ae",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "993a5b10-12d9-11ec-8ed0-814e90307553",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 14,
        "last_page_url": "/?page=14",
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
                "url": "/?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "/?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "/?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "/?page=6",
                "label": "6",
                "active": false
            },
            {
                "url": "/?page=7",
                "label": "7",
                "active": false
            },
            {
                "url": "/?page=8",
                "label": "8",
                "active": false
            },
            {
                "url": "/?page=9",
                "label": "9",
                "active": false
            },
            {
                "url": "/?page=10",
                "label": "10",
                "active": false
            },
            {
                "url": null,
                "label": "...",
                "active": false
            },
            {
                "url": "/?page=13",
                "label": "13",
                "active": false
            },
            {
                "url": "/?page=14",
                "label": "14",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=2",
        "path": "/",
        "per_page": 10,
        "prev_page_url": null,
        "to": 10,
        "total": 139
    }
}
```

-------------------------------------------------------
### 2. Store Cost
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/costs/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`cost_name` | Required | 


**Request Body Example:**

```json
{
    "cost_name": "Store cost",
    "amount": 10000,
    "paid_amount": 8000
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update cost status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save cost."
}
```

-------------------------------------------------------
### 3. Update Cost
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
`id` | Required | string | ID of updated cost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Cost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save cost."
}
```

-------------------------------------------------------
### 4. Delete Cost
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
`id` | Required | string | ID of deleted cost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete cost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete cost"
}
```

-------------------------------------------------------
### 5. Restore Cost
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
`id` | Required | string | ID of updated cost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore cost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore cost."
}
```