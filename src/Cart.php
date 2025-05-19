<?php

namespace Thanhphuc1230\ShoppingCart;

use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Events\Dispatcher;

class Cart
{
    protected $session;
    protected $event;
    protected $instance;
    protected $items;

    public function __construct(SessionManager $session, Dispatcher $event)
    {
        $this->session = $session;
        $this->event = $event;
        $this->instance = 'default';
        $this->items = new Collection;
        $this->load();
    }

    public function add($id, $name, $qty, $price, $options = [], $attributes = [])
    {
        $item = [
            'id' => $id,
            'name' => $name,
            'qty' => $qty,
            'price' => $price,
            'options' => $options,
            'subtotal' => $qty * $price
        ];

        // Thêm các thuộc tính tùy chỉnh
        foreach ($attributes as $key => $value) {
            $item[$key] = $value;
        }

        $this->items->push($item);
        $this->save();

        return $item;
    }

    public function update($id, $qty, $attributes = [])
    {
        $item = $this->items->firstWhere('id', $id);
        
        if ($item) {
            $item['qty'] = $qty;
            $item['subtotal'] = $qty * $item['price'];
            
            // Cập nhật các thuộc tính tùy chỉnh
            foreach ($attributes as $key => $value) {
                $item[$key] = $value;
            }
            
            $this->save();
        }

        return $item;
    }

    public function remove($id)
    {
        $this->items = $this->items->reject(function($item) use ($id) {
            return $item['id'] === $id;
        });

        $this->save();
    }

    public function get($id)
    {
        return $this->items->firstWhere('id', $id);
    }

    public function content()
    {
        return $this->items;
    }

    public function count()
    {
        return $this->items->sum('qty');
    }

    public function total()
    {
        return $this->items->sum('subtotal');
    }

    public function tax()
    {
        $taxRate = config('cart.tax_rate', 0);
        return $this->total() * ($taxRate / 100);
    }

    public function totalWithTax()
    {
        return $this->total() + $this->tax();
    }

    public function clear()
    {
        $this->items = new Collection;
        $this->save();
    }

    protected function load()
    {
        $this->items = new Collection($this->session->get('cart.' . $this->instance, []));
    }

    protected function save()
    {
        $this->session->put('cart.' . $this->instance, $this->items->toArray());
    }
} 