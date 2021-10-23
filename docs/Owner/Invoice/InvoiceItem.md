## Invoice Items

-------------------------------------------------------
### 1. Populate Invoice Items
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/items`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `invoice_id` | Required | uuid, string uuid | Target Invoice ID.
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of invoice items
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Request Body Example:**

```json
{
    "invoice_id": "04dba750-e957-11eb-aaca-735b1c824b4d"
}
```

**Success Response Example:**

```json
{
    "payment_terms": {
        "current_page": 1,
        "data": [
            {
                "id": "478e10b0-3111-11ec-bd9e-7b6ff846c300",
                "term_name": "Another Term Name",
                "status": 2,
                "status_description": "Paid",
                "amount": "446.67",
                "due_date": "2021-10-27",
                "human_due_date": "Oct 27, 2021"
            },
            {
                "id": "478e1450-3111-11ec-8066-b9c21f26aae9",
                "term_name": "Another Term Name",
                "status": 2,
                "status_description": "Paid",
                "amount": "446.67",
                "due_date": "2021-10-24",
                "human_due_date": "Oct 24, 2021"
            },
            {
                "id": "478e17f0-3111-11ec-a4e4-bb38054abf8c",
                "term_name": "Another Term Name",
                "status": 1,
                "status_description": "Unpaid",
                "amount": "446.67",
                "due_date": "2021-10-20",
                "human_due_date": "Oct 20, 2021"
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
        "to": 3,
        "total": 3
    }
}
```

-------------------------------------------------------
### 2. Store Invoice Item
-------------------------------------------------------

**Note:**

Only invoice with status of "Created / Draft" or has `status` column value of 1 that possible to be added with new invoice item.

**Endpoint:** `/api/dashboard/companies/invoices/items/store`

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
`item_name` | Required | string | The name of item in invoice.
`description` | Required | numeric, double, integer | The description about the invoice item.
`quantity` | Required | numeric, integer | Quantity of the item.
`quantity_unit` | Required | string | The unit of invoice item. Usually this can be filled with the unit of service/item like `cm`, `m3`, `hour`, `day`, and etc
`amount` | Required | numeric, numeric string, double | The amount charged to customer per `unit`.

**Request Body Example:**

```json
{
    "invoice_id": "04dba750-e957-11eb-aaca-735b1c824b4d",
    "item_name": "Item Name Example",
    "description": "Item Name Description",
    "quantity": 1,
    "quantity_unit": "m2",
    "amount": 900,
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save invoice item."
}
```

-------------------------------------------------------
### 3. Update Invoice Item
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/items/update`

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
`id` or `invoice_item_id` | Required | uuid, string uuid | Target Invoice Item ID. 
`item_name` | Required | string | The name of item in invoice.
`description` | Required | numeric, double, integer | The description about the invoice item.
`quantity` | Required | numeric, integer | Quantity of the item.
`quantity_unit` | Required | string | The unit of invoice item. Usually this can be filled with the unit of service/item like `cm`, `m3`, `hour`, `day`, and etc
`amount` | Required | numeric, numeric string, double | The amount charged to customer per `unit`.

**Request Body Example:**

```json
{
    "invoice_item_id": "04dba750-e957-11eb-aaca-735b1c824b4d",
    "item_name": "Item Name Example",
    "description": "Item Name Description",
    "quantity": 1,
    "quantity_unit": "m2",
    "amount": 900,
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save invoice item."
}
```

-------------------------------------------------------
### 4. Delete Invoice Item
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/items/delete`

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
`id` or `invoice_item_id` | Required | uuid, string uuid | Target Invoice Item ID. 

**Request Body Example:**

```json
{
    "invoice_item_id": "04dba750-e957-11eb-aaca-735b1c824b4d",
}
```

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete invoice item."
}
```