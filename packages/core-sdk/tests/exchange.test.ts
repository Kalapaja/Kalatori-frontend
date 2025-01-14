import { describe, test, expect } from "@jest/globals";
import { convertPrice } from "../src";

describe("Exchange API", () => {
    test("Convert Price", async () => {
        const rate = await convertPrice("bitcoin", 1, "usd");
        expect(rate).toBeGreaterThan(0);
    });
});
