## Appointment

-------------------------------------------------------
### 1. Populate Company Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments`

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
`search` | Optional | string | Searched keyword, will be matched through attributes of `cancellation_note`, `note`
`per_page` | Optional | numeric | Amount of data per page, default amount is 10
`type` | Optional | numeric, min:1, max:6  | The type code to populate only certain appointment with any type requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)
`status` | Optional | numeric, min:1, max:5 | The status code to populate only certain appointment with any status requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointments` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "appointments": {
        "current_page": 2,
        "data": [
            {
                "id": "691051e0-ed23-11eb-a069-8b02febb1280",
                "customer": {
                    "id": "5a8668b0-ed23-11eb-8735-d34b492ad6ed",
                    "addresses": [],
                    "fullname": "Customer 553 of Company 1",
                    "email": "customer553@company1.com",
                    "phone": "8338059136000",
                    "second_phone": null
                },
                "status": 2,
                "status_description": "In Process",
                "type": 3,
                "type_description": "Execute Work",
                "start": "2021-07-20T08:36:30.000000Z",
                "end": "2021-07-24T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null
            },
            {
                "id": "6912fd80-ed23-11eb-8dcd-7bf2cf7aa3c2",
                "customer": {
                    "id": "5a8a0cc0-ed23-11eb-884d-a9264d379826",
                    "addresses": [],
                    "fullname": "Customer 784 of Company 1",
                    "email": "customer784@company1.com",
                    "phone": "3836682131320",
                    "second_phone": null
                },
                "status": 1,
                "status_description": "Created",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-07-28T08:36:30.000000Z",
                "end": "2021-07-28T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z"
            },
            {
                "id": "69133c00-ed23-11eb-858e-8fa632462ff2",
                "customer": {
                    "id": "5a87c3b0-ed23-11eb-b014-f9dadfd730d7",
                    "addresses": [],
                    "fullname": "Customer 645 of Company 1",
                    "email": "customer645@company1.com",
                    "phone": "8806705850616",
                    "second_phone": null
                },
                "status": 5,
                "status_description": "Cancelled",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-07-23T08:36:30.000000Z",
                "end": "2021-07-25T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null,
                "processed_at": null,
                "calculated_at": null,
                "cancelled_at": null,
                "cancellation_vault": null,
                "cancellation_vault_description": "",
                "cancellation_cause": null,
                "cancellation_note": null
            }
        ],
        "first_page_url": "/?page=1",
        "from": 4,
        "last_page": 35,
        "last_page_url": "/?page=35",
        "links": [
            {
                "url": "/?page=1",
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "2",
                "active": true
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
                "url": "/?page=34",
                "label": "34",
                "active": false
            },
            {
                "url": "/?page=35",
                "label": "35",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=3",
        "path": "/",
        "per_page": "3",
        "prev_page_url": "/?page=1",
        "to": 6,
        "total": 103
    }
}
```

