## Quotation

-------------------------------------------------------
### 1. Populate Company Quotations
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations`

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
`page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`quotations` | Object | The quotation object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "quotations": {
        "current_page": 1,
        "data": [
            {
                "id": "216397f0-da86-11eb-96fb-0fbd2a754c5b",
                "customer": {
                    "id": "0856dc40-da86-11eb-8290-95e8784171d9",
                    "fullname": "Customer 59 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "87",
                    "zipcode": "676621",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer59@company1.com",
                    "phone": "792452851",
                    "second_phone": null
                },
                "type": 2,
                "type_description": "Renovation",
                "quotation_date": "2021-06-30",
                "quotation_number": "OLRA1RXV",
                "contact_person": "Customer 59 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "2,296.00",
                "formatted_amount": "€ 2.296,00",
                "vat_percentage": 21,
                "formatted_vat_percentage": "21%",
                "vat_amount": 482.15999999999997,
                "formatted_vat_amount": "€ 482,16",
                "discount_amount": 300,
                "total_amount": 2478,
                "expiry_date": "2021-07-06",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
            },
            {
                "id": "2183ffa0-da86-11eb-bfa6-01b5354aab4b",
                "customer": {
                    "id": "0856d390-da86-11eb-b172-1936e958fea7",
                    "fullname": "Customer 52 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "67",
                    "zipcode": "939847",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer52@company1.com",
                    "phone": "547256559",
                    "second_phone": null
                },
                "type": 1,
                "type_description": "Leakage",
                "quotation_date": "2021-06-27",
                "quotation_number": "1L3I8KFX",
                "contact_person": "Customer 52 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "895.00",
                "formatted_amount": "€ 895,00",
                "vat_percentage": 9,
                "formatted_vat_percentage": "9%",
                "vat_amount": 80.55,
                "formatted_vat_amount": "€ 80,55",
                "discount_amount": 151,
                "total_amount": 825,
                "expiry_date": "2021-07-11",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
            },
            {
                "id": "2184e460-da86-11eb-94af-6d37970d4264",
                "customer": {
                    "id": "08570e80-da86-11eb-9164-3160349e7bda",
                    "fullname": "Customer 99 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "23",
                    "zipcode": "940635",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer99@company1.com",
                    "phone": "137608785",
                    "second_phone": null
                },
                "type": 1,
                "type_description": "Leakage",
                "quotation_date": "2021-07-02",
                "quotation_number": "GZHMIVFM",
                "contact_person": "Customer 99 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "2,072.00",
                "formatted_amount": "€ 2.072,00",
                "vat_percentage": 21,
                "formatted_vat_percentage": "21%",
                "vat_amount": 435.12,
                "formatted_vat_amount": "€ 435,12",
                "discount_amount": 244,
                "total_amount": 2263,
                "expiry_date": "2021-07-11",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
            },
            {
                "id": "2184f4d0-da86-11eb-b6c8-3986c4b817b3",
                "customer": {
                    "id": "0856aa10-da86-11eb-9572-75b726ae34da",
                    "fullname": "Customer 19 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "31",
                    "zipcode": "269466",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer19@company1.com",
                    "phone": "601001518",
                    "second_phone": null
                },
                "type": 1,
                "type_description": "Leakage",
                "quotation_date": "2021-07-02",
                "quotation_number": "IPGCI6EU",
                "contact_person": "Customer 19 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "1,370.00",
                "formatted_amount": "€ 1.370,00",
                "vat_percentage": 21,
                "formatted_vat_percentage": "21%",
                "vat_amount": 287.7,
                "formatted_vat_amount": "€ 287,70",
                "discount_amount": 128,
                "total_amount": 1530,
                "expiry_date": "2021-07-10",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
            },
            {
                "id": "218a4350-da86-11eb-8266-71635c49f47b",
                "customer": {
                    "id": "0856f080-da86-11eb-b871-8d31b1ce4c34",
                    "fullname": "Customer 75 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "100",
                    "zipcode": "291267",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer75@company1.com",
                    "phone": "771282319",
                    "second_phone": null
                },
                "type": 2,
                "type_description": "Renovation",
                "quotation_date": "2021-07-01",
                "quotation_number": "Y3OJKB2C",
                "contact_person": "Customer 75 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "2,210.00",
                "formatted_amount": "€ 2.210,00",
                "vat_percentage": 9,
                "formatted_vat_percentage": "9%",
                "vat_amount": 198.9,
                "formatted_vat_amount": "€ 198,90",
                "discount_amount": 282,
                "total_amount": 2127,
                "expiry_date": "2021-07-10",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
            },
            {
                "id": "218dea00-da86-11eb-850e-03016e6b8160",
                "customer": {
                    "id": "0856c870-da86-11eb-8476-8dad999b7c5d",
                    "fullname": "Customer 43 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "72",
                    "zipcode": "377380",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer43@company1.com",
                    "phone": "876500500",
                    "second_phone": null
                },
                "type": 1,
                "type_description": "Leakage",
                "quotation_date": "2021-07-04",
                "quotation_number": "Z36IAHUA",
                "contact_person": "Customer 43 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "2,818.00",
                "formatted_amount": "€ 2.818,00",
                "vat_percentage": 21,
                "formatted_vat_percentage": "21%",
                "vat_amount": 591.78,
                "formatted_vat_amount": "€ 591,78",
                "discount_amount": 299,
                "total_amount": 3111,
                "expiry_date": "2021-07-06",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
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
        "total": 13
    }
}
```

-------------------------------------------------------
### 2. Populate Customer Quotations
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/of_customer`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`customer_id` | Required | string, uuid | ID of customer, the quotation customer
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`quotations` | Object | The quotation object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "quotations": {
        "current_page": 1,
        "data": [
            {
                "id": "218dea00-da86-11eb-850e-03016e6b8160",
                "customer": {
                    "id": "0856c870-da86-11eb-8476-8dad999b7c5d",
                    "fullname": "Customer 43 of Company 1",
                    "salutation": "dear",
                    "address": "Customer Address Road",
                    "house_number": "72",
                    "zipcode": "377380",
                    "city": "Anycity",
                    "province": "Anyprovince",
                    "email": "customer43@company1.com",
                    "phone": "876500500",
                    "second_phone": null
                },
                "type": 1,
                "type_description": "Leakage",
                "quotation_date": "2021-07-04",
                "quotation_number": "Z36IAHUA",
                "contact_person": "Customer 43 of Company 1",
                "address": "Random Address",
                "zip_code": "111000",
                "phone_number": "02861282634",
                "quotation_description": "Hello this is seeder quotation damage descripton",
                "amount": "2,818.00",
                "formatted_amount": "€ 2.818,00",
                "vat_percentage": 21,
                "formatted_vat_percentage": "21%",
                "vat_amount": 591.78,
                "formatted_vat_amount": "€ 591,78",
                "discount_amount": 299,
                "total_amount": 3111,
                "expiry_date": "2021-07-06",
                "formatted_expiry_date": "Jul 07, 2021",
                "status": 1,
                "status_description": "Draft / Created",
                "payment_method": 1,
                "payment_method_description": "Cash",
                "created_at": "2021-07-01T16:05:18.000000Z"
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
### 3. Store Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`customer_id` | Required | UUID, String | Related Customer ID with this quotation
`type` | Required | integer | Type of quotation, `Leakage` = `1`, `Renovation` = `2`, `Reparation` = `3`, `Renewal` = `4`
`quotation_number` | Required | alpha numeric with dash with no space | Quotation Number, this must be unique for each company
`quotation_date` | Required | Date string (YYYY-MM-DD) | Quotation Date, this must have format of [YYYY-MM-DD]
`contact_person` | Required | String | The name of contact person in quotation, in some probable rare case, this can be different than customer name
`address` | Required | String | The address of customer's house
`zip_code` | Required | String | Zip Code of customer's house
`phone_number` | Required | String | The callable phone number, this number will be called whenever roofer need certain contact with customer or people in charge of the house
`damage_cause` | Required | Array, string | Damage causes needs to be fill with ARRAY, the number represents: `Leak` = `1`, `Fungus Mold` = `2`, `Bird Nuisance` = 3, `Storm Damage` = `4`, `Overdue Maintenance` = `5`
`quotation_description` | Optional | String | The additional description about quotation, can be filled y the description of damage of the roof or anything related with and informing about everything needs to be known within quotation
`expiry_date` | Optional | Date, String | The Quotation expiry date, this offer can be expired whenever reached this date, this is OPTIONAL, if you don't specify this field, the quotation expiry date will be set 3 days after the quotation is created
`vat_percentage` | Required | integer | The percentage of VAT, you can only fill this field with maximum value of 100 and minumum of 0, and remember THIS IS NOT VAT AMOUNT OF MONEY
`discount_amount` | Required | integer | The amount of discount, this is NOT DISCOUNT PERCENTAGE, so the value specified here is the amount of discount, the default of application is euro then if we specify with 125 then the discount will be EUR 125, it can be specified with 0 if there is no discount
`payment_method` | Required | integer | The payment method, the number represents: `Cash` = `1`, `Bank Transfer` = `2`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save quotation data."
}
```

-------------------------------------------------------
### 4. Update Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/update`

**Method:** `PUT` or `PATCH`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | Updated quotation
`customer_id` | Required | UUID, String | Related Customer ID with this quotation
`type` | Required | integer | Type of quotation, `Leakage` = `1`, `Renovation` = `2`, `Reparation` = `3`, `Renewal` = `4`
`quotation_number` | Required | alpha numeric with dash with no space | Quotation Number, this must be unique for each company
`quotation_date` | Required | Date string (YYYY-MM-DD) | Quotation Date, this must have format of [YYYY-MM-DD]
`contact_person` | Required | String | The name of contact person in quotation, in some probable rare case, this can be different than customer name
`address` | Required | String | The address of customer's house
`zip_code` | Required | String | Zip Code of customer's house
`phone_number` | Required | String | The callable phone number, this number will be called whenever roofer need certain contact with customer or people in charge of the house
`damage_cause` | Required | Array, string | Damage causes needs to be fill with ARRAY, the number represents: `Leak` = `1`, `Fungus Mold` = `2`, `Bird Nuisance` = `3`, `Storm Damage` = `4`, `Overdue Maintenance` = `5`
`quotation_description` | Optional | String | The additional description about quotation, can be filled y the description of damage of the roof or anything related with and informing about everything needs to be known within quotation
`expiry_date` | Optional | Date, String | The Quotation expiry date, this offer can be expired whenever reached this date, this is OPTIONAL, if you don't specify this field, the quotation expiry date will be set 3 days after the quotation is created
`vat_percentage` | Required | integer | The percentage of VAT, you can only fill this field with maximum value of 100 and minumum of 0, and remember THIS IS NOT VAT AMOUNT OF MONEY
`discount_amount` | Required | integer | The amount of discount, this is NOT DISCOUNT PERCENTAGE, so the value specified here is the amount of discount, the default of application is euro then if we specify with 125 then the discount will be EUR 125, it can be specified with 0 if there is no discount
`payment_method` | Required | integer | The payment method, the number represents: `Cash` = `1`, `Bank Transfer` = `2`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully save quotation data."
}
```

-------------------------------------------------------
### 5. Delete Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/delete`

**Method:** `DELETE`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | application/x-www-form-urlencoded

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of deleted quotation
`force` | Optional | Boolean | `true` for force delete and `false` for soft delete, the default value is `false`

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully delete quotation data."
}
```

-------------------------------------------------------
### 6. Quotation Attachments
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/attachments`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of quotation that has attachments

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`attachments` | Array | List of attachments within a quotation

**Success Response Example:**

```json
{
    "attachments": [
        {
            "id": "b72409d0-df2a-11eb-b37b-0fd4d083ad0b",
            "name": "Fake Quotation Attachment",
            "description": "This is seeder generated attachment",
            "attachment_url": "http://localhost:8000/storage/uploads/quotations/attachments/dummy.pdf"
        },
        {
            "id": "b7240ae0-df2a-11eb-b3a1-e7bb876521c4",
            "name": "Fake Quotation Attachment",
            "description": "This is seeder generated attachment",
            "attachment_url": "http://localhost:8000/storage/uploads/quotations/attachments/dummy.pdf"
        },
        {
            "id": "b7240bf0-df2a-11eb-857a-adfee470c0bd",
            "name": "Fake Quotation Attachment",
            "description": "This is seeder generated attachment",
            "attachment_url": "http://localhost:8000/storage/uploads/quotations/attachments/dummy.pdf"
        }
    ]
}
```

-------------------------------------------------------
### 7. Add Quotation Attachment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/attachments/add`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`quotation_id` | Required | UUID, string | ID of quotation that will be attached
`name` | Required | String | The name of attachment, this will describe what kind of attachment is this
`description` | Optional | String | The description about attachment, this will describe deeper for user
`attachment` | Required | File, Extension: PDF, DOC, DOCX, PNG, JPG, PNG, JPEG, SVG | The attached file, this will be downloadable content

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully add attachment to quotation."
}
```

-------------------------------------------------------
### 8. Remove Quotation Attachment
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/attachments/remove`

