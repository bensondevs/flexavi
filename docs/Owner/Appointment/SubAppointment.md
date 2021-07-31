## Sub-Appointment

-------------------------------------------------------
### 1. Populate Sub-Appointment Worker
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
`page` | Optional | number | Page of pagination
`per_page` | Optional | numeric | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`sub_appointments` | Object | The customer object, contains pagination information and array of `data`