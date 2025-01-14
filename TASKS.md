# Kalatori Frontend SDK Documentation

## Overview

The **Kalatori Frontend SDK** provides a modular way to integrate blockchain payments into e-commerce platforms. It allows developers to create orders, track payment status, listen for updates, and handle currency conversions.

## Development Tasks

### **Phase 1: Core SDK Development**

1. **Set up project structure** (Monorepo with Turborepo or Nx).
2. **Set up and configure Turborepo** for monorepo management.
3. **Implement core API wrapper** for Kalatori backend.
3. **Create order management methods:** `createOrder`, `getOrderStatus`, `trackOrder`.
4. **Integrate CoinGecko API** for exchange rate conversions.
5. **Implement event-based API** (`onOrderUpdate`) for real-time tracking.
6. **Develop error handling & retry logic**.
7. **Implement authentication & API key validation**.
8. **Develop caching mechanism** to reduce redundant API calls.
9. **Add unit and integration tests** for core functions.
10. **Set up CI/CD pipeline** to automate testing and deployment.
11. **Optimize SDK for performance** (reduce latency, improve response times).
12. **Document API methods and usage examples** for developers.

### **Phase 2: UI Components**

#### **1. Core UI Component Development**
- Define shared component structure and props for consistency across React, Vue, and Svelte.
- Implement reusable logic and shared utilities for common UI interactions.
- Ensure consistent UI design and behavior across all frameworks.

#### **2. Implement Checkout UI (`KalatoriCheckout`)**
- Display order details, payment methods, and transaction status.
- Handle wallet-based and QR code payments.
- Require Terms and Conditions checkbox before allowing payment.
- Show order progress, success, and failure messages.
- Implement for:
    - **React:** `<KalatoriCheckout />`
    - **Vue:** `<KalatoriCheckoutVue />`
    - **Svelte:** `<KalatoriCheckoutSvelte />`

#### **3. Implement Currency Picker (`CurrencyPicker`)**
- Fetch supported currencies from Kalatori backend.
- Convert displayed prices using CoinGecko API.
- Implement for:
    - **React:** `<CurrencyPicker />`
    - **Vue:** `<CurrencyPickerVue />`
    - **Svelte:** `<CurrencyPickerSvelte />`

#### **4. Implement Payment QR Code (`PaymentQR`)**
- Generate and display QR codes for payments.
- Implement for:
    - **React:** `<PaymentQR />`
    - **Vue:** `<PaymentQRVue />`
    - **Svelte:** `<PaymentQRSvelte />`

#### **5. Implement Order Status Display (`OrderStatus`)**
- Track and display real-time order/payment status.
- Update dynamically when the order moves from pending → paid.
- Implement for:
    - **React:** `<OrderStatus />`
    - **Vue:** `<OrderStatusVue />`
    - **Svelte:** `<OrderStatusSvelte />`

#### **6. Implement Terms & Conditions (`TermsAndConditions`)**
- Display a Terms and Conditions checkbox.
- Prevent payment until the user agrees.
- Implement for:
    - **React:** `<TermsAndConditions />`
    - **Vue:** `<TermsAndConditionsVue />`
    - **Svelte:** `<TermsAndConditionsSvelte />`

#### **7. Performance & Optimization**
- Minimize re-renders and optimize API calls.
- Improve QR code rendering performance.
- Ensure accessibility (ARIA compliance for UI components).

#### **8. Documentation & Examples**
- Provide developer documentation for each component.
- Create code examples for React, Vue, and Svelte integrations.
- Build a demo app to showcase UI components in action.

7. **Develop core UI components for all frameworks:**
    - Checkout UI (`<KalatoriCheckout />`, `<KalatoriCheckoutVue />`, `<KalatoriCheckoutSvelte />`)
    - Currency Picker (`<CurrencyPicker />`, `<CurrencyPickerVue />`, `<CurrencyPickerSvelte />`)
    - Payment QR Code (`<PaymentQR />`, `<PaymentQRVue />`, `<PaymentQRSvelte />`)
    - Order Status Display (`<OrderStatus />`, `<OrderStatusVue />`, `<OrderStatusSvelte />`)
    - Terms and Conditions Checkbox (`<TermsAndConditions />`, `<TermsAndConditionsVue />`, `<TermsAndConditionsSvelte />`)

