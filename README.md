# Kalatori Frontend SDK Documentation

## Overview
The **Kalatori Frontend SDK** provides a modular way to integrate blockchain payments into e-commerce platforms. It allows developers to create orders, track payment status, listen for updates, and handle currency conversions.

## Installation
You can install the SDK via npm or yarn:

```sh
npm install @kalatori/core-sdk
```

or

```sh
yarn add @kalatori/core-sdk
```

## Initialization
To use the SDK, you need to initialize it with your Kalatori API key and daemon URL:

```ts
import { KalatoriSDK } from "@kalatori/core-sdk";

const kalatori = new KalatoriSDK({
  apiKey: "YOUR_API_KEY",
  daemonUrl: "https://kalatori-daemon.example.com",
});
```

## Interface
The Kalatori Frontend SDK exposes a flexible and modular interface for developers. It provides both **headless API interactions** and **UI components** for seamless integration. The interface consists of:

### 1. **Core SDK Methods** (For direct API interactions)
- `createOrder(orderId, amount, currency, callbackUrl)`: Creates a new payment order.
- `getOrderStatus(orderId)`: Retrieves the latest status of an order.
- `trackOrder(orderId, callback)`: Polls the order status and triggers the callback when updated.
- `cancelOrder(orderId)`: Cancels an active order.
- `getSupportedCurrencies()`: Returns available currencies.
- `convertPrice(baseCurrency, amount, targetCurrency)`: Converts amounts using exchange rates.

### 2. **Event-Based API** (For real-time order tracking)
- `onOrderUpdate(callback)`: Listens for order status updates.

### 3. **UI Components** (For quick integration into React/Vue projects)
- `<KalatoriCheckout />`: A checkout UI component handling payments.
- `<CurrencyPicker />`: A currency selection dropdown.
- `<PaymentQR />`: Displays a scannable QR code for payments.
- `<OrderStatus />`: Shows real-time payment progress.
- `<PriceConverter />`: Converts prices into supported currencies.
- `<TermsAndConditions />`: Displays terms and conditions with a required checkbox for payments.

### 4. **Browser Wallet Integration**
The SDK supports **browser wallets** for seamless transaction signing. This allows users to pay directly from their wallets such as **Polkadot.js, Talisman, and SubWallet**.

- `connectWallet(provider)`: Connects to a specified wallet provider.
- `getWalletAccounts()`: Retrieves the list of accounts from the connected wallet.
- `getAccountBalances()`: Checks balances of retrieved accounts.
- `filterSufficientBalanceAccounts(amount)`: Returns accounts with sufficient balance for the transaction.
- `signAndSendTransaction(orderId, selectedAccount)`: Signs and submits a transaction using the connected wallet.

#### Terms and Conditions Enforcement
Before making a payment, the user must agree to the **Terms and Conditions** by checking a required checkbox in the UI. Only after confirming agreement will the payment options (wallet integration or QR code) become available.

#### Example Usage:
```ts
import { KalatoriSDK } from "@kalatori/core-sdk";

const kalatori = new KalatoriSDK({ apiKey: "YOUR_API_KEY" });

// Connect wallet
await kalatori.connectWallet("polkadot-js");

// Fetch accounts and check balances
const accounts = await kalatori.getWalletAccounts();
const balances = await kalatori.getAccountBalances();
const eligibleAccounts = await kalatori.filterSufficientBalanceAccounts(100);

console.log("Eligible accounts:", eligibleAccounts);

// Sign and send payment transaction from a selected eligible account
await kalatori.signAndSendTransaction("123456", eligibleAccounts[0]);
```

### 5. **CLI Installer**
A command-line installer for setting up the SDK and dependencies:
```sh
npx kalatori-installer
```

## API Reference
### `createOrder({ orderId, amount, currency, callbackUrl })`
Creates a new order in Kalatori.

```ts
const order = await kalatori.createOrder({
  orderId: "123456",
  amount: 100,
  currency: "DOT",
  callbackUrl: "https://your-shop.com/webhooks/kalatori?order=123456",
});
console.log("Payment address:", order.paymentAccount);
```

### `getOrderStatus(orderId)`
Fetches the current status of an order.

```ts
const status = await kalatori.getOrderStatus("123456");
console.log("Order Status:", status.payment_status);
```

### `trackOrder(orderId, callback)`
Continuously polls the order status and triggers the callback on updates.

```ts
kalatori.trackOrder("123456", (order) => {
  console.log(`Order ${order.orderId} is now ${order.payment_status}`);
});
```

### `cancelOrder(orderId)`
Cancels an order.

```ts
await kalatori.cancelOrder("123456");
console.log("Order cancelled.");
```

### `getSupportedCurrencies()`
Retrieves a list of supported currencies.

```ts
const currencies = await kalatori.getSupportedCurrencies();
console.log("Supported Currencies:", currencies);
```

### `convertPrice(baseCurrency, amount, targetCurrency)`
Converts an amount from one currency to another using exchange rates.

```ts
const converted = await kalatori.convertPrice("USD", 50, "DOT");
console.log("Converted Price:", converted);
```

## UI Components (React)

### `<KalatoriCheckout />`
A ready-to-use checkout component.

```tsx
import { KalatoriCheckout } from "@kalatori/ui-widgets";

<KalatoriCheckout
  orderId="123456"
  amount={50}
  baseCurrency="USD"
  onSuccess={() => alert("Payment received!")}
  onError={(err) => console.error("Payment failed:", err)}
/>
```

### `<CurrencyPicker />`
A dropdown for selecting the payment currency.

```tsx
import { CurrencyPicker } from "@kalatori/ui-widgets";

<CurrencyPicker selected="DOT" onChange={(currency) => console.log(currency)} />
```

## CLI Installer
To simplify setup, use the Kalatori CLI installer:

```sh
npx kalatori-installer
```

This will guide you through selecting a platform and installing the necessary dependencies.

## License
Kalatori is open-source software licensed under GPLv3.

## Contributing
We welcome contributions! Please submit issues and pull requests to the [GitHub repository](https://github.com/kalatori/frontend-sdk).

## Support
For support and discussions, join our community on [Matrix](https://matrix.to/#/#Kalatori-support) or visit our [GitHub Discussions](https://github.com/kalatori/frontend-sdk/discussions).



# Kalatori-frontend
Collection of frontends for Kalatori Substrate-based web shop. Example demo deployments should be running (normally, this is low-budget demo, not some high availability setup) for all models, see links below. The demo deployments use the newer backend version and thus have more features but are also not stable. To place an "order", use Alice's account in any Substrate-compatible wallet:

`bottom drive obey lake curtain smoke basket hold race lonely fit walk//Alice`

# How to install plugin to different open-source webshops:
- [OpenCart v3](/docs/opencart3_install.md)
- [PrestaShop](/docs/prestashop_install.md)
- [Woo-commerce](/docs/woocommerce_install.md)
- [Solidus](/docs/solidus_install.md)
- [Magento](/docs/magento_install.md)

# How to pay with Polkadot?
- [OpenCart v3](/docs/opencart3_pay.md)
- [PrestaShop](/docs/prestashop_pay.md)
- [Woo-commerce](/docs/woocommerce_pay.md)
- [Solidus](/docs/solidus_pay.md)
- [Magento](/docs/magento_pay.md)