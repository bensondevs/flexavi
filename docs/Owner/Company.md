## Company

-------------------------------------------------------
### 0. About
-------------------------------------------------------

This section is going to describe you about the flow endpoints of the companies.

- The `companies` is the parent root of almost all data in the database.

- Company will have `visiting_address` and `invoicing_address`. These two addresses will be stored in `addresses` table. `companies` will have one to many [morph relationship](https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-polymorphic-relations) with `addresses`. To see details about addresses feature please see [Documentation](/docs/Owner/Address/CompanyAddress.md)

- Only user that registered as <b>Main Owner</b> can register (create) new company.

- This feature rely on the database table of `companies` which contains

Column Name | Data Type | Description
-------------------------------------
`id` | char(36), primary | Represents the ID of the company.
`company_name` | char(36) | Represents the name of company.
`email` | char(36), unique | Represents the email of company. This email should be unique for each company.
`email_verified_at` | timestamp, nullable | The time when the email of company is verified.
`phone_number` | varchar(255) | Represents the phone number of company.
`vat_number` | varchar(255) | Represents the VAT number of company.
`commerce_chamber_number` | varchar(255), nullable | Represents the commerce chamber number
`commerce_website_url` | varchar(255), nullable | Represents the commerce website url
`created_at` | timestamp | Represents the time when the company is registered (created).
`updated_at` | timestamp | Represents the time when the last time company is updated
`deleted_at` | timestamp | Represents the time when the company record is deleted. If not deleted, this column will be empty or null.

- Subscription will happen based on the company.

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
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`visiting_address` | Required | string | Visiting address street 
`visiting_address_house_number` | Required | string | Visiting address house number
`visiting_address_house_number_suffix` | Optional | string | Visiting address house number suffix
`visiting_address_zipcode` | Required | string | Visiting address zip code
`visiting_address_city` | Required | string | Visiting address city
`visiting_address_province` | Required | string | Visiting address province
`invoicing_address` | Required | string | Invoicing address street
`invoicing_address_house_number` | Required | string | Invoicing address house number
`invoicing_address_house_number_suffix` | Optional | string | Invoicing address house number suffix
`invoicing_address_zipcode` | Required | string | Invoicing address zip code
`invoicing_address_city` | Required | string | Invoicing address city
`invoicing_address_province` | Required | string | Invoicing address province
`company_name` | Required | string, unique | Unique company name
`email` | Required | string, unique | Email of the company
`phone_number` | Required | string, unique, numeric | The phone number of the company
`vat_number` | Required | string, unique | VAT number of the company
`commerce_chamber_number` | Optional | string | The commerce chamber number of company
`company_website_url` | Required | string, unique | Valid address of the user


**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
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
        "company_logo_url": "http://backend.test/storage/uploads/companys/20210503070400pp.jpeg",
        "company_website_url": "www.randomwebsite.com"
    }
}
```

-------------------------------------------------------
### 3. Update Company
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value
-------------|------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`visiting_address` | Required | string | Visiting address street 
`visiting_address_house_number` | Required | string | Visiting address house number
`visiting_address_house_number_suffix` | Optional | string | Visiting address house number suffix
`visiting_address_zipcode` | Required | string | Visiting address zip code
`visiting_address_city` | Required | string | Visiting address city
`visiting_address_province` | Required | string | Visiting address province
`invoicing_address` | Required | string | Invoicing address street
`invoicing_address_house_number` | Required | string | Invoicing address house number
`invoicing_address_house_number_suffix` | Optional | string | Invoicing address house number suffix
`invoicing_address_zipcode` | Required | string | Invoicing address zip code
`invoicing_address_city` | Required | string | Invoicing address city
`invoicing_address_province` | Required | string | Invoicing address province
`email` | Required | string, unique | Email of the company
`phone_number` | Required | string, unique, numeric | The phone number of the company
`vat_number` | Required | string, unique | VAT number of the company
`commerce_chamber_number` | Required | string | The value of the ID
`company_website_url` | Required | string, unique | Valid address of the user

**Request Body Example:**

```json
{
    "visiting_address_street": "11, Visited Address Street",
    "visiting_address_house_number": "11",
    "visiting_address_house_number_suffix": "A",
    "visiting_address_zip_code": "11111",
    "visiting_address_city": "Visited City",
    "invoicing_address_street": "22, Invoiced Address Street",
    "invoicing_address_house_number": "22",
    "invoicing_address_house_number_suffix": "B",
    "invoicing_address_zip_code": "22222",
    "invoicing_address_city": "Invoiced City",
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
----------------|-------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
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
------------|------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`company_logo` | Required | Image (PNG, SVG, JPEG, JPG), Max 1MB | Uploaded company logo

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully upload company logo"
}
```