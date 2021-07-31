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
Content-Type | `application/x-www-form-urlencoded`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be updated
`start` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The starting of sub appointment
`end` | Required | datetime, format (YYYY-MM-DD HH:mm:ss), between or equal to `appointment`.`start` and `appointment`.`end` | The ending of sub appointment

**Request Body Example:**

```json
{
	"id": "d6ceb440-f1d1-11eb-9fed-bd16258d69d7",
	"start": "2021-05-15 18:00:00",
    "end": "2021-05-18 18:00:00",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save sub-appointment."
}
```

-------------------------------------------------------
### 4. Execute Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/execute`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be executed

**Request Body Example:**

```json
{
	"id": "d6ceb440-f1d1-11eb-9fed-bd16258d69d7"
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully execute sub-appointment."
}
```

-------------------------------------------------------
### 5. Process Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/process`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be processed

**Request Body Example:**

```json
{
	"id": "d6ceb440-f1d1-11eb-9fed-bd16258d69d7"
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully process sub-appointment."
}
```

-------------------------------------------------------
### 6. Cancel Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/cancel`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be cancelled
`cancellation_reason` | Required | string | The main reason why this sub-appointment is cancelled
`cancellation_vault` | Required | integer, min:1, max:2 | The party held responsible, to see detail about the value representation see [Sub Appointment Meta](/docs/Meta/SubAppointment.md)
`cancellation_note` | Optional | string | The explanation about the cancellation, this will give chance for user to explain more that has been stated in `cancellation_reason`

**Request Body Example:**

```json
{
	"id": "d6ceb440-f1d1-11eb-9fed-bd16258d69d7",
	"cancellation_reason": "The customer is not at home",
	"cancellation_vault": 2,
	"cancellation_note": "The team of roofers have been waiting for 30 minutes or even more since 18.00 and we get no response from anyone inside the house"
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully cancel sub-appointment."
}
```

-------------------------------------------------------
### 7. Delete Sub-Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/subs/delete`

**Method:** `DELETE`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Response Attributes:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `sub_appointment_id` | Required | uuid, string, exists in `sub_appointments` table | Target sub appointment that will be deleted

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete sub-appointment."
}
```