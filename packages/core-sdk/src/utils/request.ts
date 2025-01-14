import axios from "axios";
import { authenticate } from "../api";

export async function apiRequest(url: string, method: string, data: any = null) {
    const headers = authenticate();
    try {
        const response = await axios({ url, method, data, headers });
        return response.data;
    } catch (error: any) {
        console.error("API Request Error:", error.toJSON ? error.toJSON() : error.message);
        throw error;
    }
}
