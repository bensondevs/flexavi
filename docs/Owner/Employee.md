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
`per_page` | Optional | number | Amount of data per page, default amount is 10

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
                "id": "6f3b32e0-c923-11eb-8ae1-0d9369adeb90",
                "title": "Another Employee",
                "employee_type": "administrative",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": null,
                "address": "Another street",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "11178",
                "city": "Another City",
                "province": "Another Province"
            },
            {
                "id": "c90c1a80-c922-11eb-b1dc-51d432414c57",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "inactive",
                "employment_status_label": "Inactive",
                "user": {
                    "id": "c66433c0-c922-11eb-816b-8517c5c75b56",
                    "fullname": "Flexavi Employee 1",
                    "salutation": "Mr.",
                    "birth_date": "1996-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "135270112",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee1@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c2050-c922-11eb-a41c-27a86f48f68c",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employment_status": "fired",
                "employment_status_label": "Fired",
                "user": {
                    "id": "c66e75b0-c922-11eb-96a7-ddcae4aaf34b",
                    "fullname": "Flexavi Employee 2",
                    "salutation": "Mr.",
                    "birth_date": "1997-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "258789806",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee2@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c2370-c922-11eb-9329-d7ce8e141064",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "inactive",
                "employment_status_label": "Inactive",
                "user": {
                    "id": "c6791680-c922-11eb-aad2-07a0c351ecc1",
                    "fullname": "Flexavi Employee 3",
                    "salutation": "Mr.",
                    "birth_date": "2001-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "221440908",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee3@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c2630-c922-11eb-bf91-9ba62a9b346e",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "fired",
                "employment_status_label": "Fired",
                "user": {
                    "id": "c683b7d0-c922-11eb-9c9a-efdcc85c3736",
                    "fullname": "Flexavi Employee 4",
                    "salutation": "Mr.",
                    "birth_date": "1997-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "931663543",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee4@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c28f0-c922-11eb-b99f-ffb901640b39",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employment_status": "fired",
                "employment_status_label": "Fired",
                "user": {
                    "id": "c68e70c0-c922-11eb-b28f-67b8e26884e8",
                    "fullname": "Flexavi Employee 5",
                    "salutation": "Mr.",
                    "birth_date": "1996-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "295443276",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee5@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c2b90-c922-11eb-b009-bf254184b0d2",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employment_status": "inactive",
                "employment_status_label": "Inactive",
                "user": {
                    "id": "c698a550-c922-11eb-ba33-5ba356b4b91b",
                    "fullname": "Flexavi Employee 6",
                    "salutation": "Mr.",
                    "birth_date": "1996-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "236188367",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee6@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c2e30-c922-11eb-a83d-311c9215bc59",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": {
                    "id": "c6a33960-c922-11eb-959f-ab22c70fc9cd",
                    "fullname": "Flexavi Employee 7",
                    "salutation": "Mr.",
                    "birth_date": "1997-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "817708792",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee7@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c30d0-c922-11eb-ba12-1f24f325a43e",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "fired",
                "employment_status_label": "Fired",
                "user": {
                    "id": "c6ad8540-c922-11eb-9f95-9356da58b99a",
                    "fullname": "Flexavi Employee 8",
                    "salutation": "Mr.",
                    "birth_date": "2001-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "216775930",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee8@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            },
            {
                "id": "c90c3360-c922-11eb-8517-b3d102b9e640",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "active",
                "employment_status_label": "Active",
                "user": {
                    "id": "c6b98520-c922-11eb-a997-8b22a8139652",
                    "fullname": "Flexavi Employee 9",
                    "salutation": "Mr.",
                    "birth_date": "1998-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "700211875",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee9@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 2,
        "last_page_url": "/?page=2",
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
        "total": 11
    }
}
```

-------------------------------------------------------
### 2. Populate Inviteable Employees
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
`employee` | Object | Object data of updated employee
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

-------------------------------------------------------
### 6. Populate Trashed Employees
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/trasheds`

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
`employees` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "employees": {
        "current_page": 1,
        "data": [
            {
                "id": "c90c1a80-c922-11eb-b1dc-51d432414c57",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employment_status": "inactive",
                "employment_status_label": "Inactive",
                "user": {
                    "id": "c66433c0-c922-11eb-816b-8517c5c75b56",
                    "fullname": "Flexavi Employee 1",
                    "salutation": "Mr.",
                    "birth_date": "1996-06-09",
                    "id_card_type": "id_card",
                    "id_card_number": "135270112",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
                    "registration_code": null,
                    "email": "employee1@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
                "address": "Address Test",
                "house_number": "11",
                "house_number_suffix": "A",
                "zipcode": "117177",
                "city": "Any City",
                "province": "Any Province"
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
### 7. Restore Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/restore`

**Method:** `GET`

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
    "employee": {
        "id": "c90c1a80-c922-11eb-b1dc-51d432414c57",
        "user_id": "c66433c0-c922-11eb-816b-8517c5c75b56",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "photo_url": "https://dummyimage.com/300/09f/fff.png",
        "title": "Employee Title",
        "employee_type": "administrative",
        "employment_status": "inactive",
        "address": "Address Test",
        "house_number": "11",
        "house_number_suffix": "A",
        "zipcode": "117177",
        "city": "Any City",
        "province": "Any Province",
        "created_at": "2021-06-09T13:01:20.000000Z",
        "updated_at": "2021-06-09T16:49:48.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully restore employee."
}
```