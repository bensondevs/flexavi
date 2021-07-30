## Appointment Worker

-------------------------------------------------------
### 1. Populate Appointment Worker
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/workers`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | uuid, string, exists in `appointments` table | Target appointment to query the worker
`page` | Optional | number | Page of pagination
`per_page` | Optional | numeric | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`workers` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "workers": {
        "current_page": 1,
        "data": [
            {
                "id": "fc406970-f113-11eb-8a60-bd34953fd7d3",
                "employee_type": "administrative",
                "employee": {
                    "id": "f7e769e0-f113-11eb-8ef8-cfb3b1d0abf1",
                    "user_id": "f5345510-f113-11eb-9f17-0f514d65c4c8",
                    "company_id": "f74ad840-f113-11eb-8f24-7f3c3246a486",
                    "title": "Employee Title",
                    "employee_type": 1,
                    "employment_status": 3,
                    "created_at": "2021-07-30T08:56:03.000000Z",
                    "updated_at": "2021-07-30T08:56:03.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "fc406f10-f113-11eb-a886-4bad10d05c2c",
                "employee_type": "administrative",
                "employee": {
                    "id": "f7e79fa0-f113-11eb-b08e-b35e759bc859",
                    "user_id": "f600ffc0-f113-11eb-bf01-4d5efbe27661",
                    "company_id": "f74ad840-f113-11eb-8f24-7f3c3246a486",
                    "title": "Employee Title",
                    "employee_type": 1,
                    "employment_status": 3,
                    "created_at": "2021-07-30T08:56:03.000000Z",
                    "updated_at": "2021-07-30T08:56:03.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "fc407200-f113-11eb-9f4d-4dcd2661b855",
                "employee_type": "administrative",
                "employee": {
                    "id": "f7e78a40-f113-11eb-ad1d-4b0c7706f3d7",
                    "user_id": "f5a6b3c0-f113-11eb-94d7-730cdbe1a617",
                    "company_id": "f74ad840-f113-11eb-8f24-7f3c3246a486",
                    "title": "Employee Title",
                    "employee_type": 1,
                    "employment_status": 3,
                    "created_at": "2021-07-30T08:56:03.000000Z",
                    "updated_at": "2021-07-30T08:56:03.000000Z",
                    "deleted_at": null
                }
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
### 2. Store Appointment Worker
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/workers/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | uuid, string, exists in `appointments` table | The target appointment
`employee_id` | Required | date, format (yyyy-mm-dd) | The target employee

**Request Body Example:**

```json
{
    "appointment_id": "8473e510-bbdb-11eb-a7df-bf6dbbb311e6",
    "employee_id": "824a1e00-bbdb-11eb-82b7-bfc28376e741",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully store appointment worker"
}
```

-------------------------------------------------------
### 3. Delete Appointment Worker
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/workers/delete`

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
`id` | Required | uuid, string, existed in `appointment_workers` | Target appointment worker

**Request Body Example:**

```json
{
    "id": "8473e510-bbdb-11eb-a7df-bf6dbbb311e6"
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete appointment worker"
}
```