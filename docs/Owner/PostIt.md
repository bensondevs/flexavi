## PostIt

-------------------------------------------------------
### 1. Populate Company Post It(s)
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/post_its`

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

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`post_its` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "post_its": {
        "current_page": 1,
        "data": [
            {
                "user_id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                "content": "Owner post it content #1",
                "user": {
                    "id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                    "fullname": "Flexavi Owner 1",
                    "birth_date": "1996-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "442343375",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "owner1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "owner"
                }
            },
            {
                "user_id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                "content": "Owner post it content #2",
                "user": {
                    "id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                    "fullname": "Flexavi Owner 1",
                    "birth_date": "1996-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "442343375",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "owner1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "owner"
                }
            },
            {
                "user_id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                "content": "Owner post it content #3",
                "user": {
                    "id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                    "fullname": "Flexavi Owner 1",
                    "birth_date": "1996-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "442343375",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "owner1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "owner"
                }
            },
            {
                "user_id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                "content": "Owner post it content #4",
                "user": {
                    "id": "91cb8580-4600-11ec-bd0e-5b9d218c7687",
                    "fullname": "Flexavi Owner 1",
                    "birth_date": "1996-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "442343375",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "owner1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "owner"
                }
            },
            {
                "user_id": "96a08ef0-4600-11ec-aeb6-a7d166012cc6",
                "content": "Employee post it content #1",
                "user": {
                    "id": "96a08ef0-4600-11ec-aeb6-a7d166012cc6",
                    "fullname": "Flexavi Employee 1",
                    "birth_date": "2000-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "719254594",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            },
            {
                "user_id": "96a08ef0-4600-11ec-aeb6-a7d166012cc6",
                "content": "Employee post it content #2",
                "user": {
                    "id": "96a08ef0-4600-11ec-aeb6-a7d166012cc6",
                    "fullname": "Flexavi Employee 1",
                    "birth_date": "2000-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "719254594",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee1@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            },
            {
                "user_id": "96ae6e90-4600-11ec-a8ef-19b31a29735f",
                "content": "Employee post it content #1",
                "user": {
                    "id": "96ae6e90-4600-11ec-a8ef-19b31a29735f",
                    "fullname": "Flexavi Employee 2",
                    "birth_date": "1997-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "821646656",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee2@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            },
            {
                "user_id": "96ae6e90-4600-11ec-a8ef-19b31a29735f",
                "content": "Employee post it content #2",
                "user": {
                    "id": "96ae6e90-4600-11ec-a8ef-19b31a29735f",
                    "fullname": "Flexavi Employee 2",
                    "birth_date": "1997-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "821646656",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee2@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            },
            {
                "user_id": "96b98800-4600-11ec-8ac5-a768a7c2ae70",
                "content": "Employee post it content #1",
                "user": {
                    "id": "96b98800-4600-11ec-8ac5-a768a7c2ae70",
                    "fullname": "Flexavi Employee 3",
                    "birth_date": "1998-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "900919780",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee3@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            },
            {
                "user_id": "96c51000-4600-11ec-b584-fb3da55a4687",
                "content": "Employee post it content #1",
                "user": {
                    "id": "96c51000-4600-11ec-b584-fb3da55a4687",
                    "fullname": "Flexavi Employee 4",
                    "birth_date": "2001-11-15",
                    "id_card_type": 1,
                    "id_card_type_description": "National ID Card",
                    "id_card_number": "356675746",
                    "phone": "999999999999",
                    "phone_verified_status": false,
                    "email": "employee4@flexavi.nl",
                    "email_verified_status": false,
                    "profile_picture": "http://localhost:8000/storage/uploads/profile_pictures/20210503075156pp.jpeg",
                    "role": "employee"
                }
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 4,
        "last_page_url": "/?page=4",
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
        "total": 34
    }
}
```

-------------------------------------------------------
### 2. Store Post It
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/post_its/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`content` | Required | string, text | The content of Post It

**Request Body Example:**

```json
{
    "status": "success",
    "message": "Successfully save post it."
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Update postit status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save post it."
}
```

-------------------------------------------------------
### 3. Update Post It
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/post_its/update`

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
`id` or `post_it_id` | Required | string | ID of updated Post It
`content` | Required | string, text | The content of Post It

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "content": "Lorem ipsum dolor sit amet"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update PostIt status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save post it."
}
```

-------------------------------------------------------
### 4. Assign User to Post It
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/post_its/assign_user`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `post_it_id` | Required | string, uuid | ID of Post It target
`assigned_user_id` | Requirec | string, uuid | ID of assigned user target

**Request Body Example:**

```json
{
    "post_it_id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
    "assigned_user_id": "96c51000-4600-11ec-b584-fb3da55a4687"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Post It status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully assign user to post it."
}
```

-------------------------------------------------------
### 5. Unassign User from Post It
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/post_its/unassign_user`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `post_it_id` | Required | string, uuid | ID of Post It target
`assigned_user_id` | Requirec | string, uuid | ID of assigned user target

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update Post It status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully assign user to post it."
}
```

-------------------------------------------------------
### 6. Delete Post It
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
`id` | Required | string | ID of deleted postit

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete postit status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete postit"
}
```

-------------------------------------------------------
### 5. Restore PostIt
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
`id` | Required | string | ID of updated postit

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore postit status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore postit."
}
```