-------------------------------------------------------
### 1.1 Populate Company Unplanned Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/unplanneds`

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
`search` | Optional | string | Searched keyword, will be matched through attributes of `cancellation_note`, `note`
`per_page` | Optional | numeric | Amount of data per page, default amount is 10
`type` | Optional | numeric, min:1, max:6  | The type code to populate only certain appointment with any type requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)
`status` | Optional | numeric, min:1, max:5 | The status code to populate only certain appointment with any status requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointments` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "appointments": {
        "current_page": 2,
        "data": [
            {
                "id": "691051e0-ed23-11eb-a069-8b02febb1280",
                "customer": {
                    "id": "5a8668b0-ed23-11eb-8735-d34b492ad6ed",
                    "addresses": [],
                    "fullname": "Customer 553 of Company 1",
                    "email": "customer553@company1.com",
                    "phone": "8338059136000",
                    "second_phone": null
                },
                "status": 2,
                "status_description": "In Process",
                "type": 3,
                "type_description": "Execute Work",
                "start": "2021-07-20T08:36:30.000000Z",
                "end": "2021-07-24T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null
            },
            {
                "id": "6912fd80-ed23-11eb-8dcd-7bf2cf7aa3c2",
                "customer": {
                    "id": "5a8a0cc0-ed23-11eb-884d-a9264d379826",
                    "addresses": [],
                    "fullname": "Customer 784 of Company 1",
                    "email": "customer784@company1.com",
                    "phone": "3836682131320",
                    "second_phone": null
                },
                "status": 1,
                "status_description": "Created",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-07-28T08:36:30.000000Z",
                "end": "2021-07-28T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z"
            },
            {
                "id": "69133c00-ed23-11eb-858e-8fa632462ff2",
                "customer": {
                    "id": "5a87c3b0-ed23-11eb-b014-f9dadfd730d7",
                    "addresses": [],
                    "fullname": "Customer 645 of Company 1",
                    "email": "customer645@company1.com",
                    "phone": "8806705850616",
                    "second_phone": null
                },
                "status": 5,
                "status_description": "Cancelled",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-07-23T08:36:30.000000Z",
                "end": "2021-07-25T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null,
                "processed_at": null,
                "calculated_at": null,
                "cancelled_at": null,
                "cancellation_vault": null,
                "cancellation_vault_description": "",
                "cancellation_cause": null,
                "cancellation_note": null
            }
        ],
        "first_page_url": "/?page=1",
        "from": 4,
        "last_page": 35,
        "last_page_url": "/?page=35",
        "links": [
            {
                "url": "/?page=1",
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "2",
                "active": true
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
                "url": "/?page=34",
                "label": "34",
                "active": false
            },
            {
                "url": "/?page=35",
                "label": "35",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=3",
        "path": "/",
        "per_page": "3",
        "prev_page_url": "/?page=1",
        "to": 6,
        "total": 103
    }
}
```

-------------------------------------------------------
### 2. Populate Customer Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/of_customer`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`customer_id` | Required | uuid, string, exists in `customers` table | Customer with appointment as target
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through attributes of `cancellation_note`, `note`
`per_page` | Optional | numeric | Amount of data per page, default amount is 10
`type` | Optional | numeric, min:1, max:6  | The type code to populate only certain appointment with any type requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)
`status` | Optional | numeric, min:1, max:5 | The status code to populate only certain appointment with any status requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointments` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "appointments": {
        "current_page": 2,
        "data": [
            {
                "id": "691051e0-ed23-11eb-a069-8b02febb1280",
                "customer": {
                    "id": "5a8668b0-ed23-11eb-8735-d34b492ad6ed",
                    "addresses": [],
                    "fullname": "Customer 553 of Company 1",
                    "email": "customer553@company1.com",
                    "phone": "8338059136000",
                    "second_phone": null
                },
                "status": 2,
                "status_description": "In Process",
                "type": 3,
                "type_description": "Execute Work",
                "start": "2021-07-20T08:36:30.000000Z",
                "end": "2021-07-24T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null
            },
            {
                "id": "6912fd80-ed23-11eb-8dcd-7bf2cf7aa3c2",
                "customer": {
                    "id": "5a8a0cc0-ed23-11eb-884d-a9264d379826",
                    "addresses": [],
                    "fullname": "Customer 784 of Company 1",
                    "email": "customer784@company1.com",
                    "phone": "3836682131320",
                    "second_phone": null
                },
                "status": 1,
                "status_description": "Created",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-07-28T08:36:30.000000Z",
                "end": "2021-07-28T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z"
            },
            {
                "id": "69133c00-ed23-11eb-858e-8fa632462ff2",
                "customer": {
                    "id": "5a87c3b0-ed23-11eb-b014-f9dadfd730d7",
                    "addresses": [],
                    "fullname": "Customer 645 of Company 1",
                    "email": "customer645@company1.com",
                    "phone": "8806705850616",
                    "second_phone": null
                },
                "status": 5,
                "status_description": "Cancelled",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-07-23T08:36:30.000000Z",
                "end": "2021-07-25T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null,
                "processed_at": null,
                "calculated_at": null,
                "cancelled_at": null,
                "cancellation_vault": null,
                "cancellation_vault_description": "",
                "cancellation_cause": null,
                "cancellation_note": null
            }
        ],
        "first_page_url": "/?page=1",
        "from": 4,
        "last_page": 35,
        "last_page_url": "/?page=35",
        "links": [
            {
                "url": "/?page=1",
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "2",
                "active": true
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
                "url": "/?page=34",
                "label": "34",
                "active": false
            },
            {
                "url": "/?page=35",
                "label": "35",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=3",
        "path": "/",
        "per_page": "3",
        "prev_page_url": "/?page=1",
        "to": 6,
        "total": 103
    }
}
```

