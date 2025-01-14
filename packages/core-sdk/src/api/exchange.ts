import axios from "axios";

const COINGECKO_API = "https://api.coingecko.com/api/v3/simple/price";

export async function convertPrice(baseCurrency: string, amount: number, targetCurrency: string): Promise<number> {
    const { data } = await axios.get(`${COINGECKO_API}?ids=${baseCurrency}&vs_currencies=${targetCurrency}`);
    return amount * data[baseCurrency][targetCurrency];
}