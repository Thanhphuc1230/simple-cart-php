# Simple Cart PHP

A simple shopping cart package for Laravel.

## Installation

```bash
composer require thanhphuc1230/simple-cart-php
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Thanhphuc1230\ShoppingCart\ShoppingCartServiceProvider" --tag="config"
```

In the `config/cart.php` file, you can configure the tax rate:

```php
return [
    'tax_rate' => 10, // 10% tax rate
    // ... other configs
];
```

## Usage

### Adding Items to Cart

```php
// Basic item
Cart::add('product_id', 'Product Name', 1, 9.99);

// With options
Cart::add('product_id', 'Product Name', 1, 9.99, [
    'size' => 'L',
    'color' => 'red'
]);

// With options and custom attributes
Cart::add('product_id', 'Product Name', 1, 9.99, 
    ['size' => 'L', 'color' => 'red'], // options
    [
        'sku' => 'SP001',
        'brand' => 'Nike',
        'category' => 'Shoes',
        'description' => 'Product description',
        'image' => 'product1.jpg',
        'weight' => 500,
        'discount' => 10
    ] // attributes
);
```

### Updating Items

```php
// Update quantity
Cart::update('product_id', 2);

// Update quantity and attributes
Cart::update('product_id', 2, [
    'discount' => 20,
    'note' => 'Updated discount'
]);
```

### Removing Items

```php
Cart::remove('product_id');
```

### Getting Item Information

```php
$item = Cart::get('product_id');
echo $item['name']; // Product Name
echo $item['sku']; // SP001
echo $item['brand']; // Nike
echo $item['discount']; // 20
```

### Getting All Items

```php
$items = Cart::content();
foreach($items as $item) {
    echo $item['name'];
    echo $item['sku'];
    echo $item['brand'];
}
```

### Counting Items

```php
Cart::count();
```

### Calculating Totals

```php
// Subtotal (before tax)
$subtotal = Cart::total();

// Tax amount
$tax = Cart::tax();

// Total with tax
$total = Cart::totalWithTax();
```

### Clearing Cart

```php
Cart::clear();
```

## License

MIT 