-------------------------------------------------------
### 3. Store Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`customer_id` | Required | uuid, string, exists in `customers` table | The customer that requested or will be having appointment with. Put their ID in this payload and it will automatically assigned to the customer
`start` | Required | date, format (yyyy-mm-dd) | The starting date of appointment
`end` | Required | date, format (yyyy-mm-dd) | The ending date of appointment
`include_weekend` | Optional | boolean, boolean string, numeric 1 or 0 | Set to `true` if the appointment include non-working days like saturday and sunday. Defaultly, this payload has value of false.
`type` | Required | numeric, numeric string, min:1, max:6 | The type of appointment, this represents the meta of appointment type/kind, to see more detail what numbers represent please see [Appointment Meta](/docs/Meta/Appointment.md) 
`note` | Optional | string, text | This payload can be used to put side note about this appointment and what should be paid attention about the appointment

**Request Body Example:**

```json
{
    "customer_id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
    "start": "2021-05-15 08:00:00",
    "end": "2021-05-18 12:00:00",
    "include_weekend": true,
    "type": 1,
    "note": "Fixing leaking rooftop",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save appointment"
}
```

-------------------------------------------------------
### 4. View Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/view`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string | ID of selected appointment
`with_finished_works` | Optional | boolean, boolean string | Set to `true` to load appointment with finished works within appointment. The default value of this attribute is `true`
`with_customer` | Optional | boolean, boolean string | Set to `true` to load appointment with customer of appointment. The default value of this attribute is `true`
`with_subs` | Optional | boolean, boolean string | Set to `true` to load appointment with subs of appointment. The default value of this attribute is `true`
`with_works` | Optional | boolean, boolean string | Set to `true` to load appointment with works of appointment. The default value of this attribute is `true`
`with_worklists` | Optional | boolean, boolean string | Set to `true` to load appointment with worklists of appointment. The default value of this attribute is `true`
`with_workdays` | Optional | boolean, boolean string | Set to `true` to load appointment with workdays of appointment. The default value of this attribute is `true`
`with_calculation` | Optional | boolean, boolean string | Set to `true` to load appointment with calculation of appointment. The default value of this attribute is `false`
`with_employees` | Optional | boolean, boolean string | Set to `true` to load appointment with employees of appointment. The default value of this attribute is `true`
`with_revenues` | Optional | boolean, boolean string | Set to `true` to load appointment with revenues of appointment. The default value of this attribute is `false`
`with_costs` | Optional | boolean, boolean string | Set to `true` to load appointment with costs of appointment. The default value of this attribute is `false`
`with_quotation` | Optional | boolean, boolean string | Set to `true` to load appointment with quotation of appointment. The default value of this attribute is `false`
`with_execute_works` | Optional | boolean, boolean string | Set to `true` to load appointment with execute_works of appointment. The default value of this attribute is `false`
`with_warranties` | Optional | boolean, boolean string | Set to `true` to load appointment with warranties of appointment. The default value of this attribute is `false`
`with_payment_reminder` | Optional | boolean, boolean string | Set to `true` to load appointment with payment_reminder of appointment. The default value of this attribute is `false`
`with_invoice` | Optional | boolean, boolean string | Set to `true` to load appointment with invoice of appointment. The default value of this attribute is `false`

**Request Body Example:**

```json
{
    "appointment_id": "07f69c00-c7b5-11eb-b290-d11f6e46c68e",
    "with_invoice": true,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointment` | Object | Object data of updated appointment

**Success Response Example:**

