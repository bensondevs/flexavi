## Appointment Meta

-------------------------------------------------------
### 1. All Appointment Cancellation Vaults
-------------------------------------------------------

**Endpoint:** `/api/meta/appointment/all_cancellation_vaults`

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

**Endpoint:** `/api/meta/appointment/all_statuses`

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
    "4": "Calculated",
    "5": "Cancelled"
}
```

-------------------------------------------------------
### 3. All Appointment Type
-------------------------------------------------------

**Endpoint:** `/api/meta/appointment/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Inspection",
    "2": "Quotation",
    "3": "Execute Work",
    "4": "Warranty",
    "5": "Payment Pick-Up",
    "6": "Payment Reminder"
}
```