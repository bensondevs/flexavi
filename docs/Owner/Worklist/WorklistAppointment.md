## Worklist Appointment

-------------------------------------------------------
### 1. Populate Worklist Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | uuid, string | Worklist ID of appointments
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointments` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "appointments": {
        "current_page": 1,
        "data": [
            {
                "id": "e17c1410-2f30-11ec-80a0-a79beeb49ac4",
                "customer_id": "dfb8ded0-2f30-11ec-a028-07c9f9d56e10",
                "status": 2,
                "status_description": "In Process",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-10-20T09:59:12.000000Z",
                "end": "2021-10-22T09:59:12.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-10-17T09:59:12.000000Z",
                "in_process_at": null
            },
            {
                "id": "e17c1b60-2f30-11ec-8e23-81c32d397564",
                "customer_id": "dfb8ded0-2f30-11ec-a028-07c9f9d56e10",
                "status": 3,
                "status_description": "Processed",
                "type": 3,
                "type_description": "Execute Work",
                "start": "2021-10-07T09:59:12.000000Z",
                "end": "2021-10-13T09:59:12.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-10-17T09:59:12.000000Z",
                "in_process_at": "2021-10-18 11:59:12",
                "processed_at": null
            },
            {
                "id": "e17c2400-2f30-11ec-8da0-97d0d18e6e8e",
                "customer_id": "dfb8ded0-2f30-11ec-a028-07c9f9d56e10",
                "status": 4,
                "status_description": "Calculated",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-10-16T09:59:12.000000Z",
                "end": "2021-10-19T09:59:12.000000Z",
                "include_weekend": false,
                "note": "This is seeder appointment",
                "created_at": "2021-10-17T09:59:12.000000Z",
                "in_process_at": "2021-10-20 11:59:12",
                "processed_at": "2021-10-18 11:59:12",
                "calculated_at": null
            },
            {
                "id": "e17c2df0-2f30-11ec-b816-572ecd3337b5",
                "customer_id": "dfb8ded0-2f30-11ec-a028-07c9f9d56e10",
                "status": 4,
                "status_description": "Calculated",
                "type": 2,
                "type_description": "Quotation",
                "start": "2021-10-24T09:59:12.000000Z",
                "end": "2021-10-24T09:59:12.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-10-17T09:59:12.000000Z",
                "in_process_at": "2021-10-20 11:59:12",
                "processed_at": "2021-10-19 11:59:12",
                "calculated_at": null
            },
            {
                "id": "e17c37d0-2f30-11ec-a107-9b070996255c",
                "customer_id": "dfb8ded0-2f30-11ec-a028-07c9f9d56e10",
                "status": 3,
                "status_description": "Processed",
                "type": 1,
                "type_description": "Inspection",
                "start": "2021-10-21T09:59:12.000000Z",
                "end": "2021-10-23T09:59:12.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-10-17T09:59:12.000000Z",
                "in_process_at": "2021-10-19 11:59:12",
                "processed_at": null
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 2. Attach Appointment to Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments/attach`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | string, uuid, exists in `appointments` table | The appointment to be attached to worklist.
`worklist_id` | Required | string, uuid, exists in `worklists` table | The target worklist to be attached by appointment.


**Request Body Example:**

```json
{
    "appointment_id": "c69e2480-fba7-11eb-8a19-bd17c3739c28",
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status of the attach.
`message` | String | Message response for the user.


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully attach appointment to worklist."
}
```

-------------------------------------------------------
### 3. Attach Many Appointments to Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments/attach_many`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of target worklist.
`appointment_ids` | Required | array, json string array contains `appointment_id` | IDs of attaching appointments.

**Request Body Example:**

```json
{
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3",
    "appointment_ids[]": "c69e2480-fba7-11eb-8a19-bd17c3739c28",
    "appointment_ids[]": "c69e3270-fba7-11eb-b21e-d79c1882d164",
}
```

```json
{
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3",
    "appointment_ids": "['c69e2480-fba7-11eb-8a19-bd17c3739c28', 'c69e3270-fba7-11eb-b21e-d79c1882d164']"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status.
`message` | String | Message response for the user.

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully attach many appointments to worklist."
}
```

-------------------------------------------------------
### 4. Detach Appointment from Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments/detach`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | string, uuid, exists in `appointments` table | The appointment to be detached to worklist.
`worklist_id` | Required | string, uuid, exists in `worklists` table | The target worklist to be detached from.


**Request Body Example:**

```json
{
    "appointment_id": "c69e2480-fba7-11eb-8a19-bd17c3739c28",
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status of the attach.
`message` | String | Message response for the user.


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully detach appointment from worklist."
}
```

-------------------------------------------------------
### 5. Detach Many Appointments to Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments/detach_many`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of target worklist.
`appointment_ids` | Required | array, json string array contains `appointment_id` | IDs of attaching appointments.

**Request Body Example:**

```json
{
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3",
    "appointment_ids[]": "c69e2480-fba7-11eb-8a19-bd17c3739c28",
    "appointment_ids[]": "c69e3270-fba7-11eb-b21e-d79c1882d164",
}
```

```json
{
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3",
    "appointment_ids": "['c69e2480-fba7-11eb-8a19-bd17c3739c28', 'c69e3270-fba7-11eb-b21e-d79c1882d164']"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status.
`message` | String | Message response for the user.

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully detach many appointments from worklist."
}
```

-------------------------------------------------------
### 6. Truncate Appointments from Worklist
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments/truncate`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`worklist_id` | Required | string | ID of target worklist.
`appointment_ids` | Required | array, json string array contains `appointment_id` | IDs of attaching appointments.

**Request Body Example:**

```json
{
    "worklist_id": "c6324300-fba7-11eb-8d1a-b38d3a2edad3",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution status.
`message` | String | Message response for the user.

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully truncate appointments from worklist."
}
```