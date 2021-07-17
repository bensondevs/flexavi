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
                "id": "7cf6c7a0-e0d3-11eb-9ff2-d10f3f87ae90",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a40dab0-e0d3-11eb-b8af-d1f8e529af04",
                    "fullname": "Flexavi Employee 1",
                    "salutation": "Mr.",
                    "birth_date": "1997-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "774978482",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee1@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6c9e0-e0d3-11eb-914f-19b8da222f6e",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employee_type_description": "",
                "employment_status": "inactive",
                "employment_status_description": "",
                "user": {
                    "id": "7a4b8260-e0d3-11eb-a897-f3f563ed5849",
                    "fullname": "Flexavi Employee 2",
                    "salutation": "Mr.",
                    "birth_date": "1998-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "485669952",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee2@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cae0-e0d3-11eb-b0ff-119df6727a11",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a563060-e0d3-11eb-b6b1-31821a9c91ce",
                    "fullname": "Flexavi Employee 3",
                    "salutation": "Mr.",
                    "birth_date": "2000-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "228989700",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee3@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cbb0-e0d3-11eb-b6c2-eb71d7c71696",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a60e160-e0d3-11eb-9abd-e3b907eb27b0",
                    "fullname": "Flexavi Employee 4",
                    "salutation": "Mr.",
                    "birth_date": "1996-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "772838609",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee4@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cc80-e0d3-11eb-92fc-851643ef8617",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "inactive",
                "employment_status_description": "",
                "user": {
                    "id": "7a6b92c0-e0d3-11eb-80ad-4928421560e7",
                    "fullname": "Flexavi Employee 5",
                    "salutation": "Mr.",
                    "birth_date": "1999-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "479439307",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee5@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cd50-e0d3-11eb-b23f-1507416109c0",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a7629a0-e0d3-11eb-80a8-5975888bc04e",
                    "fullname": "Flexavi Employee 6",
                    "salutation": "Mr.",
                    "birth_date": "1996-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "980132572",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee6@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6ce20-e0d3-11eb-b1ae-275f21bc494c",
                "title": "Employee Title",
                "employee_type": "roofers",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a808260-e0d3-11eb-9059-df71c8f79e86",
                    "fullname": "Flexavi Employee 7",
                    "salutation": "Mr.",
                    "birth_date": "2001-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "432298860",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee7@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cee0-e0d3-11eb-b344-299db28dffe4",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "fired",
                "employment_status_description": "",
                "user": {
                    "id": "7a8b0d20-e0d3-11eb-815a-072e8f8e213d",
                    "fullname": "Flexavi Employee 8",
                    "salutation": "Mr.",
                    "birth_date": "2000-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "228111848",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee8@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6cfc0-e0d3-11eb-8b34-538251701dd7",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7a953ce0-e0d3-11eb-9a78-87f0765079bb",
                    "fullname": "Flexavi Employee 9",
                    "salutation": "Mr.",
                    "birth_date": "2001-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "282216992",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee9@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
            },
            {
                "id": "7cf6d080-e0d3-11eb-8755-452677ff5b35",
                "title": "Employee Title",
                "employee_type": "administrative",
                "employee_type_description": "",
                "employment_status": "active",
                "employment_status_description": "",
                "user": {
                    "id": "7aa0bb30-e0d3-11eb-9835-c9fc5619c9d6",
                    "fullname": "Flexavi Employee 10",
                    "salutation": "Mr.",
                    "birth_date": "1998-07-09",
                    "id_card_type": "id_card",
                    "id_card_number": "241825336",
                    "phone": "999999999999",
                    "phone_verified_at": null,
                    "profile_picture_path": "uploads/profile_pictures/20210503075156pp.jpeg",
                    "registration_code": null,
                    "email": "employee10@flexavi.nl",
                    "email_verified_at": null,
                    "deleted_at": null
                },
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
        "total": 17
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
                "employment_status": 1,
                "employment_status_description": "Active",
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
`employee_type` | Required | string or integer | Employee types available `Administrative` or `1`, `Roofer` or `2` 
`employee_status` | Optional | string or integer | Employee statuses available `Active` or `1`, `Inactive` or `2`, `Fired` or `3`. When this value is not specified, the default value will be `1`.


**Request Body Example:**

```json
{
    "title": "Roof Engineer",
	"employee_type": "Roofer",
	"employee_status": 1,
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
        "employment_status": 1,
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
                "employment_status": 1,
                "employment_status_description": "Inactive",
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
        "employment_s,
        "created_at": "2021-06-09T13:01:20.000000Z",
        "updated_at": "2021-06-09T16:49:48.000000Z",
        "deleted_at": null
    },
    "status": "success",
    "message": "Successfully restore employee."
}
```