## Payment Term Meta

-------------------------------------------------------
### 1. All Quotation Types
-------------------------------------------------------

**Endpoint:** `/api/meta/quotation/all_types`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Leakage",
    "2": "Renovation",
    "3": "Reparation",
    "4": "Renewal"
}
```

-------------------------------------------------------
### 2. All Quotation Statuses
-------------------------------------------------------

**Endpoint:** `/api/meta/quotation/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Draft / Created",
    "2": "Sent",
    "3": "Revised",
    "4": "Honored",
    "5": "Cancelled"
}
```

-------------------------------------------------------
### 3. All Quotation Payment Methods
-------------------------------------------------------

**Endpoint:** `/api/meta/quotation/all_payment_methods`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`


**Success Response Example:**

```json
{
    "1": "Cash",
    "2": "Bank Transfer"
}
```

-------------------------------------------------------
### 4. All Quotation Damage Causes
-------------------------------------------------------

**Endpoint:** `/api/meta/quotation/all_damage_causes`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`


**Success Response Example:**

```json
{
    "1": "Leak",
    "2": "Fungus / Mold",
    "3": "Bird Nuisance",
    "4": "Storm Damage",
    "5": "Overdue Maintenance"
}
```