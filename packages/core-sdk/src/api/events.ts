import { EventEmitter } from "events";
import { getOrderStatus } from "./orders";

const orderEvents = new EventEmitter();
let intervalMap = new Map<string, NodeJS.Timeout>();

export function onOrderUpdate(orderId: string, callback: (status: any) => void) {
    if (intervalMap.has(orderId)) {
        clearInterval(intervalMap.get(orderId)!);
    }

    const interval = setInterval(async () => {
        try {
            const status = await getOrderStatus(orderId);
            orderEvents.emit("orderUpdated", JSON.parse(JSON.stringify(status)));
        } catch (error: any) {
            console.error("Error fetching order status:", error.message || error);

            // Stop tracking if order doesn't exist
            if (error.response?.status === 404) {
                clearInterval(interval);
                intervalMap.delete(orderId);
            }
        }
    }, 5000);

    intervalMap.set(orderId, interval);
    orderEvents.on("orderUpdated", callback);
}

export function clearAllIntervals() {
    intervalMap.forEach((interval) => clearInterval(interval));
    intervalMap.clear();
}