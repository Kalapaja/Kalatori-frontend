module.exports = {
    transform: {
        "^.+\\.(ts|tsx)$": "ts-jest",
    },
    moduleFileExtensions: ["ts", "tsx", "js", "jsx", "json", "node"],
    testEnvironment: "node",
    setupFilesAfterEnv: ["<rootDir>/tests/setupTests.ts"],
};