```json
{
    "appointment": {
        "id": "34540d80-5831-11ec-80cd-1b6bb5758996",
        "customer_id": "314e8020-5831-11ec-8dfc-99099ffafcfc",
        "status": 3,
        "status_description": "Processed",
        "type": 6,
        "type_description": "Payment Reminder",
        "start": "2021-12-15T14:14:49.000000Z",
        "end": "2021-12-18T14:14:49.000000Z",
        "include_weekend": true,
        "note": "This is seeder appointment",
        "created_at": "2021-12-08T14:14:49.000000Z",
        "in_process_at": "2021-12-11 15:14:49",
        "processed_at": null,
        "customer": null,
        "subs": [],
        "works": [
            {
                "id": "46bda120-5831-11ec-88c3-e70ed8e551b8",
                "status": 4,
                "status_description": "Unfinished",
                "quantity": 16,
                "quantity_unit": "cm2",
                "quantity_with_unit": "16 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 174,
                "formatted_unit_price": null,
                "total_price": 2784,
                "formatted_total_price": "€ 2.784,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-12-08T14:15:20.000000Z",
                "executed_at": "2021-12-08 15:15:20",
                "finished_at_appointment_id": "34540d80-5831-11ec-80cd-1b6bb5758996",
                "finished_at": "2021-12-08 15:15:20",
                "finish_note": null,
                "unfinished_at": "2021-12-08 15:15:20"
            },
            {
                "id": "46bdc960-5831-11ec-baa2-f56077bbd4ca",
                "status": 3,
                "status_description": "Finished",
                "quantity": 426,
                "quantity_unit": "m2",
                "quantity_with_unit": "426 m2",
                "description": "This is seeder appointment work",
                "unit_price": 100,
                "formatted_unit_price": null,
                "total_price": 42600,
                "formatted_total_price": "€ 42.600,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-12-08T14:15:20.000000Z",
                "executed_at": "2021-12-08 15:15:20",
                "finished_at_appointment_id": "34540d80-5831-11ec-80cd-1b6bb5758996",
                "finished_at": "2021-12-08 15:15:20",
                "finish_note": null
            }
        ],
        "finished_works": [
            {
                "id": "46bda120-5831-11ec-88c3-e70ed8e551b8",
                "status": 4,
                "status_description": "Unfinished",
                "quantity": 16,
                "quantity_unit": "cm2",
                "quantity_with_unit": "16 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 174,
                "formatted_unit_price": null,
                "total_price": 2784,
                "formatted_total_price": "€ 2.784,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-12-08T14:15:20.000000Z",
                "executed_at": "2021-12-08 15:15:20",
                "finished_at_appointment_id": "34540d80-5831-11ec-80cd-1b6bb5758996",
                "finished_at": "2021-12-08 15:15:20",
                "finish_note": null,
                "unfinished_at": "2021-12-08 15:15:20"
            },
            {
                "id": "46bdc960-5831-11ec-baa2-f56077bbd4ca",
                "status": 3,
                "status_description": "Finished",
                "quantity": 426,
                "quantity_unit": "m2",
                "quantity_with_unit": "426 m2",
                "description": "This is seeder appointment work",
                "unit_price": 100,
                "formatted_unit_price": null,
                "total_price": 42600,
                "formatted_total_price": "€ 42.600,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-12-08T14:15:20.000000Z",
                "executed_at": "2021-12-08 15:15:20",
                "finished_at_appointment_id": "34540d80-5831-11ec-80cd-1b6bb5758996",
                "finished_at": "2021-12-08 15:15:20",
                "finish_note": null
            }
        ],
        "worklists": [
            {
                "id": "33c3cee0-5831-11ec-a331-774aba4f7b72",
                "company_id": "31445190-5831-11ec-ad2a-df6ca196e45a",
                "workday_id": "32d2c800-5831-11ec-92dd-358f7ef584f9",
                "worklist_name": "Worklist Name 1",
                "status": 1,
                "status_description": "Prepared",
                "created_at": "2021-12-08T14:14:48.000000Z",
                "updated_at": "2021-12-08T14:14:48.000000Z"
            }
        ],
        "workdays": [
            {
                "id": "32d2c800-5831-11ec-92dd-358f7ef584f9",
                "date": "2021-12-27",
                "status": 1,
                "status_description": "Prepared"
            }
        ],
        "employees": [
            {
                "id": "3263f250-5831-11ec-861a-6b0ed404ed91",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 2,
                "employment_status_description": "Inactive"
            }
        ],
        "invoice": {
            "id": "a059edc0-5831-11ec-9aea-73d7237ec899",
            "company_id": "31445190-5831-11ec-ad2a-df6ca196e45a",
            "total": 45384,
            "formatted_total": "€ 45.384,00",
            "total_in_terms": 0,
            "formatted_total_in_terms": "€ 0,00",
            "total_out_terms": 0,
            "formatted_total_out_terms": "€ 0,00",
            "total_paid": 0,
            "formatted_total_paid": "€ 0,00",
            "total_unpaid": 45384,
            "formatted_total_unpaid": "€ 45.384,00",
            "status": 11,
            "status_description": "Sent to debt collector",
            "payment_method": "2",
            "payment_method_description": "Bank Transfer"
        },
        "calculation": null
    }
}
```

