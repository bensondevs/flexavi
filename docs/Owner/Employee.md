## Employee

-------------------------------------------------------
### 1. Populate Company Employees
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of employee

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`employees` | Object | The employee object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "employees": {
        "current_page": 1,
        "data": [
            {
                "id": "1a1f15b0-c5d6-11eb-9310-41d7d5a11d7d",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employment_status": "fired",
                "employment_status_label": "Active",
                "user": {
                    "id": "176da550-c5d6-11eb-8b32-634ab0783981",
                    "fullname": "Flexavi Employee 1",
                    "salutation": "Mr.",
                    "birth_date": "1999-06-05",
                    "id_card_type": "id_card",
                    "id_card_number": "210497319",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "address": "11, A Road Name",
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee1@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                }
            },
            {
                "id": "1a1f1900-c5d6-11eb-ae43-e364c44d1ec4",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": {
                    "id": "17780880-c5d6-11eb-829f-a1bbd715b573",
                    "fullname": "Flexavi Employee 2",
                    "salutation": "Mr.",
                    "birth_date": "2001-06-05",
                    "id_card_type": "id_card",
                    "id_card_number": "886719379",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "address": "11, A Road Name",
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee2@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                }
            },
            {
                "id": "1a1f1ae0-c5d6-11eb-9032-7f2a66be4f07",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": null
            },
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 3,
        "last_page_url": "/?page=3",
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
        "total": 28
    }
}
```

-------------------------------------------------------
### 2. Populate Company Employees
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/inviteable`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of employee

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`employees` | Object | The employee object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "employees": {
        "current_page": 1,
        "data": {
            "27": {
                "id": "2124a3a0-c5d6-11eb-b76c-09197012e8a7",
                "title": "Invited Employee",
                "employee_type": "roofers",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": null
            }
        },
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
### 3. Store Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`title` | Required | string | The title of the employee, usually title given by the company
`employee_type` | Require | string | Employee types available `administrative` or `roofer`
`employee_status` | Optional | string | Employee statuses available `active`, `inactive`, `fired`. When not input, automatically set to `active`

**Request Body Example:**

```json
{
    "title": "Roof Engineer",
	"employee_type": "roofer",
	"employee_status": "active",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`employee` | Object | Object data of stored employee
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "employee": {
        "title": "Another Employee",
        "employee_type": "administrative",
        "employment_status": "active",
        "company_id": "198874a0-c5d6-11eb-896e-057d244ae1fa",
        "id": "07f69c00-c7b5-11eb-b290-d11f6e46c68e",
        "updated_at": "2021-06-07T17:23:10.000000Z",
        "created_at": "2021-06-07T17:23:10.000000Z"
    },
    "status": "success",
    "message": "Successfully save employee data."
}
```

-------------------------------------------------------
### 4. Update Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`id` | Required | string | The ID of employee
`title` | Required | string | The title of the employee, usually title given by the company
`employee_type` | Require | string | Employee types available `administrative` or `roofer`
`employee_status` | Optional | string | Employee statuses available `active`, `inactive`, `fired`. When not input, automatically set to `active`

**Request Body Example:**

```json
{
	"id": "07f69c00-c7b5-11eb-b290-d11f6e46c68e",
    "title": "Another Employee",
	"employee_type": "administrative",
	"employee_status": "active",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`employee` | Object | Object data of stored employee
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "employee": {
        "id": "07f69c00-c7b5-11eb-b290-d11f6e46c68e",
        "user_id": null,
        "company_id": "198874a0-c5d6-11eb-896e-057d244ae1fa",
        "photo_url": null,
        "title": "Another Employee",
        "employee_type": "administrative",
        "employment_status": "active",
        "created_at": "2021-06-07T17:23:10.000000Z",
        "updated_at": "2021-06-07T17:23:10.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully save employee data."
}
```

-------------------------------------------------------
### 5. Delete Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/delete`

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
`id` | Required | string | ID of deleted employee

**Request Body Example:**

```json
{
    "id": "b42c7ff0-b609-11eb-b4b6-97ec8e29fb6b"
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
    "message": "Successfully delete employee."
}
```