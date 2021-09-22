## Worklist

-------------------------------------------------------
### 1. Populate Company Worklists
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/`

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
`with_appointments` | Optional | boolean, boolean string | Set this value to `true` will load the worklist that has property of `appointments`, this property has type of Array that contains appointments inside the worklist

**Request Body Example:**

```json
{
    "workday_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "page": 2,
    "per_page": 10,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`owners` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "worklists": {
        "current_page": 1,
        "data": [
            {
                "id": "c1af1af0-f606-11eb-85bd-05da89e09083",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7160-f606-11eb-ad20-eb66e242cb72",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af5780-f606-11eb-8a80-13cda2ee8ab2",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7580-f606-11eb-83de-c98858b134df",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af5e20-f606-11eb-9bda-198e52ae336e",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7890-f606-11eb-bf44-1759fd10d33f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6240-f606-11eb-8a63-b5891e2d295a",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 3,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6630-f606-11eb-b0b0-793e6a187f74",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 2,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6940-f606-11eb-83db-f56a41337ab7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 3",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 0,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af6b90-f606-11eb-9bf0-591c279e36c7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af72c0-f606-11eb-a63c-fb61168542bf",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af7660-f606-11eb-92ce-097d47e358db",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e8080-f606-11eb-8c8c-7b65fedb41fd",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
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
        "to": 9,
        "total": 9
    }
}
```

-------------------------------------------------------
### 2. Populate Workday Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/of_workday`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`workday_id` | Required | string | Workday ID of the populated worklists
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Request Body Example:**

```json
{
    "workday_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "page": 2,
    "per_page": 10,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`owners` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "worklists": {
        "current_page": 1,
        "data": [
            {
                "id": "c1af1af0-f606-11eb-85bd-05da89e09083",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7160-f606-11eb-ad20-eb66e242cb72",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af5780-f606-11eb-8a80-13cda2ee8ab2",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7580-f606-11eb-83de-c98858b134df",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af5e20-f606-11eb-9bda-198e52ae336e",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7890-f606-11eb-bf44-1759fd10d33f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6240-f606-11eb-8a63-b5891e2d295a",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 3,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6630-f606-11eb-b0b0-793e6a187f74",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 2,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6940-f606-11eb-83db-f56a41337ab7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 3",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 0,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af6b90-f606-11eb-9bf0-591c279e36c7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af72c0-f606-11eb-a63c-fb61168542bf",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af7660-f606-11eb-92ce-097d47e358db",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e8080-f606-11eb-8c8c-7b65fedb41fd",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
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
        "to": 9,
        "total": 9
    }
}
```

-------------------------------------------------------
### 2. Populate Trashed Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/trasheds`

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

**Request Body Example:**

```json
{
    "workday_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "page": 2,
    "per_page": 10,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`owners` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "worklists": {
        "current_page": 1,
        "data": [
            {
                "id": "c1af1af0-f606-11eb-85bd-05da89e09083",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7160-f606-11eb-ad20-eb66e242cb72",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af5780-f606-11eb-8a80-13cda2ee8ab2",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7580-f606-11eb-83de-c98858b134df",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af5e20-f606-11eb-9bda-198e52ae336e",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7890-f606-11eb-bf44-1759fd10d33f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6240-f606-11eb-8a63-b5891e2d295a",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 3,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6630-f606-11eb-b0b0-793e6a187f74",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 2,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af6940-f606-11eb-83db-f56a41337ab7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "worklist_name": "Worklist Name 3",
                "status": 1,
                "status_description": "Prepared",
                "total_appointments": 0,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z"
            },
            {
                "id": "c1af6b90-f606-11eb-9bf0-591c279e36c7",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04",
                "calculated_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af72c0-f606-11eb-a63c-fb61168542bf",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
            },
            {
                "id": "c1af7660-f606-11eb-92ce-097d47e358db",
                "company_id": "c0487e20-f606-11eb-a067-8b56900624d0",
                "workday_id": "c12e8080-f606-11eb-8c8c-7b65fedb41fd",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "total_appointments": 1,
                "created_at": "2021-08-05T16:04:04.000000Z",
                "updated_at": "2021-08-05T16:04:04.000000Z",
                "processed_at": "2021-08-05 18:04:04"
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
        "to": 9,
        "total": 9
    }
}
```

-------------------------------------------------------
### 2. Store Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`workday_id` | Required | Exists in `workdays` by matching `id` | The workday that's going to contain the created worklist
`worklist_name` | Required | string | The name of worklist


**Request Body Example:**

```json
{
    "workday_id": "c12e7160-f606-11eb-ad20-eb66e242cb72",
    "worklist_name": "Worklist from Home"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update Worklist status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save Worklist."
}
```

-------------------------------------------------------
### 3. Update Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/update`

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
`id` | Required | string | ID of updated worklist
`worklist_name` | Required | string | The name of worklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "worklist_name": "The new name for worklist",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Worklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save Worklist."
}
```

-------------------------------------------------------
### 3. Process Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/process`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `worklist_id` | Required | string | ID of updated worklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of processing Worklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully process Worklist."
}
```

-------------------------------------------------------
### 4. Calculate Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/calculate`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `worklist_id` | Required | string | ID of updated worklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Worklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully calculate Worklist."
}
```

-------------------------------------------------------
### 5. Delete Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/delete`

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
`id` | Required | string | ID of deleted Worklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete Worklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete Worklist"
}
```

-------------------------------------------------------
### 6. Restore Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/restore`

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
`id` | Required | string | ID of updated Worklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore Worklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore worklist."
}
```