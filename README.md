# Kalatori Frontend SDK Documentation

## Overview

### How It Works with the Kalatori Daemon

The **Kalatori Frontend SDK** interfaces with the **Kalatori Daemon**, which generates unique addresses per order and tracks payments. The SDK itself **does not process transactions** but interacts with the daemon's API to facilitate payment workflows.

### Payment Flow

1. The merchant's frontend **creates an order** using the SDK.
2. The SDK **retrieves a unique payment address** from the daemon.
3. The user pays using either:
    - **Browser Wallet (Polkadot.js, Talisman, SubWallet, etc.)**
    - **QR Code for external wallet payments**
4. The daemon monitors the blockchain for the payment confirmation.
5. The frontend SDK **tracks the order status** and updates the UI accordingly.

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

To use the SDK, you need to initialize it with your Kalatori daemon URL:

```ts
import { KalatoriSDK } from "@kalatori/core-sdk";

const kalatori = new KalatoriSDK({
  daemonUrl: "https://kalatori-daemon.example.com",
});
```

## Interface

### 5. **CLI Tool for Kalatori Daemon**

The Kalatori CLI tool allows direct interaction with the Kalatori Daemon from the command line, making it easier to manage payments, check statuses, and retrieve available currencies.

#### Installation
To install the CLI tool, run:
```sh
npm install -g @kalatori/cli
```

#### Available Commands
- `kalatori status` → Checks the daemon's health and supported currencies.
- `kalatori order create --orderId 123456 --amount 50 --currency USDC` → Creates a new payment order.
- `kalatori order status --orderId 123456` → Retrieves the status of an existing order.
- `kalatori wallet accounts` → Lists available wallet accounts with balances.
- `kalatori wallet pay --orderId 123456 --account 5GrwvaEF5zXb26Fz9rcQpDWS57CtERHpNehXCPcNoHGKutQY` → Sends payment from the specified account.

#### Example Usage
```sh
kalatori order create --orderId 123456 --amount 100 --currency DOT
kalatori order status --orderId 123456
kalatori wallet accounts
kalatori wallet pay --orderId 123456 --account 5GrwvaEF5zXb26Fz9rcQpDWS57CtERHpNehXCPcNoHGKutQY
```

The Kalatori Frontend SDK exposes a flexible and modular interface for developers. It provides both **headless API interactions** and **UI components** for seamless integration. The interface consists of:

### 1. **Core SDK Methods** (For direct API interactions)

- `createOrder(orderId, amount, currency, callbackUrl)`: Creates a new payment order.
- `getOrderStatus(orderId)`: Retrieves the latest status of an order.
- `trackOrder(orderId, callback)`: Polls the order status and triggers the callback when updated.
- `getSupportedCurrencies()`: Returns available currencies.
- `convertPrice(baseCurrency, amount, targetCurrency)`: Converts amounts using exchange rates.

### 2. **Event-Based API** (For real-time order tracking)

- `onOrderUpdate(callback)`: Listens for order status updates.

### 3. **UI Components** (For quick integration into React, Vue, and Svelte projects)

#### Payment Modes

- **Embedded Mode** → The merchant's frontend handles the entire process.
- **Offsite Mode** → Users are redirected to an external Kalatori-hosted payment page.

#### Example: Redirecting to an Offsite Payment Page

```ts
const order = await kalatori.createOrder({ orderId: "123456", amount: 100, currency: "DOT" });

if (order.payment_page) {
  window.location.href = order.payment_page; // Redirect to offsite payment page
}
```

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
- `getAccountBalancesInCurrency(currency)`: Returns balances for all accounts in the selected currency.
- `getAvailableAccountsForPayment(amount, currency)`: Filters and returns only accounts that can cover the payment amount.
- `signAndSendTransaction(orderId, selectedAccount)`: Signs and submits a transaction using the connected wallet.

#### Terms and Conditions Enforcement

Before making a payment, the user must agree to the **Terms and Conditions** by checking a required checkbox in the UI. Only after confirming agreement will the payment options (wallet integration or QR code) become available.

## API Reference

### `createOrder({ orderId, amount, currency, callbackUrl })`

Creates a new order in Kalatori.

#### Callback URL Support

- If no `callbackUrl` is provided, the order will **not trigger automatic updates**.
- Use a webhook to listen for payment updates.

```ts
const order = await kalatori.createOrder({
  orderId: "123456",
  amount: 50,
  currency: "USDC",
  callbackUrl: "https://yourshop.com/webhook/kalatori",
});
```

### Webshop Integrations

Want to accept Polkadot payments on your webshop? Check out our plugins:

- [WooCommerce](/docs/woocommerce_install.md)
- [Magento](/docs/magento_install.md)
- [OpenCart](/docs/opencart3_install.md)

## Testing & Debugging

To test API interactions, use Postman or cURL:

```sh
curl -X POST https://kalatori-daemon.example.com/v2/order/123456 \
  -H "Content-Type: application/json" \
  -d '{"amount": 50, "currency": "USDC"}'
```

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