-------------------------------------------------------
### 5. Update Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/update`

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
`id` or `appointment_id` | Required | string, uuid | The selected ID of appointment that's going to be updated
`customer_id` | Required | uuid, string, exists in `customers` table | The customer that requested or will be having appointment with. Put their ID in this payload and it will automatically assigned to the customer
`start` | Required | date, format (yyyy-mm-dd) | The starting date of appointment
`end` | Required | date, format (yyyy-mm-dd) | The ending date of appointment
`include_weekend` | Optional | boolean, boolean string, numeric 1 or 0 | Set to `true` if the appointment include non-working days like saturday and sunday. Defaultly, this payload has value of false.
`type` | Required | numeric, numeric string, min:1, max:6 | The type of appointment, this represents the meta of appointment type/kind, to see more detail what numbers represent please see [Appointment Meta](/docs/Meta/Appointment.md) 
`note` | Optional | string, text | This payload can be used to put side note about this appointment and what should be paid attention about the appointment

**Request Body Example:**

```json
{
    "id": "402d4950-b596-11eb-9dd1-6732e058f436",
    "customer_id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
    "start": "2021-05-15 08:00:00",
    "end": "2021-05-18 12:00:00",
    "include_weekend": true,
    "type": 1,
    "note": "Fixing leaking rooftop",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save appointment"
}
```

**Notes:**

1. Only appointment with status of `Created` can be updated.

-------------------------------------------------------
### 6. Execute Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/execute`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of executing appointment

**Request Body Example:**

```json
{
    "id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully execute appointment."
}
```

**Notes:**

1. Only appointment with status of `Created` can be updated.
2. Executing appointment will change the appointment status to `InProcess`

-------------------------------------------------------
### 7. Process Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/process`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of executing appointment

**Request Body Example:**

```json
{
    "id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully process appointment."
}
```

-------------------------------------------------------
### 8. Cancel Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/cancel`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of cancelled appointment
`cancellation_cause` | Required | string, max: 265 characters | The cause of cancellation in short description
`cancellation_vault` | Required | numeric, min: 1, max: 2 | The vault of the cancellation, this represents who is held responsible to the cancellation of appointment, to see more detail what numbers represent please see [Appointment Meta](/docs/Meta/Appointment.md) 
`cancellation_note` | Required | string, text | The description or note about the cancellation, this contains the explanation of reason why the appointment is cancelled. This payload will provide the user to explain what he states at `cancellation_cause` in deeper detail and reasonable explanation.

**Request Body Example:**

```json
{
    "id": "402d4950-b596-11eb-9dd1-6732e058f436",
    "cancellation_cause": "Roofer is badly late",
    "cancellation_vault": 1,
    "cancellation_note": "Roofer agreed to be arrived at 9, but he did't show up until 10. We try to make many calls but get no answer, what a dissapointment. He showed up at 11 and say the excuse about traffic jam and so on and so forth",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully cancel appointment."
}
```

-------------------------------------------------------
### 9. Reschedule Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/reschedule`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of rescheduled appointment
`start` | Required | date, format (yyyy-mm-dd) | The starting date of appointment
`end` | Required | date, format (yyyy-mm-dd) | The ending date of appointment
`include_weekend` | Optional | boolean, boolean string, numeric 1 or 0 | Set to `true` if the appointment include non-working days like saturday and sunday. Defaultly, this payload has value of false.
`type` | Required | numeric, numeric string, min:1, max:6 | The type of appointment, this represents the meta of appointment type/kind, to see more detail what numbers represent please see [Appointment Meta](/docs/Meta/Appointment.md) 
`note` | Optional | string, text | This payload can be used to put side note about this appointment and what should be paid attention about the appointment

