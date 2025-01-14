# Kalatori Core SDK
This SDK provides an interface for interacting with the Kalatori Daemon.

## Installation
```sh
npm install @kalatori/core-sdk
```

## Usage
```ts
import { createOrder } from "@kalatori/core-sdk";
const order = await createOrder({ orderId: "123", amount: 100, currency: "DOT" });
console.log(order);
```

## Testing
```sh
npm test
```

## License
GPLv3