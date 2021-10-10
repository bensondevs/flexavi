## Worklist Costs

-------------------------------------------------------
### 1. Populate Worklist Cost
-------------------------------------------------------

**Endpoint:** `/api/companies/worklists/costs`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string, uuid | Worklist ID of a cost
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
                "id": "47686de0-2991-11ec-ad94-f57c23990469",
                "cost_name": "Worklist Cost Seeder #2",
                "amount": 2000,
                "paid_amoun": 400,
                "unpaid_amount": 1600
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
### 2. Store Cost and Record to Worklist
-------------------------------------------------------

**Endpoint:** `/api/companies/worklists/costs/store_record`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`cost_name` | Required | String | The cost name, explanation about the cost.
`amount` | Required | double, numeric | The amout of cost.
`worklist_id` | Required, string, uuid | exists in `worklists` | The worklist to be attached with new cost.
`paid_amount` | Optional, default: 0 | double, numeric | The paid amount of certain cost.
`record_in_workday` | Optional | boolean, boolean string | Set this to false, and the newly stored cost will not be recorded to parent.

**Request Body Example:**

```json
{
    "cost_name": "Cost Name Example",
    "amount": 90000,
    "paid_amount": 1000,
    "worklist_id": "0aafba20-2991-11ec-a103-6d36f3e35b1f"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | Array | Save and Record worklist cost status.
`message` | Array | Message response for the user.


**Success Response Example:**

```json
{
    "status": [
        "success",
        "success"
    ],
    "message": [
        "Successfully save cost.",
        "Successfully record cost in worklist."
    ]
}
```

-------------------------------------------------------
### 3. Record Cost to Worklist
-------------------------------------------------------

**Note:** 

This endpoint will record a cost to a worklist. Recording a cost to a worklist will make the calculation of worklist will be having a record of recorded cost.
This endpoint is not creating new cost, just record the existing cost to a worklist.

**Endpoint:** `/api/companies/worklists/costs/record`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string, uuid | ID of target worklist.
`cost_id` | Required | string, uuid | ID of to be recorded cost.

**Request Body Example:**

```json
{
    "worklist_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "cost_id": "0e03d010-2991-11ec-8fed-f917954e55f9"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update WorklistCost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully record cost in worklist."
}
```

-------------------------------------------------------
### 4. Unrecord Cost from Worklist
-------------------------------------------------------

**Note:** 

This endpoint will not directly delete a cost. The cost will be unrecorded from a worklist, this means that the cost is no longer belongs to the worklist. 

Only if the cost has no longer recorded at any `costables` like `Workday`, `Worklist`, `Appointment` then the cost will be automatically deleted.

**Endpoint:** `/api/companies/worklists/costs/unrecord`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string, uuid | ID of target worklist.
`cost_id` | Required | string, uuid | ID of to be recorded cost.

**Request Body Example:**

```json
{
    "worklist_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "cost_id": "0e03d010-2991-11ec-8fed-f917954e55f9"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete worklistcost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully unrecord cost in worklist."
}
```

-------------------------------------------------------
### 5. Record Many Cost to Worklist
-------------------------------------------------------

**Endpoint:** `/api/companies/worklists/costs/record_many`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of updated worklist
`cost_ids` | Required | array, array string

**Request Body Example:**

```json
{
    "worklist_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "cost_ids": ["0e03d010-2991-11ec-8fed-f917954e55f9", "0e03d010-2991-11ec-8fed-f917954e55f9"]
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore worklistcost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully record many costs in worklist."
}
```

-------------------------------------------------------
### 6. Unrecord Many Cost to Worklist
-------------------------------------------------------

**Endpoint:** `/api/companies/worklists/costs/unrecord_many`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of updated worklist
`cost_ids` | Required | array, array string

**Request Body Example:**

```json
{
    "worklist_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "cost_ids": ["0e03d010-2991-11ec-8fed-f917954e55f9", "0e03d010-2991-11ec-8fed-f917954e55f9"]
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully unrecord many costs in worklist."
}
```

-------------------------------------------------------
### 7. Truncate Worklist Costs
-------------------------------------------------------

**Endpoint:** `/api/companies/worklists/costs/truncate`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`


**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of updated worklist

**Request Body Example:**

```json
{
    "worklist_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution  status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully unrecord all costs within worklist"
}
```