# Simple Invoice Application

An application project that generates simple invoices. It is built on the PHP code igniter framework. In this simple application, you can create invoices and store them in a database. This application includes two features: You can print saved invoices and download a PDF file for saved invoices.

# Release Information

First release.

# New Features

- Print Invoice
- Download Invoice PDF file

# Server Requirements

PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

# Installation

Project Location ``Xampp/htdocs``

**Step 1: Download**

Using command line
```
git clone https://github.com/Munzir-devs/simple-invoice-application.git
```

**Step 2: Update base url**

```php
// path : application/config/config.php
// URL will be like this http://localhost/simple-invoice-application/

$config['base_url'] = '';
```

**Step 3: Create database**

```
Open PHPMyAdmin and create a database called simple-invoice-application.
```

**Step 4: Create tables**

```mysql
--Run this query

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `invoice` (
  `id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `customer_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `billing_address` text COLLATE utf8_unicode_ci NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `item_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `tax` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `invoice`
ADD PRIMARY KEY (`id`);

ALTER TABLE `invoice_items`
ADD PRIMARY KEY (`id`);

ALTER TABLE `invoice`
MODIFY `id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoice_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```

**Step 5: Config Database Connection**
```php
// path : application/config/database.php
// Change username if necessary
// Change password if necessary

$db['default'] = array(
	'username' => 'root',
	'password' => '',
	'database' => 'simple-invoice-application',
);
```


