## Customer

-------------------------------------------------------
### 1. Populate Company Customers
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`customers` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "customers": {
        "current_page": 1,
        "data": [
            {
                "id": "198e8710-c5d6-11eb-a1c3-75e3acac9bb2",
                "fullname": "Customer 1 of Company 1",
                "email": "customer1@Company 1.com",
                "phone": "115455235"
            },
            {
                "id": "198e8ba0-c5d6-11eb-b36b-1f45909ad7fe",
                "fullname": "Customer 2 of Company 1",
                "email": "customer2@Company 1.com",
                "phone": "327364052"
            },
            {
                "id": "198e8d80-c5d6-11eb-b8a8-737e93e62af1",
                "fullname": "Customer 3 of Company 1",
                "email": "customer3@Company 1.com",
                "phone": "276277318"
            },
            {
                "id": "198e8f30-c5d6-11eb-8f4f-ddcde50b5e97",
                "fullname": "Customer 4 of Company 1",
                "email": "customer4@Company 1.com",
                "phone": "674243377"
            },
            {
                "id": "198e90b0-c5d6-11eb-bacc-a79cb1aa6a8d",
                "fullname": "Customer 5 of Company 1",
                "email": "customer5@Company 1.com",
                "phone": "903922434"
            },
            {
                "id": "198e9230-c5d6-11eb-9b95-69f9480715c1",
                "fullname": "Customer 6 of Company 1",
                "email": "customer6@Company 1.com",
                "phone": "392495470"
            },
            {
                "id": "198e93a0-c5d6-11eb-a3eb-55bd17c7470b",
                "fullname": "Customer 7 of Company 1",
                "email": "customer7@Company 1.com",
                "phone": "624644876"
            },
            {
                "id": "198e9510-c5d6-11eb-b954-3560ba62d816",
                "fullname": "Customer 8 of Company 1",
                "email": "customer8@Company 1.com",
                "phone": "473465401"
            },
            {
                "id": "198e9680-c5d6-11eb-8774-4b9787ddc295",
                "fullname": "Customer 9 of Company 1",
                "email": "customer9@Company 1.com",
                "phone": "267552723"
            },
            {
                "id": "198e9800-c5d6-11eb-a268-c7a62893c049",
                "fullname": "Customer 10 of Company 1",
                "email": "customer10@Company 1.com",
                "phone": "706355448"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 10,
        "last_page_url": "/?page=10",
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
        "total": 50
    }
}
```

-------------------------------------------------------
### 2. Store Customer
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`fullname` | Required | string | Fullname of customer
`email` | Optional | string, unique, email | Email of the customer
`phone` | Required | string, phone number | Unique phone number of customer
`second_phone` | Optional | string, phone number | Second phone number of customer, cannot be equal to `phone` but not unique, can be using other user's phone number.

**Request Body Example:**

```json
{
    "fullname": "John Doe",
    "salutation": "mrs",
    "email": "john@doe.com",
    "phone": "1234567890",
    "second_phone": "1231231231"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`customer` | Object | Object of customer data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "customer": {
        "fullname": "John Doe",
        "salutation": "mr",
        "email": "john@doe.com",
        "phone": "1234567890",
        "company_id": "59322d20-c819-11eb-ac4a-5545e4062ed5",
        "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6",
        "updated_at": "2021-06-08T07:24:23.000000Z",
        "created_at": "2021-06-08T07:24:23.000000Z"
    },
    "status": "success",
    "message": "Successfully save customer data."
}
```

-------------------------------------------------------
### 3. View Customer
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/view`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of updated customer
`with_company` | Optional | string, boolean string | set this to `true` to load customer with its relation to company. If not set, the default value will be `false`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`customer` | Object | Object of customer data

```json
{
    "customer": {
        "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6",
        "company_id": "59322d20-c819-11eb-ac4a-5545e4062ed5",
        "fullname": "John Doe",
        "email": "john@doe.com",
        "phone": "1234567890",
        "second_phone": null,
        "created_at": "2021-06-08T07:24:23.000000Z",
        "updated_at": "2021-06-08T07:30:20.000000Z",
        "deleted_at": null
    }
}
```

-------------------------------------------------------
### 4. Update Customer
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/update`

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
`id` | Required | string | ID of updated customer
`fullname` | Required | string | Fullname of customer
`email` | Optional | string, unique, email | Email of the customer
`phone` | Required | string, phone number | Unique phone number of customer
`second_phone` | Optional | string, phone number | Second phone number of customer, cannot be equal to `phone` but not unique, can be using other user's phone number.

**Request Body Example:**

```json
{
    "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6",
    "fullname": "John Doe",
    "email": "john@doe.com",
    "phone": "1234567890",
    "second_phone": "1231231231"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`customer` | Object | Object of customer data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "customer": {
        "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6",
        "company_id": "59322d20-c819-11eb-ac4a-5545e4062ed5",
        "fullname": "John Doe",
        "email": "john@doe.com",
        "phone": "1234567890",
        "second_phone": null,
        "created_at": "2021-06-08T07:24:23.000000Z",
        "updated_at": "2021-06-08T07:30:20.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully save customer data."
}
```

-------------------------------------------------------
### 5. Delete Customer
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/update`

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
`id` | Required | string | ID of deleted customer

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
    "status": "success",
    "message": "Successully delete customer data."
}
```

-------------------------------------------------------
### 6. Populate Trashed Customers
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/trasheds`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`customers` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "customers": {
        "current_page": 1,
        "data": [
            {
                "id": "c88bcae0-c922-11eb-80c3-3d4feea41ff5",
                "fullname": "Customer 1 of Company 1",
                "email": "customer1@company1.com",
                "phone": "654721355",
                "second_phone": null
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
        "to": 1,
        "total": 1
    }
}
```

-------------------------------------------------------
### 7. Restore Customer
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/customers/restore`

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
`id` | Required | string | ID of deleted customer

**Request Body Example:**

```json
{
    "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`customer` | Object | Object of restored customer
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "customer": {
        "id": "c88bcae0-c922-11eb-80c3-3d4feea41ff5",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "registered_from": "web",
        "fullname": "Customer 1 of Company 1",
        "phone": "654721355",
        "second_phone": null,
        "created_at": "2021-06-09T13:01:19.000000Z",
        "updated_at": "2021-06-09T16:54:35.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully restore customer."
}
```