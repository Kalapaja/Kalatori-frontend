import { describe, test, expect } from "@jest/globals";
import { createOrder, getOrderStatus } from "../src";

describe("Orders API", () => {
    test("Create Order", async () => {
        const order = { orderId: "9865432123456789", amount: 100, currency: "DOT" };
        const response = await createOrder(order);
        expect(response).toHaveProperty("order");
    });

    test("Get Order Status", async () => {
        const response = await getOrderStatus("9865432123456789");
        expect(response).toHaveProperty("payment_status");
    });
});