**Request Body Example:**

```json
{
    "appointment_id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
    "start": "2021-05-15",
    "end": "2021-05-18",
    "include_weekend": true,
    "type": 1,
    "note": "The reschedule of meeting because it was cancelled",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully reschedule appointment."
}
```

-------------------------------------------------------
### 10. Generate Invoice from Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/generate_invoice`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of executing appointment
`payment_method` | Required | numeric, numeric string | Payment method the customer prefer to use, to see what are the options of payment methods please see [Invoice Meta Documentation](/docs/Meta/Invoice.md) 

**Request Body Example:**

```json
{
    "id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
    "payment_method": 1,
}
```

**Success Response Example:**

```json
{
    "invoice": {
        "payment_method": "1",
        "customer_id": "eb6b2bd0-1bc4-11ec-8552-b5f89f23db3e",
        "invoiceable_type": "App\\Models\\Appointment",
        "invoiceable_id": "09993f80-1dc8-11ec-b56e-6f2e72081215",
        "company_id": "eb63c1a0-1bc4-11ec-abd0-f12a42f84c12",
        "total": 0,
        "id": "06e95140-1ddc-11ec-88d7-35d2b6b4125e",
        "updated_at": "2021-09-25T08:38:58.000000Z",
        "created_at": "2021-09-25T08:38:58.000000Z",
        "appointment": {
            "id": "09993f80-1dc8-11ec-b56e-6f2e72081215",
            "customer_id": "eb6b2bd0-1bc4-11ec-8552-b5f89f23db3e",
            "status": 5,
            "status_description": "Cancelled",
            "type": 1,
            "type_description": "Inspection",
            "start": "2021-05-14T22:00:00.000000Z",
            "end": "2021-05-17T22:00:00.000000Z",
            "include_weekend": true,
            "note": "Fixing leaking rooftop",
            "created_at": "2021-09-25T06:15:53.000000Z",
            "in_process_at": null,
            "processed_at": null,
            "calculated_at": null,
            "cancelled_at": "2021-09-25 08:15:53",
            "cancellation_vault": 1,
            "cancellation_vault_description": "Roofer",
            "cancellation_cause": "The rooder is terribly late",
            "cancellation_note": "oofer agreed to be arrived at 9, but he did't show up until 10. We try to make many calls but get no answer, what a dissapointment. He showed up at 11 and say the excuse about traffic jam and so on and so forth"
        }
    },
    "status": "success",
    "message": "Successfully generate invoice from appointment"
}
```

**Success Response Note:**

1. If the appointment already has an invoice, then this endpoint won't generate new invoice instead, returning existing invoice.
2. In `invoice` object, we will have `invoice.appointment` data. In other endpoint which implements invoicing like `quotation` for an example, we'll have `invoice.quotation`. The attribute of it depends on what is within the record of `invoiceable_type` (To define the model class) and `invoiceable_id` (To define ID of associated class).

-------------------------------------------------------
### 11. Delete Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/delete`

**Method:** `DELETE`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string, uuid | The ID of executing appointment
`force` | Optional | boolean, boolean string | Set this to `true` to permanently delete record from database. Permanent deletion is not revertable and require certain permission to execute this action.

**Request Body Example:**

```json
{
    "id": "2b6633c0-ee1a-11eb-afc9-f90464cdf390",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete appointment."
}
```

