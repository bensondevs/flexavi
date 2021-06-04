## Company

-------------------------------------------------------
### 1. Register Company
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/register`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`visiting_addresss_street` | Required | string | Visiting address street 
`visiting_addresss_house_number` | Required | string | Visiting address house number
`visiting_addresss_house_number_suffix` | Optional | string | Visiting address house number suffix
`visiting_addresss_zip_code` | Required | string | Visiting address zip code
`visiting_addresss_city` | Required | string | Visiting address city

`invoicing_addresss_street` | Required | string | Invoicing address street  
`invoicing_addresss_house_number` | Required | string | Invoicing address house number
`invoicing_addresss_house_number_suffix` | Optional | string | Invoicing address house number suffix
`invoicing_addresss_zip_code` | Required | string | Invoicing address zip code
`invoicing_addresss_city` | Required | string | Invoicing address city
`company_name` | Required | string, unique, Unique company name
`email` | Required | string, unique | Email of the company
`phone_number` | Required | string, unique, numeric | The phone number of the company
`vat_number` | Required | string, unique | VAT number of the company
`commerce_chamber_number` | Required | string | The value of the ID
`company_website_url` | Required | string, unique | Valid address of the user


**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`company` | Object | Object data of updated company
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "company": {
        "company_name": "Another Company",
        "email": "company@flexavi.com",
        "phone_number": "333111222000",
        "vat_number": "111000222111",
        "commerce_chamber_number": "121",
        "company_website_url": "https://company.test.com/",
        "visiting_address": {
            "street": "11, Visited Address Street",
            "house_number": "11",
            "house_number_suffix": "A",
            "zip_code": "111111",
            "city": "Visited City"
        },
        "invoicing_address": {
            "street": "22, Invoiced Address Street",
            "house_number": "22",
            "house_number_suffix": "A",
            "zip_code": "22222",
            "city": "Invoiced City"
        },
        "id": "13b96bc0-c496-11eb-b40f-fb944d91988d"
    },
    "status": "success",
    "message": "Successfully save company data."
}
```

-------------------------------------------------------
### 2. User's Company
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/user`

**Method:** `GET`

**Headers:**

 Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`company` | Object | Object of company data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "company": {
        "id": "8f142910-c39b-11eb-bc07-f5fae7d28d75",
        "company_name": "Company 1",
        "visiting_address": {
            "city": "Random City",
            "street": "Custom Road",
            "zip_code": "67312",
            "house_number": 96,
            "house_number_suffix": "X"
        },
        "invoicing_address": {
            "city": "Random City",
            "street": "Custom Street",
            "zip_code": "65123",
            "house_number": 51,
            "house_number_suffix": "X"
        },
        "email": "company1@flexavi.com",
        "phone_number": "5549100",
        "vat_number": "24745309",
        "commerce_chamber_number": "29",
        "company_logo_url": "http://backend.test/storage/uploads/cars/20210503070400pp.jpeg",
        "company_website_url": "www.randomwebsite.com"
    }
}
```

-------------------------------------------------------
### 3. Update Company
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/update`

**Method:** `POST`

**Headers:**

 Header Name | Value
-------------|------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`visiting_addresss_street` | Required | string | Visiting address street 
`visiting_addresss_house_number` | Required | string | Visiting address house number
`visiting_addresss_house_number_suffix` | Optional | string | Visiting address house number suffix
`visiting_addresss_zip_code` | Required | string | Visiting address zip code
`visiting_addresss_city` | Required | string | Visiting address city
`company_name` | Required | string, unique, Unique company name
`invoicing_addresss_street` | Required | string | Invoicing address street  
`invoicing_addresss_house_number` | Required | string | Invoicing address house number
`invoicing_addresss_house_number_suffix` | Optional | string | Invoicing address house number suffix
`invoicing_addresss_zip_code` | Required | string | Invoicing address zip code
`invoicing_addresss_city` | Required | string | Invoicing address city

`email` | Required | string, unique | Email of the company
`phone_number` | Required | string, unique, numeric | The phone number of the company
`vat_number` | Required | string, unique | VAT number of the company
`commerce_chamber_number` | Required | string | The value of the ID
`company_website_url` | Required | string, unique | Valid address of the user

**Request Body Example:**
```json
{
    "visiting_addresss_street": "11, Visited Address Street",
    "visiting_addresss_house_number": "11",
    "visiting_addresss_house_number_suffix": "A",
    "visiting_addresss_zip_code": "11111",
    "visiting_addresss_city": "Visited City",
    "invoicing_addresss_street": "22, Invoiced Address Street",
    "invoicing_addresss_house_number": "22",
    "invoicing_addresss_house_number_suffix": "B",
    "invoicing_addresss_zip_code": "22222",
    "invoicing_addresss_city": "Invoiced City",
    "company_name": "Another Company",
    "email": "company@flexavi.com",
    "phone_number": "333111222000",
    "vat_number": "111000222111",
    "commerce_chamber_number": "121",
    "company_website_url": "https://www.flexavi.company/",
}
```

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`company` | Object | Object data of updated company
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "company": {
        "company_name": "Another Company",
        "email": "company@flexavi.com",
        "phone_number": "333111222000",
        "vat_number": "111000222111",
        "commerce_chamber_number": "121",
        "company_website_url": "https://company.test.com/",
        "visiting_address": {
            "street": "11, Visited Address Street",
            "house_number": "11",
            "house_number_suffix": "A",
            "zip_code": "111111",
            "city": "Visited City"
        },
        "invoicing_address": {
            "street": "22, Invoiced Address Street",
            "house_number": "22",
            "house_number_suffix": "A",
            "zip_code": "22222",
            "city": "Invoiced City"
        },
        "id": "13b96bc0-c496-11eb-b40f-fb944d91988d"
    },
    "status": "success",
    "message": "Successfully save company data."
}
```

-------------------------------------------------------
### 3. Upload Company Logo
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/upload_logo`

**Method:** `POST`

**Headers:**

 Header Name | Value
-------------|------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`company_logo` | Required | Image (PNG, SVG, JPEG, JPG), Max 1MB | Uploaded company logo

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`company` | Object | Object data of updated company
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "company": {
        "id": "13b96bc0-c496-11eb-b40f-fb944d91988d",
        "company_name": "Another Company",
        "visiting_address": {
            "city": "Visited City",
            "street": "11, Visited Address Street",
            "zip_code": "111111",
            "house_number": "11",
            "house_number_suffix": "A"
        },
        "invoicing_address": {
            "city": "Invoiced City",
            "street": "22, Invoiced Address Street",
            "zip_code": "22222",
            "house_number": "22",
            "house_number_suffix": "A"
        },
        "email": "company@flexavi.com",
        "email_verified_at": null,
        "phone_number": "333111222000",
        "vat_number": "111000222111",
        "commerce_chamber_number": "121",
        "company_logo_url": "http://localhost:8000/storage/companies/logos/20210604040318pp.jpeg",
        "company_website_url": "https://company.test.com/"
    },
    "status": "success",
    "message": "Successfully upload company logo"
}
```