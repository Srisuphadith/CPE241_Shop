# Mongkol - Religious Items Marketplace

Mongkol is an e-commerce platform specializing in religious and spiritual items. The platform connects sellers and buyers in a safe and convenient marketplace.

## Features

### For Users
- Browse products by categories (Buddhist, Christian, Islamic, God, Others)
- Search for products
- View product details and reviews
- Add products to cart
- Checkout and place orders
- View order history
- Track order status

### For Sellers
- Manage product listings
- Add new products
- Edit product details
- Delete products
- View and manage orders
- Update order status

### For Administrators
- Manage users (regular users and sellers)
- Manage coupons and discounts
- View sales reports and statistics
- Monitor platform activity

## Technical Stack

- PHP 7.4+
- MySQL
- HTML5
- CSS3 (Tailwind CSS)
- JavaScript
- Font Awesome Icons

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/mongkol.git
```

2. Set up the database:
- Create a MySQL database
- Import the database schema from `db/schema.sql`

3. Configure the database connection:
- Copy `conn.example.php` to `conn.php`
- Update the database credentials in `conn.php`

4. Set up the web server:
- Point your web server to the project directory
- Ensure PHP and MySQL are installed and configured

5. Install dependencies:
```bash
npm install
```

## Directory Structure

```
mongkol/
├── admin/           # Admin panel files
├── auth/            # Authentication files
├── db/              # Database files
├── img/             # Image assets
├── navbar/          # Navigation components
├── seller/          # Seller panel files
├── user/            # User interface files
├── conn.php         # Database connection
├── footer.php       # Footer component
└── README.md        # This file
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Project Link: [https://github.com/yourusername/mongkol](https://github.com/yourusername/mongkol)

