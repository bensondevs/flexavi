## Sub-Appointment

-------------------------------------------------------
### 1. Populate Sub-Appointment(s)
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | uuid, string, exists in `appointments` table | Target appointment to query the worker

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`sub_appointments` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "sub_appointments": [
        {
            "id": "d6ceaa60-f1d1-11eb-aa48-d180f6c62241",
            "status": 3,
            "status_description": "Processed",
            "start": "2021-07-23 09:35:11",
            "end": "2021-08-04 09:35:11",
            "note": null
        },
        {
            "id": "d6ceaf50-f1d1-11eb-9eda-1bcfc4988f51",
            "status": 3,
            "status_description": "Processed",
            "start": "2021-07-29 09:35:11",
            "end": "2021-08-08 09:35:11",
            "note": null
        },
        {
            "id": "d6ceb440-f1d1-11eb-9fed-bd16258d69d7",
            "status": 3,
            "status_description": "Processed",
            "start": "2021-07-26 09:35:11",
            "end": "2021-08-05 09:35:11",
            "note": null
        }
    ]
}
```

-------------------------------------------------------
### 2. Store Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`appointment_id` | Required | uuid, string, exists in `appointments` table | Target appointment that will have another sub
`start` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The starting of sub appointment
`end` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The ending of sub appointment

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save sub appointment."
}
```

-------------------------------------------------------
### 3. Update Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be updated
`start` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The starting of sub appointment
`end` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The ending of sub appointment

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save sub appointment."
}
```

-------------------------------------------------------
### 3. Delete Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/delete`

**Method:** `DELETE`