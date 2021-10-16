## WorklistAppointment

-------------------------------------------------------
### 1. Populate Worklist Appointments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/worklists/appointments`

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
`appointments` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    
}
```

-------------------------------------------------------
### 2. Store WorklistAppointment
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
`status` | String | Update worklistappointment status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save worklistappointment."
}
```

-------------------------------------------------------
### 3. Update WorklistAppointment
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
`id` | Required | string | ID of updated worklistappointment

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update WorklistAppointment status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save worklistappointment."
}
```

-------------------------------------------------------
### 4. Delete WorklistAppointment
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
`id` | Required | string | ID of deleted worklistappointment

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete worklistappointment status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete worklistappointment"
}
```

-------------------------------------------------------
### 5. Restore WorklistAppointment
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
`id` | Required | string | ID of updated worklistappointment

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore worklistappointment status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore worklistappointment."
}
```