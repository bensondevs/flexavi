## Workday Cost

-------------------------------------------------------
### 1. Populate Workday Costs
-------------------------------------------------------

**Description**

This endpoint will populate data of workday's costs. 

**Endpoint:** `/api/dashboard/companies/workdays/costs`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `workday_id` | Required | uuid, string uuid, exists in table `workdays` | The workday id
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`costs` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "costs": {
        "current_page": 1,
        "data": [
            {
                "id": "e3437a50-1edf-11ec-925a-d3d96e0e55ef",
                "cost_name": "Example Cost",
                "amount": 10000,
                "paid_amoun": 0,
                "unpaid_amount": 0
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
        "to": 1,
        "total": 1
    }
}
```

-------------------------------------------------------
### 2. Store Cost and Record to Workday
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/costs/store_record`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`workday_id` or `id` | Required | string, uuid, exists in `workdays` table | The workday target to be attached with created costs

**Request Body Example:**

```json
{
    "workday_id": "15b9a890-1e90-11ec-a2a5-0705de5c2e1c",
    "cost_name": "Example cost name",
    "amount": 10000,
    "paid_amount": 0
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | Array | Store workday cost and record workday cost statuses
`message` | Array | Message response for the user


**Success Response Example:**

```json
{
    "status": [
        "success",
        "success"
    ],
    "message": [
        "Successfully save cost.",
        "Successfully record cost in workday."
    ]
}
```

-------------------------------------------------------
### 3. Record Workday Cost
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/costs/record`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of updated workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update WorkdayCost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save workdaycost."
}
```

-------------------------------------------------------
### 4. Delete WorkdayCost
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
`id` | Required | string | ID of deleted workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete workdaycost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete workdaycost"
}
```

-------------------------------------------------------
### 5. Restore Workday Cost
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
`id` | Required | string | ID of updated workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore workdaycost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore workdaycost."
}
```