**Method:** `DELETE`

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of deleted Quotation Attachment, this is suppose not to be filled with Quotatin ID

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "SSuccessfully remove attachment."
}
```

-------------------------------------------------------
### 9. Send Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/send`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of sent quotation
`destination` | Optional | Email, String | Recipient email of quotation. If the value is not specified, the quotation will be sent to customer's default email
`text` | Optional | String | Text written inside the email, if the value is not specified, the text of the email is `quotation_description`.

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully send quotation to customer."
}
```

-------------------------------------------------------
### 10. Print Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/print`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of printed quotation

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully print quotation."
}
```

-------------------------------------------------------
### 8. Revise Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/revise`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of revised quotation
`discount_amount` | Optional, if `payment_method` is specified | Float, Integer | Revised discount amount
`payment_method` | Optional, if `discount_amount` is specified | integer | The payment method, the number represents: Cash = 1, BankTransfer = 2

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully revise the quotation."
}
```

-------------------------------------------------------
### 9. Honor Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/honor`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of honored quotation
`discount_amount` | Optional | Float, Integer | The optional discount amount, sometimes the company give some discount if customer do certain things like paying early and etc.

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully honor quotation."
}
```

-------------------------------------------------------
### 10. Cancel Quotation
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/cancel`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `multipart/form-data`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of cancelled quotation
`cancellation_reason` | Required | String | Reason why quotation is cancelled

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`status` | String | Request processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully cancel quotation."
}
```

-------------------------------------------------------
### 10. Quotation Works
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/quotations/works`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | UUID, string | ID of cancelled quotation

