## Invoice Meta

-------------------------------------------------------
### 1. All Invoice Statuses
-------------------------------------------------------

**Endpoint:** `/api/meta/invoice/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Created / Draft",
    "2": "Sent / Definitive",
    "3": "Paid",
    "4": "Payment Overdue",
    "5": "Overdue, send first reminder?",
    "6": "First Reminder Sent",
    "7": "First reminder sent, send the second reminder?",
    "8": "Second Reminder Sent",
    "9": "Second Reminder Sent, send the third reminder?",
    "10": "Third Reminder Sent",
    "11": "Overdue, debt collector?",
    "12": "Sent to debt collector",
    "13": "Paid via Debt collector"
}
```

-------------------------------------------------------
### 2. Selectable Invoice Statuses
-------------------------------------------------------

**Endpoint:** `/api/meta/invoice/selectable_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "5": "Overdue, send first reminder?",
    "6": "First Reminder Sent",
    "7": "First reminder sent, send the second reminder?",
    "8": "Second Reminder Sent",
    "9": "Second Reminder Sent, send the third reminder?",
    "10": "Third Reminder Sent",
    "11": "Overdue, debt collector?",
    "12": "Sent to debt collector",
    "13": "Paid via Debt collector"
}
```

-------------------------------------------------------
### 3. All Invoice Payment Methods
-------------------------------------------------------

**Endpoint:** `/api/meta/invoice/all_payment_methods`

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
### 3. All Invoice Damage Causes
-------------------------------------------------------

**Endpoint:** `/api/meta/invoice/all_damage_causes`

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