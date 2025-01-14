export class KalatoriError extends Error {
    constructor(message: string) {
        super(message);
        this.name = "KalatoriError";
    }
}

export async function retry<T>(fn: () => Promise<T>, retries: number = 3): Promise<T> {
    for (let i = 0; i < retries; i++) {
        try {
            return await fn();
        } catch (error) {
            if (i === retries - 1) throw error;
        }
    }
    throw new KalatoriError("Function failed after maximum retries");
}
