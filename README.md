
---

# 🛒 KarawoHub API & Frontend

> Backend Laravel + Frontend React. API full JSON, siap connect ke frontend React. Auth, Vendors, Products, Cart, Orders, Payments, Membership, Courier, Admin Panel semua ada. 
---

## 🔥 Fitur

* **User Auth:** Register, Login, Logout
* **Products:** List, Detail, CRUD (vendor)
* **Vendors:** List, Detail, Register / Upgrade user ke vendor
* **Cart:** Tambah, Update, Delete item
* **Checkout & Orders:** Buat order, cancel, update status
* **Payments:** Integrasi QRIS + webhook notify
* **Membership:** Subscribe paket bulanan / tahunan
* **Courier:** List assignments, update shipment status
* **Admin Panel:** Validasi vendor & produk, manage orders
* **API Only:** Tanpa blade, langsung JSON response

---

## ⚡ Requirements

* PHP >= 8.2 (Laravel 11 butuh minimal 8.1)
* Composer
* MySQL / MariaDB
* Node.js + npm (untuk frontend React)
* Postman (optional, untuk testing API)

---

## 🏗️ Installation

### 1. Clone Repository

```bash
git clone https://github.com/ZARTH24/karawohub.git
cd karawohub/backend
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
```

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_db
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://127.0.0.1:8000

# Jika pake Sanctum / Mail:
SANCTUM_STATEFUL_DOMAINS=localhost
MAIL_MAILER=smtp
MAIL_HOST=mail.example.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Migrasi & Seeder

```bash
php artisan migrate --seed
```

> Seeder opsional tapi berguna untuk sample data: users, vendors, products.

### 6. Jalankan Laravel

```bash
php artisan serve
```

> Biasanya jalan di `http://127.0.0.1:8000`

### 7. Jalankan Frontend React (kalau ada)

```bash
cd frontend
npm start
```

---

## 📡 API Documentation

Gunakan Postman. File `KarawoHub.postman_collection.json` sudah include semua endpoint + body JSON. Semua GET tidak butuh body, POST/PUT/DELETE butuh body sesuai dokumentasi.

### **Auth**

* `POST /api/register`
* `POST /api/login`
* `GET /api/me` (Bearer token required)

### **Products**

* `GET /api/products`
* `GET /api/products/{id}`
* `POST /api/products` (vendor)
* `PUT /api/products/{id}` (vendor)

### **Vendors**

* `GET /api/vendors`
* `GET /api/vendors/{id}`
* `POST /api/vendor/register` (upgrade user)
* `GET /api/vendor/me`

### **Cart**

* `POST /api/cart/items`
* `PUT /api/cart/items/{id}`
* `DELETE /api/cart/items/{id}`
* `GET /api/cart`

### **Checkout & Orders**

* `POST /api/checkout`
* `GET /api/orders`
* `GET /api/orders/{id}`
* `POST /api/orders/cancel`
* `POST /api/orders/status/update` (vendor)
* `POST /api/ongkir` (Calculate shipping fee based on distance sent from frontend)

### **Payments**

* `POST /api/payments`
* `POST /api/payments/notify`

### **Membership**

* `POST /api/memberships/subscribe`
* `GET /api/memberships/me`

### **Courier**

* `GET /api/vendor/couriers`
* `GET /api/courier/assignments`
* `POST /api/courier/shipments/{id}/update_status`

### **Admin Panel**

* `GET /api/admin/vendors/pending`
* `POST /api/admin/vendors/{id}/validate`
* `GET /api/admin/products/pending`
* `POST /api/admin/products/{id}/validate`
* `GET /api/admin/orders`
* `POST /api/admin/orders/{id}/status`

> Semua endpoint butuh Bearer Token kecuali register/login. Token berbeda tiap user.

---

## 🛠️ Notes / Tips

* Vendor API cuma bisa diakses user yang sudah upgrade ke vendor.
* Membership API harus login sebagai user biasa dulu.
* Admin Panel API cuma untuk user dengan role `admin`.
* Jangan lupa generate token di Postman.

---

## ⚡ License

MIT License
Copyright (c) 2025 ZARTH24

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies, and to permit persons to whom the Software is furnished, subject to conditions in license.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND.

---
