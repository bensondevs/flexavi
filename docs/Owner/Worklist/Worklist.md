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
### 3. View Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/worklists/view`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `worklist_id` | Required | uuid, string | The target worklist ID

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`worklist` | Object | Worklist Data.

**Success Response Example:**

```json
{
    "worklist": {
        "id": "c995ffc0-651f-11ec-9c35-71a93553ff89",
        "company_id": "c6ef4840-651f-11ec-81a9-c71ba4116df8",
        "workday_id": "c8912600-651f-11ec-b4d8-d39bd772009c",
        "worklist_name": "Worklist Name 1",
        "status": 1,
        "status_description": "Prepared",
        "workday": {
            "id": "c8912600-651f-11ec-b4d8-d39bd772009c",
            "date": "2021-12-02",
            "status": 1,
            "status_description": "Prepared"
        },
        "appointments": [
            {
                "id": "ca2a4b30-651f-11ec-b5d3-194f0abe7e2f",
                "customer_id": "c7004dc0-651f-11ec-8bc8-117242fb494d",
                "status": 4,
                "status_description": "Calculated",
                "type": 1,
                "type_description": "Inspection",
                "start": "2021-12-25T01:12:55.000000Z",
                "end": "2021-12-29T01:12:55.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z",
                "in_process_at": "2021-12-27 02:12:55",
                "processed_at": "2021-12-26 02:12:55",
                "calculated_at": null
            },
            {
                "id": "ca2a6660-651f-11ec-bd7d-dff581d56d45",
                "customer_id": "c7004dc0-651f-11ec-8bc8-117242fb494d",
                "status": 1,
                "status_description": "Created",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-12-16T01:12:55.000000Z",
                "end": "2021-12-22T01:12:55.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z"
            },
            {
                "id": "ca2a6e10-651f-11ec-9331-d1c99a6b72e5",
                "customer_id": "c7004dc0-651f-11ec-8bc8-117242fb494d",
                "status": 5,
                "status_description": "Cancelled",
                "type": 2,
                "type_description": "Quotation",
                "start": "2022-01-01T01:12:55.000000Z",
                "end": "2022-01-07T01:12:55.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z",
                "in_process_at": "2021-12-26 02:12:55",
                "processed_at": "2021-12-27 02:12:55",
                "calculated_at": "2021-12-28 02:12:55",
                "cancelled_at": "2021-12-29 02:12:55",
                "cancellation_vault": 1,
                "cancellation_vault_description": "Roofer",
                "cancellation_cause": "Another cause no one knows",
                "cancellation_note": "Random cancellation note for appointment"
            },
            {
                "id": "ca2a7a50-651f-11ec-9dd1-d3c85a48467c",
                "customer_id": "c7004dc0-651f-11ec-8bc8-117242fb494d",
                "status": 1,
                "status_description": "Created",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-12-29T01:12:55.000000Z",
                "end": "2022-01-02T01:12:55.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z"
            },
            {
                "id": "ca2c1be0-651f-11ec-91af-ff8bc07bfef1",
                "customer_id": "c7010ea0-651f-11ec-b1c6-e9d8d6f6c722",
                "status": 2,
                "status_description": "In Process",
                "type": 1,
                "type_description": "Inspection",
                "start": "2021-12-25T01:12:55.000000Z",
                "end": "2021-12-28T01:12:55.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z",
                "in_process_at": null
            },
            {
                "id": "ca2c2310-651f-11ec-8785-7d2e4ec6adf3",
                "customer_id": "c7010ea0-651f-11ec-b1c6-e9d8d6f6c722",
                "status": 5,
                "status_description": "Cancelled",
                "type": 2,
                "type_description": "Quotation",
                "start": "2021-12-15T01:12:55.000000Z",
                "end": "2021-12-18T01:12:55.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z",
                "in_process_at": "2021-12-27 02:12:55",
                "processed_at": "2021-12-28 02:12:55",
                "calculated_at": "2021-12-28 02:12:55",
                "cancelled_at": "2021-12-28 02:12:55",
                "cancellation_vault": 1,
                "cancellation_vault_description": "Roofer",
                "cancellation_cause": "Another cause no one knows",
                "cancellation_note": "Random cancellation note for appointment"
            },
            {
                "id": "ca2c2f20-651f-11ec-b34c-6b3c09d65d19",
                "customer_id": "c7010ea0-651f-11ec-b1c6-e9d8d6f6c722",
                "status": 1,
                "status_description": "Created",
                "type": 1,
                "type_description": "Inspection",
                "start": "2021-12-30T01:12:55.000000Z",
                "end": "2022-01-02T01:12:55.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-12-25T01:12:55.000000Z"
            }
        ],
        "worklist_cars": [
            {
                "id": "245158e0-6520-11ec-af64-638cb58332b5",
                "worklist_id": "c995ffc0-651f-11ec-9c35-71a93553ff89",
                "car_id": "c87aece0-651f-11ec-a938-e7594d5b1ad8",
                "employee_in_charge_id": "c820e180-651f-11ec-933a-1da79016a064",
                "should_return_at": null,
                "returned_at": null
            }
        ],
        "appoint_employees": [
            {
                "id": "41b5d8c0-6520-11ec-8e91-63ff306157d6",
                "appointment_id": "ca2a4b30-651f-11ec-b5d3-194f0abe7e2f",
                "employee_id": "c8213450-651f-11ec-a985-cdb90a8ea0ff",
                "created_at": "2021-12-25T01:16:15.000000Z",
                "updated_at": "2021-12-25T01:16:15.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41b7d0c0-6520-11ec-9934-ddc5f5ae7f4c",
                "appointment_id": "ca2a6660-651f-11ec-bd7d-dff581d56d45",
                "employee_id": "c8212260-651f-11ec-bc60-55c132e1f72d",
                "created_at": "2021-12-25T01:16:15.000000Z",
                "updated_at": "2021-12-25T01:16:15.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41b8c3b0-6520-11ec-805f-bd92dd328814",
                "appointment_id": "ca2a6e10-651f-11ec-9331-d1c99a6b72e5",
                "employee_id": "c8211df0-651f-11ec-b5e0-3baeda8c1513",
                "created_at": "2021-12-25T01:16:15.000000Z",
                "updated_at": "2021-12-25T01:16:15.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41b9bce0-6520-11ec-be0f-41e99954b1ec",
                "appointment_id": "ca2a7a50-651f-11ec-9dd1-d3c85a48467c",
                "employee_id": "c8210240-651f-11ec-86f3-67d1de0ec67a",
                "created_at": "2021-12-25T01:16:15.000000Z",
                "updated_at": "2021-12-25T01:16:15.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41e676d0-6520-11ec-a2de-f9abc25ce84c",
                "appointment_id": "ca2c1be0-651f-11ec-91af-ff8bc07bfef1",
                "employee_id": "c820c7b0-651f-11ec-bb47-3ded5e116459",
                "created_at": "2021-12-25T01:16:16.000000Z",
                "updated_at": "2021-12-25T01:16:16.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41e77d10-6520-11ec-ac7f-1f760c2bf889",
                "appointment_id": "ca2c2310-651f-11ec-8785-7d2e4ec6adf3",
                "employee_id": "c8213450-651f-11ec-a985-cdb90a8ea0ff",
                "created_at": "2021-12-25T01:16:16.000000Z",
                "updated_at": "2021-12-25T01:16:16.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            },
            {
                "id": "41e88610-6520-11ec-a5c3-ab51c0425179",
                "appointment_id": "ca2c2f20-651f-11ec-b34c-6b3c09d65d19",
                "employee_id": "c8210b40-651f-11ec-8a7c-3f1defaa9a43",
                "created_at": "2021-12-25T01:16:16.000000Z",
                "updated_at": "2021-12-25T01:16:16.000000Z",
                "laravel_through_key": "c995ffc0-651f-11ec-9c35-71a93553ff89"
            }
        ],
        "employees": [
            {
                "id": "c820c7b0-651f-11ec-bb47-3ded5e116459",
                "title": "Employee Title",
                "employee_type": 1,
                "employee_type_description": "Administrative",
                "employment_status": 3,
                "employment_status_description": "Fired"
            },
            {
                "id": "c8210240-651f-11ec-86f3-67d1de0ec67a",
                "title": "Employee Title",
                "employee_type": 1,
                "employee_type_description": "Administrative",
                "employment_status": 3,
                "employment_status_description": "Fired"
            },
            {
                "id": "c8210b40-651f-11ec-8a7c-3f1defaa9a43",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired"
            },
            {
                "id": "c8211df0-651f-11ec-b5e0-3baeda8c1513",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 1,
                "employment_status_description": "Active"
            },
            {
                "id": "c8212260-651f-11ec-bc60-55c132e1f72d",
                "title": "Employee Title",
                "employee_type": 1,
                "employee_type_description": "Administrative",
                "employment_status": 1,
                "employment_status_description": "Active"
            },
            {
                "id": "c8213450-651f-11ec-a985-cdb90a8ea0ff",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired"
            },
            {
                "id": "c8213450-651f-11ec-a985-cdb90a8ea0ff",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired"
            }
        ],
        "costs": [
            {
                "id": "d1f293a0-651f-11ec-8712-a9cb2da600d7",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f29e80-651f-11ec-af7f-afa82764ec60",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f2a530-651f-11ec-b572-b7c28f007630",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f2ab60-651f-11ec-8ff9-bd97256302e3",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f3a880-651f-11ec-a8dd-efa835080a77",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f3ae80-651f-11ec-8f38-69ba2100443f",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "d1f3b480-651f-11ec-b79a-65027b87f82c",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7cc9ba0-6520-11ec-be46-537f93d669aa",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7db1380-6520-11ec-8dd5-31684a2edcb8",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7db3760-6520-11ec-9707-adf10093a663",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7db5c70-6520-11ec-beff-9738d2645574",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7dfa290-6520-11ec-8b6f-99ea248f81cc",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7dfb040-6520-11ec-b581-33bf8b9c9330",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "e7dfbdc0-6520-11ec-bdd6-a549e3001f6f",
                "cost_name": "Appointment Cost Seeder #1",
                "amount": 1000,
                "paid_amoun": 200,
                "unpaid_amount": 800
            },
            {
                "id": "ef9e18c0-6520-11ec-ab47-a7e81c621a0e",
                "cost_name": "Worklist Cost Seeder #2",
                "amount": 2000,
                "paid_amoun": 400,
                "unpaid_amount": 1600
            },
            {
                "id": "efa045f0-6520-11ec-afc6-17729fa2b804",
                "cost_name": "Worklist Cost Seeder #3",
                "amount": 2000,
                "paid_amoun": 400,
                "unpaid_amount": 1600
            }
        ],
        "created_at": "2021-12-25T01:12:54.000000Z",
        "updated_at": "2021-12-25T01:12:54.000000Z"
    }
}
```

-------------------------------------------------------
### 4. Update Worklist
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
### 5. Process Worklist
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
### 6. Calculate Worklist
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
### 7. Delete Worklist
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
### 8. Restore Worklist
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