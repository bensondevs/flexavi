## Appointment Meta

-------------------------------------------------------
### 1. All Appointment Cancellation Vaults
-------------------------------------------------------

**Endpoint:** `/api/meta/sub_appointment/all_cancellation_vaults`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Roofer",
    "2": "Customer"
}
```

-------------------------------------------------------
### 2. All Appointment Status
-------------------------------------------------------

**Endpoint:** `/api/meta/sub_appointment/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`


**Success Response Example:**

```json
{
    "1": "Created",
    "2": "In Process",
    "3": "Processed",
    "4": "Cancelled"
}
```