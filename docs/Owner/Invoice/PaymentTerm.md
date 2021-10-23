## Payment Term

-------------------------------------------------------
### 1. Populate Invoice Payment Term
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`payment_terms` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "payment_terms": {
        "current_page": 1,
        "data": [
            {
                "id": "34ddc0b0-33ab-11ec-92af-d706d515cdf2",
                "term_name": "Another Term from Input",
                "status": 1,
                "status_description": "Unpaid",
                "amount": "100.00",
                "due_date": "2021-07-30",
                "human_due_date": "Jul 30, 2021"
            },
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
        "to": 4,
        "total": 4
    }
}
```

-------------------------------------------------------
### 2. Store Payment Term
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`invoice_id` | Required | string, uuid string | The target invoice ID.
`term_name` | Required | string | Term name. Can shortly describe about the term.
`amount` | Required | string | integer, double, float | The amount of created term. This amount cannot exceed the invoice unterm-ed total left over. For an example, we have grand total of 1000 and we already created some payment terms that have sum of 900, then we can only give this payload value maximum of 100.
`status` | Required | integer | The status of payment term. To see the available value please see [Documentation](./docs/Meta/PaymentTerm.md)
`due_date` | Required | date, date string format: (YYYY-MM-DD) | The due date of the payment term.

**Request Body Example:**

```json
{
    "invoice_id": "42bc8f40-3111-11ec-a359-b5efda34b642",
    "term_name": "Term Name Example",
    "status": 1,
    "due_date": "2021-08-21",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update payment term status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save payment term."
}
```

-------------------------------------------------------
### 3. Update Payment Term
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms/update`

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
`id` | Required | string | ID of updated payment term
`term_name` | Required | string | Term name. Can shortly describe about the term.
`amount` | Required | string | integer, double, float | The amount of created term. This amount cannot exceed the invoice unterm-ed total left over. For an example, we have grand total of 1000 and we already created some payment terms that have sum of 900, then we can only give this payload value maximum of 100.
`status` | Required | integer | The status of payment term. To see the available value please see [Documentation](./docs/Meta/PaymentTerm.md)
`due_date` | Required | date, date string format: (YYYY-MM-DD) | The due date of the payment term.

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update PaymentTerm status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save paymentterm."
}
```

-------------------------------------------------------
### 4. Delete Payment Term
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms/delete`

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
`id` or `payment_term_id` | Required | string | ID of deleted payment term

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete payment term status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete paymentterm"
}
```

-------------------------------------------------------
### 5. Mark Payment Term as Paid
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms/mark_as_paid`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `payment_term_id` | Required | string | ID of target payment term

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of action status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully mark payment term as paid"
}
```

-------------------------------------------------------
### 5. Cancel Payment Term Paid Status
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/invoices/payment_terms/cancel_paid_status`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `payment_term_id` | Required | string | ID of target payment term

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of action status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully cancel payment term paid status"
}
```