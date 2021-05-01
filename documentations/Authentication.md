# Authentication
### Login
**Endpoint:** `/api/auth/login`

**Method:** `POST`

**Payload:**
```json
{
  "email": "john@doe.com",
  "password": "Example123#"
}
```

**Validation Rules:**
- `email`: required
- `password`: required

**Sample Success Response:**
```json
{
  "data": {
    "token": "4|56DnD4xkRAsA4BZQVvXQ5wu538Hw9fTCRSFSSg4o",
    "user": {
      "id": 1,
      "name": "Example User",
      "email": "user@example.com"
    }
  },
  "message": null
}
```

**Status:** :white_check_mark: