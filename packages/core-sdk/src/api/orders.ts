import { apiRequest } from "../utils/request";
import { Order, OrderResponse } from "../types/order";

const API_BASE = process.env.KALATORI_DAEMON_URL;

export async function createOrder(order: Order): Promise<OrderResponse> {
    return apiRequest(`${API_BASE}/v2/order/${order.orderId}`, "POST", order);
}

export async function getOrderStatus(orderId: string): Promise<OrderResponse> {
    return apiRequest(`${API_BASE}/v2/order/${orderId}`, "POST");
}


export async function trackOrder(orderId: string, callback: (status: OrderResponse) => void) {
    setInterval(async () => {
        const status = await getOrderStatus(orderId);
        callback(status);
    }, 5000);
}
