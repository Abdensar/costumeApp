## Postman / cURL Examples

Base URL: `http://127.0.0.1:8000/api`

1) Register

POST /register

```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"user@example.com","password":"password","password_confirmation":"password"}'
```

2) Login

POST /login

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

Response contains `token` — use as Bearer token for authenticated requests.

3) List costumes (public)

```bash
curl http://127.0.0.1:8000/api/costumes
```

4) Get costume images

```bash
curl http://127.0.0.1:8000/api/costumes/1/images
```

5) Add costume image (admin) — upload file

```bash
curl -X POST http://127.0.0.1:8000/api/costumes/1/images \
  -H "Authorization: Bearer <TOKEN>" \
  -F "file=@/path/to/photo.jpg"
```

Or provide URL

```bash
curl -X POST http://127.0.0.1:8000/api/costumes/1/images \
  -H "Authorization: Bearer <TOKEN>" \
  -F "image_url=https://example.com/photo.jpg"
```

6) List sizes

```bash
curl http://127.0.0.1:8000/api/costumes/1/sizes
```

7) Add size (admin)

```bash
curl -X POST http://127.0.0.1:8000/api/costumes/1/sizes \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"size_label":"M","quantity_available":3}'
```

8) Create rental (authenticated)

```bash
curl -X POST http://127.0.0.1:8000/api/rentals \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"costume_id":1,"size_id":1,"start_date":"2026-01-05","end_date":"2026-01-08"}'
```
