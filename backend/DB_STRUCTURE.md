Database structure (tables):

1. users
- id, name, email, password, role (client/admin), timestamps

2. costumes
- id, name, slug, description, category_id (FK), brand, price_per_day, featured_image_url, is_active (bool), images (json legacy), available (bool), timestamps

3. costume_images
- id, costume_id (FK), image_url, position, timestamps

4. sizes
- id, costume_id (FK), size_label (S/M/L/XL), quantity_available, timestamps

5. rentals
- id, user_id (FK), costume_id (FK), size_id (FK), start_date, end_date, price_total, status (pending/confirmed/returned/cancelled), timestamps

Relations:
- costumes -> costume_images (1-n)
- costumes -> sizes (1-n)
- users -> rentals (1-n)
- costumes -> rentals (1-n)
