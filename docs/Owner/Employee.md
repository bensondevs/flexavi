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
                "id": "171d92a0-f114-11eb-9850-050e7526874a",
                "title": "Invited Employee",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired",
                "user": null,
                "addresses": [],
                "inspections_count": 0
            },
            {
                "id": "176f3cd0-f114-11eb-a729-83330fbc07b9",
                "title": "Invited Employee",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 2,
                "employment_status_description": "Inactive",
                "user": null,
                "addresses": [],
                "inspections_count": 0
            },
            {
                "id": "f7e76280-f113-11eb-813a-7fca4c9f4000",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 2,
                "employment_status_description": "Inactive",
                "user": {
                    "id": "f51e96a0-f113-11eb-a28c-0fbb1c3154a1",
                    "fullname": "Flexavi Employee 1",
                    "birth_date": "1999-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "733629563",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac5e00-f114-11eb-9ff4-71a036bb5c2a",
                        "user_id": "f51e96a0-f113-11eb-a28c-0fbb1c3154a1",
                        "address": "Flexavi Employee 1 Address 1",
                        "house_number": "967",
                        "house_number_suffix": "X",
                        "zipcode": "461232",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f51e96a0-f113-11eb-a28c-0fbb1c3154a1"
                    },
                    {
                        "id": "17ac61a0-f114-11eb-b591-5ffcf2ed9c92",
                        "user_id": "f51e96a0-f113-11eb-a28c-0fbb1c3154a1",
                        "address": "Flexavi Employee 1 Address 2",
                        "house_number": "643",
                        "house_number_suffix": "X",
                        "zipcode": "875605",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f51e96a0-f113-11eb-a28c-0fbb1c3154a1"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e766d0-f113-11eb-b72e-1188d536aee7",
                "title": "Employee Title",
                "employee_type": 1,
                "employee_type_description": "Administrative",
                "employment_status": 1,
                "employment_status_description": "Active",
                "user": {
                    "id": "f5297760-f113-11eb-8c04-a30d97501592",
                    "fullname": "Flexavi Employee 2",
                    "birth_date": "1999-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "764756656",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee2@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac6530-f114-11eb-bdf5-dd2708703a5f",
                        "user_id": "f5297760-f113-11eb-8c04-a30d97501592",
                        "address": "Flexavi Employee 2 Address 1",
                        "house_number": "608",
                        "house_number_suffix": "X",
                        "zipcode": "345881",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f5297760-f113-11eb-8c04-a30d97501592"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e769e0-f113-11eb-8ef8-cfb3b1d0abf1",
                "title": "Employee Title",
                "employee_type": 1,
                "employee_type_description": "Administrative",
                "employment_status": 3,
                "employment_status_description": "Fired",
                "user": {
                    "id": "f5345510-f113-11eb-9f17-0f514d65c4c8",
                    "fullname": "Flexavi Employee 3",
                    "birth_date": "1996-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "541904122",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee3@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac68d0-f114-11eb-995e-c97772e12753",
                        "user_id": "f5345510-f113-11eb-9f17-0f514d65c4c8",
                        "address": "Flexavi Employee 3 Address 1",
                        "house_number": "119",
                        "house_number_suffix": "X",
                        "zipcode": "212657",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f5345510-f113-11eb-9f17-0f514d65c4c8"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e76c90-f113-11eb-b819-bd3514f1e3e9",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 2,
                "employment_status_description": "Inactive",
                "user": {
                    "id": "f53f29b0-f113-11eb-a3b3-a9db5730403e",
                    "fullname": "Flexavi Employee 4",
                    "birth_date": "1999-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "764393182",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee4@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac6c80-f114-11eb-99c3-85e54e580924",
                        "user_id": "f53f29b0-f113-11eb-a3b3-a9db5730403e",
                        "address": "Flexavi Employee 4 Address 1",
                        "house_number": "521",
                        "house_number_suffix": "X",
                        "zipcode": "142942",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f53f29b0-f113-11eb-a3b3-a9db5730403e"
                    },
                    {
                        "id": "17ac70a0-f114-11eb-9ee7-99345c714774",
                        "user_id": "f53f29b0-f113-11eb-a3b3-a9db5730403e",
                        "address": "Flexavi Employee 4 Address 2",
                        "house_number": "892",
                        "house_number_suffix": "X",
                        "zipcode": "291473",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f53f29b0-f113-11eb-a3b3-a9db5730403e"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e77000-f113-11eb-80f8-750f9405477a",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired",
                "user": {
                    "id": "f54b8ce0-f113-11eb-b57f-9f31960c3639",
                    "fullname": "Flexavi Employee 5",
                    "birth_date": "1999-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "510209132",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee5@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac7450-f114-11eb-817f-af1c838764b3",
                        "user_id": "f54b8ce0-f113-11eb-b57f-9f31960c3639",
                        "address": "Flexavi Employee 5 Address 1",
                        "house_number": "699",
                        "house_number_suffix": "X",
                        "zipcode": "112220",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f54b8ce0-f113-11eb-b57f-9f31960c3639"
                    },
                    {
                        "id": "17ac77f0-f114-11eb-8a31-5f266625a055",
                        "user_id": "f54b8ce0-f113-11eb-b57f-9f31960c3639",
                        "address": "Flexavi Employee 5 Address 2",
                        "house_number": "852",
                        "house_number_suffix": "X",
                        "zipcode": "345579",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f54b8ce0-f113-11eb-b57f-9f31960c3639"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e77340-f113-11eb-a798-b9eb9c28b981",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 1,
                "employment_status_description": "Active",
                "user": {
                    "id": "f5567470-f113-11eb-908c-011d59acf3bf",
                    "fullname": "Flexavi Employee 6",
                    "birth_date": "1997-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "114641193",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee6@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac7ba0-f114-11eb-8ead-43359e6c61c8",
                        "user_id": "f5567470-f113-11eb-908c-011d59acf3bf",
                        "address": "Flexavi Employee 6 Address 1",
                        "house_number": "625",
                        "house_number_suffix": "X",
                        "zipcode": "200766",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f5567470-f113-11eb-908c-011d59acf3bf"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e776b0-f113-11eb-97f7-cf9ec8b02197",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired",
                "user": {
                    "id": "f562b960-f113-11eb-9bee-0f7a1348f9d9",
                    "fullname": "Flexavi Employee 7",
                    "birth_date": "2000-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "429400900",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee7@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac7f40-f114-11eb-96ed-8b2b631f7d7a",
                        "user_id": "f562b960-f113-11eb-9bee-0f7a1348f9d9",
                        "address": "Flexavi Employee 7 Address 1",
                        "house_number": "116",
                        "house_number_suffix": "X",
                        "zipcode": "286196",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f562b960-f113-11eb-9bee-0f7a1348f9d9"
                    },
                    {
                        "id": "17ac82e0-f114-11eb-929b-f10fa17dd291",
                        "user_id": "f562b960-f113-11eb-9bee-0f7a1348f9d9",
                        "address": "Flexavi Employee 7 Address 2",
                        "house_number": "533",
                        "house_number_suffix": "X",
                        "zipcode": "915290",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f562b960-f113-11eb-9bee-0f7a1348f9d9"
                    }
                ],
                "inspections_count": 0
            },
            {
                "id": "f7e779e0-f113-11eb-a6f3-17307f5532d7",
                "title": "Employee Title",
                "employee_type": 2,
                "employee_type_description": "Roofer",
                "employment_status": 3,
                "employment_status_description": "Fired",
                "user": {
                    "id": "f56e0a10-f113-11eb-83c0-fbe0bb3c421e",
                    "fullname": "Flexavi Employee 8",
                    "birth_date": "1997-07-30",
                    "id_card_type": "id_card",
                    "id_card_number": "715249248",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee8@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                },
                "addresses": [
                    {
                        "id": "17ac8680-f114-11eb-842c-5b0cd7fcfd05",
                        "user_id": "f56e0a10-f113-11eb-83c0-fbe0bb3c421e",
                        "address": "Flexavi Employee 8 Address 1",
                        "house_number": "954",
                        "house_number_suffix": "X",
                        "zipcode": "574063",
                        "city": "Randon City",
                        "province": "Random Province",
                        "created_at": "2021-07-30T08:56:56.000000Z",
                        "updated_at": "2021-07-30T08:56:56.000000Z",
                        "deleted_at": null,
                        "laravel_through_key": "f56e0a10-f113-11eb-83c0-fbe0bb3c421e"
                    }
                ],
                "inspections_count": 0
            }
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
        "total": 29
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
### 4. View Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/employees/view`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`id` or `employee_id` | Required | uuid, string | The ID of viewed employee


**Request Body Example:**

```json
{
    "id": "07f69c00-c7b5-11eb-b290-d11f6e46c68e"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`employee` | Object | Object data of updated employee

**Success Response Example:**

```json
{
    "employee": {
        "id": "023d4300-f8e2-11eb-b6cb-f1ef24693c4c",
        "title": "Employee Title",
        "employee_type": 1,
        "employee_type_description": "Administrative",
        "employment_status": 1,
        "employment_status_description": "Active",
        "user": {
            "id": "0006a940-f8e2-11eb-ab6c-6df85b9247e7",
            "fullname": "Flexavi Employee 13",
            "birth_date": "2000-08-09",
            "id_card_type": "id_card",
            "id_card_number": "335297237",
            "phone": "999999999999",
            "phone_verified_status": false,
            "email": "employee13@flexavi.nl",
            "email_verified_status": false,
            "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
            "role": "employee"
        },
        "addresses": [
            {
                "address": "Flexavi Employee 13 Address 1",
                "house_number": "99",
                "house_number_suffix": "X",
                "zipcode": "218782",
                "city": "Randon City",
                "province": "Random Province"
            },
            {
                "address": "Flexavi Employee 13 Address 2",
                "house_number": "203",
                "house_number_suffix": "X",
                "zipcode": "874689",
                "city": "Randon City",
                "province": "Random Province"
            }
        ],
        "company": {
            "id": "01a35d40-f8e2-11eb-9988-bb964bca54a7",
            "company_name": "Company 1",
            "email": "company1@flexavi.com",
            "phone_number": "84113120",
            "vat_number": "28338180",
            "commerce_chamber_number": "1",
            "company_logo_url": "http://localhost:8000/storage/uploads/companies/logos/20210730125714.jpeg",
            "company_website_url": "www.randomwebsite.com",
            "visiting_address": {
                "city": "Random City",
                "street": "Custom Road",
                "zip_code": "67312",
                "house_number": 259,
                "house_number_suffix": "X"
            },
            "invoicing_address": {
                "city": "Random City",
                "street": "Custom Street",
                "zip_code": "65123",
                "house_number": 38,
                "house_number_suffix": "X"
            }
        },
        "today_inspections": []
    }
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
        "id": "7cf6cee0-e0d3-11eb-b344-299db28dffe4",
        "title": "Employee Title",
        "employee_type": "administrative",
        "employee_type_description": "",
        "employment_status": "fired",
        "employment_status_description": "",
        "user": {
            "id": "7a8b0d20-e0d3-11eb-815a-072e8f8e213d",
            "fullname": "Flexavi Employee 8",
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
    "status": "success",
    "message": "Successfully restore employee."
}
```