8. **Ensure consistent UI design and behavior** across React, Vue, and Svelte.
9. **Implement reusable logic and shared utilities** for common UI interactions.
10. **Develop state management solutions** to handle order tracking and payment status.
11. **Create documentation and usage examples** for UI components.
12. **Optimize performance and accessibility** across all UI frameworks.

7. **Create React UI components:**
    - `<KalatoriCheckout />` (handles payments, displays status, and QR code)
    - `<CurrencyPicker />` (allows users to select a currency)
    - `<PaymentQR />` (shows a scannable QR code for payments)
    - `<OrderStatus />` (displays payment confirmation and updates)
    - `<TermsAndConditions />` (checkbox to enforce agreement before payment)
    - Style and optimize React UI components.

8. **Develop Vue UI components:**
    - `<KalatoriCheckoutVue />`
    - `<CurrencyPickerVue />`
    - `<PaymentQRVue />`
    - `<OrderStatusVue />`
    - `<TermsAndConditionsVue />`
    - Style and optimize Vue UI components.

9. **Develop Svelte UI components:**
    - `<KalatoriCheckoutSvelte />`
    - `<CurrencyPickerSvelte />`
    - `<PaymentQRSvelte />`
    - `<OrderStatusSvelte />`
    - `<TermsAndConditionsSvelte />`
    - Style and optimize Svelte UI components.

7. **Create ****\`\`**** component** (handles payments, displays status, and QR code).
8. **Develop ****\`\`**** component** (allows users to select a currency).
9. **Create ****\`\`**** component** (shows a scannable QR code for payments).
10. **Implement ****\`\`**** component** (displays payment confirmation and updates).
11. **Add ****\`\`**** component** (checkbox to enforce agreement before payment).
12. **Style and optimize UI components**.

### **Phase 3: Browser Wallet Integration**

13. **Implement automatic wallet detection** (Polkadot.js, Talisman, SubWallet, etc.).
14. **Fetch available wallet accounts** upon connection.
15. **Retrieve balances and filter accounts** that have sufficient funds.
16. **Enable transaction signing & submission** from the selected wallet.
17. **Dim out accounts with insufficient funds** in UI.

### **Phase 4: Payment Experience & Security**

18. **Add support for multi-wallet integration** (Metamask, WalletConnect, etc.).
19. **Implement payment expiration and auto-cancellation logic**.
20. **Display countdown timer** for pending payments.
21. **Improve transaction failure handling** with retry and error messages.
22. **Add payment confirmation modal** (showing transaction hash, explorer link, and estimated confirmation time).
23. **Provide a developer debug mode** (`kalatori.debug(true)`) for API logging.

### **Phase 5: E-Commerce Plugin Development**

24. **Develop WooCommerce plugin** (PHP integration for Kalatori payments).
25. **Implement Shopify custom payment gateway**.
26. **Build Magento extension** for handling blockchain transactions.

### **Phase 6: CLI Installer**

31. **Implement interactive CLI prompts** to guide users through installation.
32. **Support installation of SDK, UI components, and plugins** based on user selection.
33. **Enable version selection** so users can install specific SDK versions.
34. **Automatically detect project type** (React, Vue, Shopify, WooCommerce, Magento) and configure accordingly.
35. **Generate necessary environment files** (`.env`) based on user input.
36. **Implement validation checks** for required dependencies before installation.
37. **Add rollback functionality** to revert changes in case of installation failure.
38. **Provide post-installation guidance** with clear next steps.
39. **Test CLI across different environments** (Windows, macOS, Linux).
40. **Automate updates and version checks** to notify users of new releases.
41. **Develop ****\`\`**** CLI** to automate installation.
42. **Add environment configuration setup** during installation.
43. **Verify API connectivity before completing setup**.
44. **Write documentation and onboarding guides**.

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

## License

Kalatori is open-source software licensed under GPLv3.

## Contributing

We welcome contributions! Please submit issues and pull requests to the [GitHub repository](https://github.com/kalatori/frontend-sdk).

## Support

For support and discussions, join our community on [Matrix](https://matrix.to/#/#Kalatori-support) or visit our [GitHub Discussions](https://github.com/kalatori/frontend-sdk/discussions).