-------------------------------------------------------
### 12. Populate Deleted/Trashed Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/trasheds`

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
`search` | Optional | string | Searched keyword, will be matched through attributes of `cancellation_note`, `note`
`per_page` | Optional | numeric | Amount of data per page, default amount is 10
`type` | Optional | numeric, min:1, max:6  | The status code to populate only certain appointment with any type requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)
`status` | Optional | numeric, min:1, max:5 | The status code to populate only certain appointment with any status requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)
`cancellation_vault` | Optional | numeric, min:1, max:2 | The status code to populate only certain appointment with any status requested, detail see: [Appointment Meta](/docs/Meta/Appointment.md)

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`appointments` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "appointments": {
        "current_page": 2,
        "data": [
            {
                "id": "691051e0-ed23-11eb-a069-8b02febb1280",
                "customer": {
                    "id": "5a8668b0-ed23-11eb-8735-d34b492ad6ed",
                    "addresses": [],
                    "fullname": "Customer 553 of Company 1",
                    "email": "customer553@company1.com",
                    "phone": "8338059136000",
                    "second_phone": null
                },
                "status": 2,
                "status_description": "In Process",
                "type": 3,
                "type_description": "Execute Work",
                "start": "2021-07-20T08:36:30.000000Z",
                "end": "2021-07-24T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null
            },
            {
                "id": "6912fd80-ed23-11eb-8dcd-7bf2cf7aa3c2",
                "customer": {
                    "id": "5a8a0cc0-ed23-11eb-884d-a9264d379826",
                    "addresses": [],
                    "fullname": "Customer 784 of Company 1",
                    "email": "customer784@company1.com",
                    "phone": "3836682131320",
                    "second_phone": null
                },
                "status": 1,
                "status_description": "Created",
                "type": 6,
                "type_description": "Payment Reminder",
                "start": "2021-07-28T08:36:30.000000Z",
                "end": "2021-07-28T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z"
            },
            {
                "id": "69133c00-ed23-11eb-858e-8fa632462ff2",
                "customer": {
                    "id": "5a87c3b0-ed23-11eb-b014-f9dadfd730d7",
                    "addresses": [],
                    "fullname": "Customer 645 of Company 1",
                    "email": "customer645@company1.com",
                    "phone": "8806705850616",
                    "second_phone": null
                },
                "status": 5,
                "status_description": "Cancelled",
                "type": 5,
                "type_description": "Payment Pick-Up",
                "start": "2021-07-23T08:36:30.000000Z",
                "end": "2021-07-25T08:36:30.000000Z",
                "include_weekend": true,
                "note": "This is seeder appointment",
                "created_at": "2021-07-25T08:36:30.000000Z",
                "in_process_at": null,
                "processed_at": null,
                "calculated_at": null,
                "cancelled_at": null,
                "cancellation_vault": null,
                "cancellation_vault_description": "",
                "cancellation_cause": null,
                "cancellation_note": null
            }
        ],
        "first_page_url": "/?page=1",
        "from": 4,
        "last_page": 35,
        "last_page_url": "/?page=35",
        "links": [
            {
                "url": "/?page=1",
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "2",
                "active": true
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
                "url": "/?page=34",
                "label": "34",
                "active": false
            },
            {
                "url": "/?page=35",
                "label": "35",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=3",
        "path": "/",
        "per_page": "3",
        "prev_page_url": "/?page=1",
        "to": 6,
        "total": 103
    }
}
```

-------------------------------------------------------
### 13. Restore Appointment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/appointments/restore`

**Method:** `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `appointment_id` | Required | string | ID of restoring appointment

**Request Body Example:**

```json
{
    "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "appointment": {
        "id": "f9fd41b0-f113-11eb-bd31-c33ec36c220c",
        "customer": {
            "id": "f7506360-f113-11eb-99f2-8dbaf0d30003",
            "addresses": [],
            "fullname": "Customer 1 of Company 1",
            "email": "customer1@company1.com",
            "phone": "6909138017494",
            "second_phone": null
        },
        "status": 5,
        "status_description": "Cancelled",
        "type": 6,
        "type_description": "Payment Reminder",
        "start": "2021-08-05T08:56:06.000000Z",
        "end": "2021-08-05T08:56:06.000000Z",
        "include_weekend": true,
        "note": "This is seeder appointment",
        "created_at": "2021-07-30T08:56:06.000000Z",
        "in_process_at": "2021-08-01 10:56:06",
        "processed_at": "2021-08-02 10:56:06",
        "calculated_at": "2021-08-02 10:56:06",
        "cancelled_at": "2021-08-03 10:56:06",
        "cancellation_vault": 1,
        "cancellation_vault_description": "Roofer",
        "cancellation_cause": "Another cause no one knows",
        "cancellation_note": "Random cancellation note for appointment"
    },
    "status": "success",
    "message": "Successfully restore appointment."
}
```