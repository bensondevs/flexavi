## Invoices

-------------------------------------------------------
### 1. Company Invoices
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of invoice
`per_page` | Optional | number | Amount of data per page, default amount is 10
`start` | Optional | date, date string | Start date range to populate only invoice from this date and beyond.
`end` | Optional | date, date string | End date range to populate only invoice until this date and before.

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`invoices` | Object | The invoice object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "invoices": {
        "current_page": 1,
        "data": [
            {
                "id": "6e648670-30d4-11ec-a5fa-25c8b6895f90",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 962,
                "formatted_total": "€ 962,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 962,
                "formatted_total_unpaid": "€ 962,00",
                "status": 1,
                "status_description": "Created / Draft",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e648cc0-30d4-11ec-8756-ef6b25094754",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 785,
                "formatted_total": "€ 785,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 785,
                "formatted_total_unpaid": "€ 785,00",
                "status": 2,
                "status_description": "Sent / Definitive",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e648fe0-30d4-11ec-8810-6f4c017aac22",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1209,
                "formatted_total": "€ 1.209,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1209,
                "formatted_total_unpaid": "€ 1.209,00",
                "status": 9,
                "status_description": "Second Reminder Sent, send the third reminder?",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e6492c0-30d4-11ec-8f71-aff4345e59d8",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1185,
                "formatted_total": "€ 1.185,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1185,
                "formatted_total_unpaid": "€ 1.185,00",
                "status": 13,
                "status_description": "Paid via Debt collector",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e6495a0-30d4-11ec-a790-ef7f10cf339b",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1683,
                "formatted_total": "€ 1.683,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1683,
                "formatted_total_unpaid": "€ 1.683,00",
                "status": 11,
                "status_description": "Overdue, debt collector?",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e649870-30d4-11ec-8735-094dd65afc32",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 2141,
                "formatted_total": "€ 2.141,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 2141,
                "formatted_total_unpaid": "€ 2.141,00",
                "status": 13,
                "status_description": "Paid via Debt collector",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e649b40-30d4-11ec-9254-ab8573e843e2",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 785,
                "formatted_total": "€ 785,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 785,
                "formatted_total_unpaid": "€ 785,00",
                "status": 12,
                "status_description": "Sent to debt collector",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e649e10-30d4-11ec-aaef-c725cd4b59ff",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1546,
                "formatted_total": "€ 1.546,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1546,
                "formatted_total_unpaid": "€ 1.546,00",
                "status": 10,
                "status_description": "Third Reminder Sent",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e64a0e0-30d4-11ec-a635-3fd8568dd810",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 389,
                "formatted_total": "€ 389,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 389,
                "formatted_total_unpaid": "€ 389,00",
                "status": 7,
                "status_description": "First reminder sent, send the second reminder?",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e64a3b0-30d4-11ec-895f-fbe2277beb0d",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 504,
                "formatted_total": "€ 504,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 504,
                "formatted_total_unpaid": "€ 504,00",
                "status": 3,
                "status_description": "Paid",
                "payment_method": "1",
                "payment_method_description": "Cash"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 20,
        "last_page_url": "/?page=20",
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
                "url": "/?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "/?page=6",
                "label": "6",
                "active": false
            },
            {
                "url": "/?page=7",
                "label": "7",
                "active": false
            },
            {
                "url": "/?page=8",
                "label": "8",
                "active": false
            },
            {
                "url": "/?page=9",
                "label": "9",
                "active": false
            },
            {
                "url": "/?page=10",
                "label": "10",
                "active": false
            },
            {
                "url": null,
                "label": "...",
                "active": false
            },
            {
                "url": "/?page=19",
                "label": "19",
                "active": false
            },
            {
                "url": "/?page=20",
                "label": "20",
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
        "total": 196
    }
}
```

-------------------------------------------------------
### 1.1 Company Overdue Invoices
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/overdue`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of invoice
`per_page` | Optional | number | Amount of data per page, default amount is 10
`start` | Optional | date, date string | Start date range to populate only invoice from this date and beyond.
`end` | Optional | date, date string | End date range to populate only invoice until this date and before.

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`invoices` | Object | The invoice object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "invoices": {
        "current_page": 1,
        "data": [
            {
                "id": "6e648670-30d4-11ec-a5fa-25c8b6895f90",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 962,
                "formatted_total": "€ 962,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 962,
                "formatted_total_unpaid": "€ 962,00",
                "status": 1,
                "status_description": "Created / Draft",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e648cc0-30d4-11ec-8756-ef6b25094754",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 785,
                "formatted_total": "€ 785,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 785,
                "formatted_total_unpaid": "€ 785,00",
                "status": 2,
                "status_description": "Sent / Definitive",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e648fe0-30d4-11ec-8810-6f4c017aac22",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1209,
                "formatted_total": "€ 1.209,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1209,
                "formatted_total_unpaid": "€ 1.209,00",
                "status": 9,
                "status_description": "Second Reminder Sent, send the third reminder?",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e6492c0-30d4-11ec-8f71-aff4345e59d8",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1185,
                "formatted_total": "€ 1.185,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1185,
                "formatted_total_unpaid": "€ 1.185,00",
                "status": 13,
                "status_description": "Paid via Debt collector",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e6495a0-30d4-11ec-a790-ef7f10cf339b",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1683,
                "formatted_total": "€ 1.683,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1683,
                "formatted_total_unpaid": "€ 1.683,00",
                "status": 11,
                "status_description": "Overdue, debt collector?",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e649870-30d4-11ec-8735-094dd65afc32",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 2141,
                "formatted_total": "€ 2.141,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 2141,
                "formatted_total_unpaid": "€ 2.141,00",
                "status": 13,
                "status_description": "Paid via Debt collector",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e649b40-30d4-11ec-9254-ab8573e843e2",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 785,
                "formatted_total": "€ 785,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 785,
                "formatted_total_unpaid": "€ 785,00",
                "status": 12,
                "status_description": "Sent to debt collector",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e649e10-30d4-11ec-aaef-c725cd4b59ff",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 1546,
                "formatted_total": "€ 1.546,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 1546,
                "formatted_total_unpaid": "€ 1.546,00",
                "status": 10,
                "status_description": "Third Reminder Sent",
                "payment_method": "2",
                "payment_method_description": "Bank Transfer"
            },
            {
                "id": "6e64a0e0-30d4-11ec-a635-3fd8568dd810",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 389,
                "formatted_total": "€ 389,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 389,
                "formatted_total_unpaid": "€ 389,00",
                "status": 7,
                "status_description": "First reminder sent, send the second reminder?",
                "payment_method": "1",
                "payment_method_description": "Cash"
            },
            {
                "id": "6e64a3b0-30d4-11ec-895f-fbe2277beb0d",
                "company_id": "47ed9c00-30d4-11ec-afcd-5d2985e253a0",
                "total": 504,
                "formatted_total": "€ 504,00",
                "total_in_terms": 0,
                "formatted_total_in_terms": "€ 0,00",
                "total_out_terms": 0,
                "formatted_total_out_terms": "€ 0,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "total_unpaid": 504,
                "formatted_total_unpaid": "€ 504,00",
                "status": 3,
                "status_description": "Paid",
                "payment_method": "1",
                "payment_method_description": "Cash"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 20,
        "last_page_url": "/?page=20",
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
                "url": "/?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "/?page=6",
                "label": "6",
                "active": false
            },
            {
                "url": "/?page=7",
                "label": "7",
                "active": false
            },
            {
                "url": "/?page=8",
                "label": "8",
                "active": false
            },
            {
                "url": "/?page=9",
                "label": "9",
                "active": false
            },
            {
                "url": "/?page=10",
                "label": "10",
                "active": false
            },
            {
                "url": null,
                "label": "...",
                "active": false
            },
            {
                "url": "/?page=19",
                "label": "19",
                "active": false
            },
            {
                "url": "/?page=20",
                "label": "20",
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
        "total": 196
    }
}
```

-------------------------------------------------------
### 2. Store Invoice
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`customer_id` | Required | string, uuid | Invoiced customer ID.
`invoice_number` | Optional | string | Self-made invoice number.
`payment_method` | Optional | numeric, numeric string | Payment method of invoice. To see the detail about the method list, see [Documentation](/docs/Meta/Invoice.md)

**Request Body Example:**

```json
{
	"customer_id": "023d4300-f8e2-11eb-b6cb-f1ef24693c4c",
	"invoice_number": "INV0191201",
	"payment_method": 2
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save invoice."
}
```

-------------------------------------------------------
### 3. Update Invoice
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.
`customer_id` | Required | string, uuid | Invoiced customer ID.
`invoice_number` | Optional | string | Self-made invoice number.
`payment_method` | Optional | numeric, numeric string | Payment method of invoice. To see the detail about the method list, see [Documentation](/docs/Meta/Invoice.md)

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27",
	"customer_id": "023d4300-f8e2-11eb-b6cb-f1ef24693c4c",
	"invoice_number": "INV0191201",
	"payment_method": 2
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save invoice."
}
```

-------------------------------------------------------
### 3. Send Invoice
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/send`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.
`destination_email` | Required | string, email string | Email destination.

**Request Body Example:**

```json
{
	"id": "613fb100-30e1-11ec-a90c-3b208767dd27",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully send invoice."
}
```

-------------------------------------------------------
### 4. Print Invoice
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/print`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.

**Request Body Example:**

```json
{
	"id": "613fb100-30e1-11ec-a90c-3b208767dd27",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully print invoice."
}
```

-------------------------------------------------------
### 5. Print Invoice as Draft
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/print_deaft`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully print invoice as draft."
}
```

-------------------------------------------------------
### 6. Send Invoice Reminder
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/send_reminder`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully send invoice reminder."
}
```

-------------------------------------------------------
### 7. Change Invoice Status
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/change_status`

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
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.
`status` | Required | integer | Target of invoice status. See details in [Documentation](/docs/Meta/Invoice.md)

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27",
	"status": 2
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully change invoice status."
}
```

-------------------------------------------------------
### 8. Mark Invoice As Paid
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/mark_as_paid`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.
`is_via_debt_collector` | Optional | string, boolean string | Via Debt collector.

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27",
	"is_via_debt_collector": 1
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully mark invoice as paid."
}
```

-------------------------------------------------------
### 9. Delete Invoice
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/delete`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.

**Request Body Example:**

```json
{
	"invoice_id": "613fb100-30e1-11ec-a90c-3b208767dd27"
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete invoice."
}
```