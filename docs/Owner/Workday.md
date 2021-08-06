## Workday

-------------------------------------------------------
### 1. Populate Company Workdays
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays`

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
`with_worklists` | Optional | boolean, boolean string | Set this value to `true` to load the `worklists` inside each workday object. The `worklists` attribute will have Array type.
`with_appointments` | Optional | boolean, boolean string | Set this value to `true` to load `appointments`. If you set `with_worklists` to `true`, there will be Array of `appointments` inside each `worklist` object. Otherwise, `appointments` will be loaded in the property of each `worklists`.

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workdays` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "workdays": {
        "current_page": 1,
        "data": [
            {
                "id": "c12e7160-f606-11eb-ad20-eb66e242cb72",
                "date": "2021-08-01",
                "total_worklists": 2,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e7580-f606-11eb-83de-c98858b134df",
                "date": "2021-08-02",
                "total_worklists": 1,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e7890-f606-11eb-bf44-1759fd10d33f",
                "date": "2021-08-03",
                "total_worklists": 1,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e7b50-f606-11eb-8012-c147c3252d0f",
                "date": "2021-08-04",
                "total_worklists": 3,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e7de0-f606-11eb-924b-9f82529af49a",
                "date": "2021-08-05",
                "total_worklists": 2,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e8080-f606-11eb-8c8c-7b65fedb41fd",
                "date": "2021-08-06",
                "total_worklists": 1,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e8300-f606-11eb-9c6e-57cd1b656c08",
                "date": "2021-08-07",
                "total_worklists": 0,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e8580-f606-11eb-b0c0-d578b7c689a2",
                "date": "2021-08-08",
                "total_worklists": 1,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e8800-f606-11eb-b197-c734d81afbff",
                "date": "2021-08-09",
                "total_worklists": 4,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            },
            {
                "id": "c12e8aa0-f606-11eb-9e91-c50badecc624",
                "date": "2021-08-10",
                "total_worklists": 1,
                "total_appointments": null,
                "status": 1,
                "status_description": "Prepared"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 4,
        "last_page_url": "/?page=4",
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
        "total": 32
    }
}
```

-------------------------------------------------------
### 2. Current Workday
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/current`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
- | - | - | -

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workday` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "workday": {
        "id": "c12e8080-f606-11eb-8c8c-7b65fedb41fd",
        "date": "2021-08-06",
        "total_worklists": null,
        "total_appointments": null,
        "status": 1,
        "status_description": "Prepared"
    }
}
```

-------------------------------------------------------
### 2. Process Workday
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/process`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `workday_id` | Required | string | The workday that will be processed

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workday` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully process workday."
}
```

-------------------------------------------------------
### 3. Calculate Workday
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/calculate`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `workday_id` | Required | string | The workday that will be processed

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workday` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully calculate workday."
}
```