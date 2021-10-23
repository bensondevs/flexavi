## Workday Worklists

-------------------------------------------------------
### 1. Populate Workday Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/workdays/worklists`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`workday_id` | Required | string, uuid, exists in `worklists` | ID of target workday to populate worklists.
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workdayworklist` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "worklists": {
        "current_page": 1,
        "data": [
            {
                "id": "697de5b0-2f57-11ec-a6eb-dd44c95fb711",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4b180-2f57-11ec-84d6-89642616eaff",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02",
                "calculated_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697df240-2f57-11ec-a5a6-6de18259aafe",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4bb00-2f57-11ec-84b0-a9866a81ffb2",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z"
            },
            {
                "id": "697df5b0-2f57-11ec-8cf9-d36de06bdeda",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4bb00-2f57-11ec-84b0-a9866a81ffb2",
                "worklist_name": "Worklist Name 2",
                "status": 2,
                "status_description": "Processed",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697df970-2f57-11ec-a3fb-73f539169f8b",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4c170-2f57-11ec-925d-cf760bab5d75",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697dfd20-2f57-11ec-8d57-c3ecf2d7ddd6",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4c730-2f57-11ec-9002-2b3635a1ddd6",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z"
            },
            {
                "id": "697dfff0-2f57-11ec-8540-e56e15d3c78d",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4ccb0-2f57-11ec-b222-f1dd47ea1a1c",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697e0420-2f57-11ec-8663-8b3a6a01d1f9",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4d220-2f57-11ec-bdd8-df064bacc907",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697e07b0-2f57-11ec-8f60-e1fc59a69dcd",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4d780-2f57-11ec-b402-9dae427417fd",
                "worklist_name": "Worklist Name 1",
                "status": 2,
                "status_description": "Processed",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02"
            },
            {
                "id": "697e0b30-2f57-11ec-947d-fb025027be48",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4d780-2f57-11ec-b402-9dae427417fd",
                "worklist_name": "Worklist Name 2",
                "status": 1,
                "status_description": "Prepared",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z"
            },
            {
                "id": "697e0dd0-2f57-11ec-b444-fb253e5175e6",
                "company_id": "681c8e20-2f57-11ec-b9cf-53b421af227c",
                "workday_id": "68f4dd90-2f57-11ec-9f7a-e1e0254c1fd4",
                "worklist_name": "Worklist Name 1",
                "status": 3,
                "status_description": "Calculated",
                "created_at": "2021-10-17T14:35:02.000000Z",
                "updated_at": "2021-10-17T14:35:02.000000Z",
                "processed_at": "2021-10-17 16:35:02",
                "calculated_at": "2021-10-17 16:35:02"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 3,
        "last_page_url": "/?page=3",
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
        "total": 25
    }
}
```

-------------------------------------------------------
### 2. Store Worklist and Attach to Workday
-------------------------------------------------------

**Endpoint:** `/api/dashboard/workdays/worklists/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`workday_id` | Required | string, uuid string | Target workday ID.
`worklist_name` | Required | string | Name of worklist.


**Request Body Example:**

```json
{
    
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update workdayworklist status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save workdayworklist."
}
```

-------------------------------------------------------
### 3. Update WorkdayWorklist
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
`id` | Required | string | ID of updated workdayworklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update WorkdayWorklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save workdayworklist."
}
```

-------------------------------------------------------
### 4. Delete WorkdayWorklist
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
`id` | Required | string | ID of deleted workdayworklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete workdayworklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete workdayworklist"
}
```

-------------------------------------------------------
### 5. Restore WorkdayWorklist
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
`id` | Required | string | ID of updated workdayworklist

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore workdayworklist status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore workdayworklist."
}
```