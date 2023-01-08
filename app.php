<?php

// The Item class represents a single item in the shopping cart
class Item
{
    private $code;
    private $name;
    private $price;

    public function __construct($code, $name, $price)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}

// The ShoppingCart class represents a collection of items in the shopping cart
class ShoppingCart
{
    private $items = [];

    public function addItem(Item $item, $quantity)
    {
        // Add the specified quantity of the given item to the shopping cart
        for ($i = 0; $i < $quantity; $i++) {
            $this->items[] = $item;
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTotal()
    {
        // Calculate the total price of all items in the shopping cart
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getPrice();
        }
        return $total;
    }

    public function displayItems()
    {
        // Print the contents of the shopping cart
        foreach ($this->items as $item) {
            echo $item->getName() . " - " . $item->getPrice() . PHP_EOL;
        }
    }
}

// The Order class represents a customer order, including the customer, shopping cart, and invoice and payment information
class Order
{
    private $customer;
    private $cart;
    private $invoiceGenerator;
    private $paymentMethod;

    public function __construct(Customer $customer, ShoppingCart $cart)
    {
        $this->customer = $customer;
        $this->cart = $cart;
    }

    public function getItems()
    {
        return $this->cart->getItems();
    }

    public function getTotal()
    {
        return $this->cart->getTotal();
    }

    public function setInvoiceGenerator(InvoiceStrategy $generator)
    {
        $this->invoiceGenerator = $generator;
    }

    public function generateInvoice()
    {
        // Generate an invoice using the specified invoice generation strategy
        $this->invoiceGenerator->generate($this);
    }

    public function setPaymentMethod(PaymentStrategy $payment)
    {
        $this->paymentMethod = $payment;
    }

    public function payInvoice()
    {
        // Pay the invoice using the specified payment strategy
        $this->paymentMethod->pay($this->getTotal());
    }
}

// The Customer class represents a customer of the eCommerce store
class Customer
{
    private $name;
    private $address;
    private $email;

    public function __construct($name, $address, $email)
    {
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getEmail()
    {
        return $this->email;
    }
}

// The PDFInvoice class represents a strategy for generating a PDF invoice for an order
class PDFInvoice
{
    public function generate(Order $order)
    {
        // Generate a PDF invoice for the given order
        echo "Generating PDF invoice for order #" . $order->getId() . "..." . PHP_EOL;
    }
}

// The TextInvoice class represents a strategy for generating a text invoice for an order
class TextInvoice
{
    public function generate(Order $order)
    {
        // Generate a text invoice for the given order
        echo "Generating text invoice for order #" . $order->getId() . "..." . PHP_EOL;
    }
}

// The PaypalPayment class represents a strategy for paying an invoice using Paypal
class PaypalPayment
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function pay($amount)
    {
        // Pay the given amount using Paypal
        echo "Paying $" . $amount . " using Paypal (email: " . $this->email . ", password: " . $this->password . ")..." . PHP_EOL;
    }
}

// The CreditCardPayment class represents a strategy for paying an invoice using a credit card
class CreditCardPayment
{
    private $name;
    private $number;
    private $ccv;
    private $expiryMonthAndYear;

    public function __construct($name, $number, $ccv, $expiryMonthAndYear)
    {
        $this->name = $name;
        $this->number = $number;
        $this->ccv = $ccv;
        $this->expiryMonthAndYear = $expiryMonthAndYear;
    }

    public function pay($amount)
    {
        // Pay the given amount using the specified credit card
        echo "Paying $" . $amount . " using credit card (name: " . $this->name . ", number: " . $this->number . ", CCV: " . $this->ccv . ", expiry: " . $this->expiryMonthAndYear . ")..." . PHP_EOL;
    }
}

// The CashOnDelivery class represents a strategy for paying an invoice on delivery
class CashOnDelivery
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function pay($amount)
    {
        // Pay the given amount in cash on delivery
        echo "Paying $" . $amount . " in cash on delivery to customer " . $this->customer->getName() . "..." . PHP_EOL;
    }
}

// The Application class represents the main entry point of the program
class Application
{
    public static function run()
    {
        // Create a shopping cart and add some items to it
        $cart = new ShoppingCart();
        $cart->addItem(new Item("123456", "Shampoo", 9.99), 2);
        $cart->addItem(new Item("234567", "Soap", 4.99), 4);
        $cart->addItem(new Item("345678", "Toothpaste", 2.99), 1);

        // Display the items in the shopping cart
        echo "Shopping Cart:" . PHP_EOL;
        $cart->displayItems();
        echo PHP_EOL;

        // Create a customer
        $customer = new Customer("John Doe", "123 Main Street, Anytown, USA", "john@example.com");

        // Create an order and set the invoice and payment strategies
        $order = new Order($customer, $cart);
        $order->setInvoiceGenerator(new PDFInvoice());
        $order->setPaymentMethod(new CreditCardPayment("John Doe", "4111 1111 1111 1111", "123", "01/2025"));

        // Checkout the order and pay the invoice
        $order->generateInvoice();
        $order->payInvoice();
    }
}

// Run the application
Application::run();