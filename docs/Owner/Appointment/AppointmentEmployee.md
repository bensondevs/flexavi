## Appointment Employee

-------------------------------------------------------
### 1. Populate Appointment Employees
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/employees`

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
    "appointment_employees": {
        "current_page": 1,
        "data": [
            {
                "id": "125dafd0-12db-11ec-9216-997884b0fa94",
                "appointment_id": "96978010-12d9-11ec-9c62-f75430b14749",
                "employee_id": "955c0280-12d9-11ec-af46-31bc59100f33",
                "employee": {
                    "id": "955c0280-12d9-11ec-af46-31bc59100f33",
                    "user_id": "926b2340-12d9-11ec-a98c-99b65547a180",
                    "company_id": "94bc19b0-12d9-11ec-b202-c367580c2e11",
                    "title": "Employee Title",
                    "employee_type": 1,
                    "employment_status": 2,
                    "created_at": "2021-09-11T08:23:46.000000Z",
                    "updated_at": "2021-09-11T08:23:46.000000Z",
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
        "to": 1,
        "total": 1
    }
}
```

-------------------------------------------------------
### 2. Assign Appointment Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/employees/assign`

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
    "message": "Successfully assign employee to appointment."
}
```

-------------------------------------------------------
### 3. Unassign Appointment Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/employees/unassign`

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
`id` or `appointment_employee_id` | Required | uuid, string, existed in `appointment_employees` | Target appointment worker

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
    "message": "Successfully unassign employee from appointment."
}
```