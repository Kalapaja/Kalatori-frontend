import { describe, test, expect, jest, beforeEach, afterEach } from "@jest/globals";
import { createOrder, getOrderStatus } from "../src/api/orders";
import { onOrderUpdate, clearAllIntervals } from "../src/api/events";

describe("Events API", () => {
    let testOrderId = "test-987654"; // Unique order for each test

    beforeEach(async () => {
        // Ensure a test order exists before tracking it
        await createOrder({ orderId: testOrderId, amount: 100, currency: "DOT" });
    });

    afterEach(() => {
        // Clear all intervals to prevent memory leaks
        clearAllIntervals();
    });

    test("Order Update Event", async () => {
        const callback = jest.fn((status) => {
            expect(status).toHaveProperty("payment_status");
        });

        onOrderUpdate(testOrderId, callback);

        // Wait for event to trigger (Jest timeout issue)
        await new Promise((resolve) => setTimeout(resolve, 6000));

        expect(callback).toHaveBeenCalled();
    });
});
