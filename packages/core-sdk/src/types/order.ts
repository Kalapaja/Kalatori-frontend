export interface Order {
    orderId: string;
    amount: number;
    currency: string;
    callbackUrl?: string;
}

export interface OrderResponse {
    order: string;
    payment_status: string;
    amount: number;
    currency: string;
}