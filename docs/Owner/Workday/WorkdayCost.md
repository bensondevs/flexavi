## WorkdayCost

-------------------------------------------------------
### 1. Populate WorkdayCost
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/workdays/costs`

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
`workdaycost` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    
}
```

-------------------------------------------------------
### 2. Store WorkdayCost
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
`status` | String | Update workdaycost status
`message` | String | Message response for the user


**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save workdaycost."
}
```

-------------------------------------------------------
### 3. Update WorkdayCost
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
`id` | Required | string | ID of updated workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of update WorkdayCost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save workdaycost."
}
```

-------------------------------------------------------
### 4. Delete WorkdayCost
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
`id` | Required | string | ID of deleted workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete workdaycost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete workdaycost"
}
```

-------------------------------------------------------
### 5. Restore WorkdayCost
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
`id` | Required | string | ID of updated workdaycost

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of restore workdaycost status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully restore workdaycost."
}
```