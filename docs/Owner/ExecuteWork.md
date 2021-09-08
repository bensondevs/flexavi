## ExecuteWork

**Description**

This endpoint is like progress tracker for work. Say thet we have a work. We can execute it and add the definition for each process. Each process will be tracked through each execute work existed here.

Each execute work will have photos that records about before and after work. This will allow the owner of the company to see clearly about the work execution.

-------------------------------------------------------
### 1. Populate Execute Work
-------------------------------------------------------

**Endpoint:** `/`

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
`executework` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "execute_works": {
        "current_page": 1,
        "data": [
            {
                "id": "a7d1d410-0c64-11ec-b189-03c803dc4caf",
                "company_id": "994233a0-0c64-11ec-b54d-c9a0b169441a",
                "appointment_id": "9b294cd0-0c64-11ec-ac20-abf4dc81ffcc",
                "work_id": "a4814770-0c64-11ec-a160-b19178a4ba46",
                "is_finished": null,
                "is_continuation": null,
                "status": 1,
                "status_description": "In process",
                "previous_execute_work_id": null,
                "note": "This is seeder execute work 1"
            },
            {
                "id": "a7d1d8d0-0c64-11ec-84ae-950ab04434f9",
                "company_id": "994233a0-0c64-11ec-b54d-c9a0b169441a",
                "appointment_id": "9b294cd0-0c64-11ec-ac20-abf4dc81ffcc",
                "work_id": "a4814770-0c64-11ec-a160-b19178a4ba46",
                "is_finished": null,
                "is_continuation": null,
                "status": 2,
                "status_description": "Finished",
                "previous_execute_work_id": null,
                "note": "This is seeder execute work 2",
                "finish_note": null
            },
            {
                "id": "a7d1dc90-0c64-11ec-974d-bf5b152b2a53",
                "company_id": "994233a0-0c64-11ec-b54d-c9a0b169441a",
                "appointment_id": "9b294cd0-0c64-11ec-ac20-abf4dc81ffcc",
                "work_id": "a4814770-0c64-11ec-a160-b19178a4ba46",
                "is_finished": null,
                "is_continuation": null,
                "status": 2,
                "status_description": "Finished",
                "previous_execute_work_id": null,
                "note": "This is seeder execute work 3",
                "finish_note": null
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
### 2. Execute Work
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
`work_id` | Required | string, uuid | Execution of work.
`appointment_id` | Required | string, uuid | Execution on any appointment.
`description` | Required | string | Description of execute work, the description of what work part.
`note` | Optional | string | Note about the work execution.

**Request Body Example:**

```json
{
    "work_id": "a4814770-0c64-11ec-a160-b19178a4ba46",
    "appointment_id": "9b292ef0-0c64-11ec-9293-8349219e6ad6",
    "description": "Roof fixing",
    "note": "Fixing the root for first phase."
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update executework status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save executework."
}
```

-------------------------------------------------------
### 3. Mark Finish Execute Work
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
`id` or `execute_work_id` | Required | string | ID of updated execute work.
`finish_note` | Required | string | Note about finished work.

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "finish_note": "Finished at fixing roof."
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update ExecuteWork status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save execute work."
}
```

-------------------------------------------------------
### 4. Delete Execute Work
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
`id` or `execute_work_id` | Required | string | ID of deleted executework

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete executework status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete execute work"
}
```

-------------------------------------------------------
### 5. Restore Execute Work
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
`id` | Required | string | ID of updated executework

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore executework status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore executework."
}
```