**Note:** Quotation has many works listed, to access what are those works, this endpoint will give list of works inside a quotation

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`works` | Object | The quotation works object

**Success Response Example:**

```json
{
    "works": {
        "current_page": 1,
        "data": [
            {
                "id": "4b636b90-df1b-11eb-a62c-c99c4da36c47",
                "quantity": 793,
                "quantity_unit": "l",
                "quantity_with_unit": "793 l",
                "description": "This is seeder work",
                "unit_price": 137,
                "unit_total": 108641,
                "formatted_unit_total": "€ 108.641,00",
                "include_tax": true,
                "tax_percentage": 9,
                "formatted_tax_percentage": "9%",
                "tax_amount": 9777.69,
                "formatted_tax_amount": "€ 9.777,69",
                "total_price": 118418.69,
                "formatted_total_price": "€ 118.418,69"
            },
            {
                "id": "4b636d70-df1b-11eb-87cf-cd370a6300c4",
                "quantity": 971,
                "quantity_unit": "m2",
                "quantity_with_unit": "971 m2",
                "description": "This is seeder work",
                "unit_price": 59,
                "unit_total": 57289,
                "formatted_unit_total": "€ 57.289,00",
                "include_tax": false,
                "tax_percentage": 0,
                "formatted_tax_percentage": "0%",
                "tax_amount": 0,
                "formatted_tax_amount": "€ 0,00",
                "total_price": 57289,
                "formatted_total_price": "€ 57.289,00"
            },
            {
                "id": "4b636f50-df1b-11eb-96f1-efa09b9bd2ea",
                "quantity": 303,
                "quantity_unit": "m2",
                "quantity_with_unit": "303 m2",
                "description": "This is seeder work",
                "unit_price": 78,
                "unit_total": 23634,
                "formatted_unit_total": "€ 23.634,00",
                "include_tax": false,
                "tax_percentage": 0,
                "formatted_tax_percentage": "0%",
                "tax_amount": 0,
                "formatted_tax_amount": "€ 0,00",
                "total_price": 23634,
                "formatted_total_price": "€ 23.634,00"
            },
            {
                "id": "4b637130-df1b-11eb-b616-332cd7982d04",
                "quantity": 992,
                "quantity_unit": "m2",
                "quantity_with_unit": "992 m2",
                "description": "This is seeder work",
                "unit_price": 158,
                "unit_total": 156736,
                "formatted_unit_total": "€ 156.736,00",
                "include_tax": true,
                "tax_percentage": 21,
                "formatted_tax_percentage": "21%",
                "tax_amount": 32914.56,
                "formatted_tax_amount": "€ 32.914,56",
                "total_price": 189650.56,
                "formatted_total_price": "€ 189.650,56"
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
        "to": 4,
        "total": 4
    }
}
```