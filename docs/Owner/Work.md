## Work

-------------------------------------------------------
### 1. Populate Company Works
-------------------------------------------------------

**Endpoint:** `/`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10
`status` | Optional | number | The works with stated status will be the only works populated. To see what are those statuses see at [Work Meta](/docs/Meta/Work.md)
`created_after` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been created after the specified date
`created_before` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been created before the specified date
`last_updated_after` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been updated after the specified date 
`last_updated_before` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been updated after the specified date 
`executed_after` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been executed after the specified date 
`executed_before` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been executed after the specified date 
`finished_after` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been finished after the specified date 
`finished_before` | Optional | date, date string format: YYYY-MM-DD | Set the value of this parameter and the return data will be only works that have been finished after the specified date 
`unit_price_above` | Optional | numeric | Set the value of this parameter and the return data will be only works that have unit price more than or equal to the specified price 
`unit_price_below` | Optional | numeric | Set the value of this parameter and the return data will be only works that have unit price more than or equal to the specified price 
`with_quotation` | Optional | boolean, boolean string | Set the value for this parameter and the list of works will be loaded with the relation with `quotation` if any
`with_executions_count` | Optional | boolean, boolean string | Set the value for this parameter and the list of works will be loaded with total execution count of the work.
`order_by_quantity` | Optional | string, `asc` or `desc` OR `ASC` or `DESC` | Set the value for this parameter and the result will be ordered according to specified order (Ascending or Descending) in the value of `quantity`
`order_by_unit_price` | Optional | string, `asc` or `desc` OR `ASC` or `DESC` | Set the value for this parameter and the result will be ordered according to specified order (Ascending or Descending) in the value of `unit_price`
`order_by_total_price` | Optional | string, `asc` or `desc` OR `ASC` or `DESC` | Set the value for this parameter and the result will be ordered according to specified order (Ascending or Descending) in the value of `total_price`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`work` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "works": {
        "current_page": 1,
        "data": [
            {
                "id": "953cba40-0571-11ec-9290-c7973b5eb97a",
                "status": 2,
                "status_description": "In Process",
                "quantity": 326,
                "quantity_unit": "m",
                "quantity_with_unit": "326 m",
                "description": "This is seeder appointment work",
                "unit_price": 10,
                "formatted_unit_price": null,
                "total_price": 3260,
                "formatted_total_price": "€ 3.260,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33"
            },
            {
                "id": "953ca7d0-0571-11ec-ab78-71d235358666",
                "status": 4,
                "status_description": "Unfinished",
                "quantity": 373,
                "quantity_unit": "dm3",
                "quantity_with_unit": "373 dm3",
                "description": "This is seeder appointment work",
                "unit_price": 11,
                "formatted_unit_price": null,
                "total_price": 4103,
                "formatted_total_price": "€ 4.103,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33",
                "finished_at_appointment_id": "8e2df790-0571-11ec-97ec-9962dd6e15de",
                "finished_at": "2021-08-25 08:56:33",
                "unfinished_at": "2021-08-25 08:56:33"
            },
            {
                "id": "953e3300-0571-11ec-a079-790892d6788c",
                "status": 2,
                "status_description": "In Process",
                "quantity": 235,
                "quantity_unit": "cm2",
                "quantity_with_unit": "235 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 11,
                "formatted_unit_price": null,
                "total_price": 2817.65,
                "formatted_total_price": "€ 2.817,65",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33"
            },
            {
                "id": "961d2380-0571-11ec-a5b5-59970353b92b",
                "status": 3,
                "status_description": "Finished",
                "quantity": 888,
                "quantity_unit": "cm2",
                "quantity_with_unit": "888 cm2",
                "description": "This is seeder quotation work",
                "unit_price": 11,
                "formatted_unit_price": null,
                "total_price": 9768,
                "formatted_total_price": "€ 9.768,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:34.000000Z",
                "executed_at": "2021-08-25 08:56:34",
                "finished_at_appointment_id": "8f3ebd70-0571-11ec-9faf-b71e678a1af5",
                "finished_at": "2021-08-25 08:56:34"
            },
            {
                "id": "953c0b10-0571-11ec-ae95-c988bad82bc0",
                "status": 2,
                "status_description": "In Process",
                "quantity": 785,
                "quantity_unit": "m",
                "quantity_with_unit": "785 m",
                "description": "This is seeder appointment work",
                "unit_price": 12,
                "formatted_unit_price": null,
                "total_price": 10267.8,
                "formatted_total_price": "€ 10.267,80",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33"
            },
            {
                "id": "953d4c80-0571-11ec-8721-574be7057040",
                "status": 1,
                "status_description": "Created",
                "quantity": 249,
                "quantity_unit": "cm2",
                "quantity_with_unit": "249 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 12,
                "formatted_unit_price": null,
                "total_price": 3256.92,
                "formatted_total_price": "€ 3.256,92",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z"
            },
            {
                "id": "953e44e0-0571-11ec-8dc6-577aadb0faeb",
                "status": 2,
                "status_description": "In Process",
                "quantity": 986,
                "quantity_unit": "l",
                "quantity_with_unit": "986 l",
                "description": "This is seeder appointment work",
                "unit_price": 12,
                "formatted_unit_price": null,
                "total_price": 14316.72,
                "formatted_total_price": "€ 14.316,72",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33"
            },
            {
                "id": "953ffe60-0571-11ec-90b1-8b086c59982a",
                "status": 1,
                "status_description": "Created",
                "quantity": 547,
                "quantity_unit": "m",
                "quantity_with_unit": "547 m",
                "description": "This is seeder appointment work",
                "unit_price": 13,
                "formatted_unit_price": null,
                "total_price": 7111,
                "formatted_total_price": "€ 7.111,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z"
            },
            {
                "id": "953cf860-0571-11ec-a5a8-0516045302a8",
                "status": 2,
                "status_description": "In Process",
                "quantity": 332,
                "quantity_unit": "cm2",
                "quantity_with_unit": "332 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 14,
                "formatted_unit_price": null,
                "total_price": 5624.08,
                "formatted_total_price": "€ 5.624,08",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z",
                "executed_at": "2021-08-25 08:56:33"
            },
            {
                "id": "953d7af0-0571-11ec-80fa-9fc044c73697",
                "status": 1,
                "status_description": "Created",
                "quantity": 203,
                "quantity_unit": "l",
                "quantity_with_unit": "203 l",
                "description": "This is seeder appointment work",
                "unit_price": 14,
                "formatted_unit_price": null,
                "total_price": 2842,
                "formatted_total_price": "€ 2.842,00",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00",
                "created_at": "2021-08-25T06:56:33.000000Z"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 32,
        "last_page_url": "/?page=32",
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
                "url": "/?page=31",
                "label": "31",
                "active": false
            },
            {
                "url": "/?page=32",
                "label": "32",
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
        "total": 316
    }
}
```

-------------------------------------------------------
### 2. Populate Finished Works within an Appointment
-------------------------------------------------------

**Special Note:** 

```
This endpoint is unique. This endpoint allows you to see works which is being done at certain appointment. If you use Normal Populate Works endpoint with parameter of appointment_id and status of finished, it will populate you appointment's works which have been done AND attached to specified appointment, 

whereas certain works can possibly be having more than one appointment (in case of certain condition that forces the company to re-attach the same work at different appointment), thus, we need this endpoint to see all works that have been done EXACTLY at the specified requested appointment especially for those works which have more than one appointment. Because if we record the appointment more than once, then it will disturbing calculation data in which the work will be counted as revenue more than once and we want to avoid this.
```
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
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10
`status` | Optional | number | The works with stated status will be the only works populated. To see what are those statuses see at [Work Meta](/docs/Meta/Work.md)
`appointment_id` | Optional | UUID, string | Specify the value for this parameter then the data will only load works that have been attached to specified appointment

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`work` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "works": {
        "current_page": 1,
        "data": [
            {
                "id": "f8893230-016f-11ec-9137-731509895599",
                "status": 1,
                "status_description": "Created",
                "quantity": 262,
                "quantity_unit": "l",
                "quantity_with_unit": "262 l",
                "description": "This is seeder appointment work",
                "unit_price": 101,
                "unit_total": 26462,
                "formatted_unit_total": "€ 26.462,00",
                "total_price": 32019.02,
                "formatted_total_price": "€ 32.019,02",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00"
            },
            {
                "id": "f8893520-016f-11ec-b266-51054c9de3b3",
                "status": 1,
                "status_description": "Created",
                "quantity": 18,
                "quantity_unit": "cm2",
                "quantity_with_unit": "18 cm2",
                "description": "This is seeder appointment work",
                "unit_price": 194,
                "unit_total": 3492,
                "formatted_unit_total": "€ 3.492,00",
                "total_price": 4225.32,
                "formatted_total_price": "€ 4.225,32",
                "total_paid": 0,
                "formatted_total_paid": "€ 0,00"
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
        "to": 2,
        "total": 2
    }
}
```

-------------------------------------------------------
### 2. Store Work
-------------------------------------------------------

**Endpoint:** `/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------


**Request Body Example:**

```json
{
    
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update work status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save work."
}
```

-------------------------------------------------------
### 3. Update Work
-------------------------------------------------------

**Endpoint:** `/update`

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
`id` | Required | string | ID of updated work

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Work status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save work."
}
```

-------------------------------------------------------
### 4. Delete Work
-------------------------------------------------------

**Endpoint:** `/delete`

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
`id` | Required | string | ID of deleted work

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete work status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete work"
}
```

-------------------------------------------------------
### 5. Restore Work
-------------------------------------------------------

**Endpoint:** `/restore`

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
`id` | Required | string | ID of updated work

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore work status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore work."
}
```