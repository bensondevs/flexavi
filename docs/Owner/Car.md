## Car

-------------------------------------------------------
### 1. Populate Company Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars`

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
`cars` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 2. Populate Free Company Cars
-------------------------------------------------------


**Endpoint:** `/api/dashboard/companies/cars`

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
`cars` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 3. Store Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`brand` | Required | string | Brand of car
`model` | Required | string | Model of car
`year` | Required | integer | Year of car issued
`car_name` | Required | string, unique | Name of the car 
`car_license` | Required | string | Car license number, the legal identification of the car
`insured` | Required | boolean | Insurance status, if not insured return false

**Request Body Example:**

```json
{
	"brand" : "Toyota",
    "model" : "Carry",
    "year" : "1998",
    "car_name" : "Suzuki BX 2012 Bulldozer",
    "car_license" : "BM 14386",
    "insured" : true,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "brand": "Suzuki",
        "model": "BX",
        "year": "2012",
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0"
    },
    "status": "success",
    "message": "Successfully save car data."
}
```

-------------------------------------------------------
### 4. Set Car Image
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/set_image`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of target car
`car_image` | Required | file, Image (PNG, JPEG, JPG, SVG) | Image of car

**Request Body Example:**

```json
{
    "id": "ffa7c790-ab59-11eb-b742-c328c1e0f2ee",
    "brand" : "<FILE>"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "car_image_url": "http://localhost:8000/storage/uploads/cars/20210609161619pp.jpeg",
        "brand": "Suzuki",
        "model": "BX",
        "year": 2012,
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "insured": 0,
        "status": "free"
    },
    "status": "success",
    "message": "Successfully set car image."
}
```

-------------------------------------------------------
### 4. Update Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`brand` | Required | string | Brand of car
`model` | Required | string | Model of car
`year` | Required | integer | Year of car issued
`car_name` | Required | string, unique | Name of the car 
`car_license` | Required | string | Car license number, the legal identification of the car
`insured` | Required | boolean | Insurance status, if not insured return false

**Request Body Example:**

```json
{
	"id": "ffa7c790-ab59-11eb-b742-c328c1e0f2ee",
	"brand" : "Toyota",
    "model" : "Carry",
    "year" : "1998",
    "car_name" : "Suzuki BX 2012 Bulldozer",
    "car_license" : "BM 14386",
    "insured" : true,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "brand": "Suzuki",
        "model": "BX",
        "year": "2012",
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0"
    },
    "status": "success",
    "message": "Successfully save car data."
}
```

-------------------------------------------------------
### 5. Delete Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/update`

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
`id` | Required | string | ID of deleted car
`force` | Optional | boolean | Force status, set to `true` to delete permanently

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
    "message": "Successully delete car."
}
```

-------------------------------------------------------
### 6. Company Trashed Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/trasheds`

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
`cars` | Object | The customer object, contains pagination information and array of `data`


**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 7. Restore Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/restore`

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
`id` | Required | string | ID of deleted car

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
    "message": "Successully restore car."
}
```